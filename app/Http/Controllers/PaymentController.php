<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Redirect ke halaman pembayaran sesuai metode yang dipilih
     */
    public function show($order_sn)
    {
        // Cari berdasarkan order_sn, bukan id
        $order = Order::where('order_sn', $order_sn)->firstOrFail();
        
        // Pastikan user hanya bisa akses pesanan mereka sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Redirect berdasarkan metode pembayaran
        switch ($order->payment_method) {
            case 'qris_pra':
                return view('pages.payment-qris-pra', compact('order'));
                
            case 'qris_pasca':
            case 'cash_pra':
            case 'cash_pasca':
                return view('pages.payment-outlet', compact('order'));
                
            default:
                abort(400, 'Metode pembayaran tidak valid');
        }
    }

    /**
     * Upload bukti pembayaran (khusus QRIS Pra-bayar)
     */
    public function uploadProof(Request $request)
    {
        $request->validate([
            'order_sn' => 'required|exists:orders,order_sn',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = Order::where('order_sn', $request->order_sn)->firstOrFail();
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($order->payment_method !== 'qris_pra') {
            return response()->json(['success' => false, 'message' => 'Metode pembayaran tidak valid'], 400);
        }

        try {
            // Hapus bukti lama jika ada
            if ($order->paymentProof) {
                Storage::disk('public')->delete($order->paymentProof->file_path);
                $order->paymentProof->delete();
            }

            // Upload file baru
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $order->order_sn . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            // Simpan ke database
            PaymentProof::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'uploaded_at' => now(),
                'status' => 'pending',
            ]);

            // Update status order
            $order->update([
                'payment_status' => 'waiting_verification'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil dikirim'
            ]);

        } catch (\Exception $e) {
            Log::error('Upload proof failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek status verifikasi pembayaran (untuk polling)
     */
    public function checkStatus($order_sn)
    {
        $order = Order::with('paymentProof')->where('order_sn', $order_sn)->firstOrFail();
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $status = 'pending';
        
        if ($order->paymentProof) {
            $status = $order->paymentProof->status;
        }

        return response()->json([
            'status' => $status,
            'payment_status' => $order->payment_status
        ]);
    }

    /**
     * Cek apakah user sudah upload bukti (untuk refresh page)
     */
    public function checkProof($order_sn)
    {
        $order = Order::with('paymentProof')->where('order_sn', $order_sn)->firstOrFail();
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'has_proof' => $order->paymentProof !== null,
            'status' => $order->paymentProof?->status ?? 'none'
        ]);
    }

    /**
     * Cancel order oleh user
     */
    public function cancelOrder(Request $request)
    {
        Log::info('Cancel order called', ['request' => $request->all()]);

        $request->validate([
            'order_sn' => 'required|exists:orders,order_sn'
        ]);

        $order = Order::where('order_sn', $request->order_sn)->firstOrFail();
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Hanya bisa cancel jika belum diproses
        if (!in_array($order->payment_status, ['pending', 'waiting_verification'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan karena sudah diproses'
            ], 400);
        }

        try {
            // Hapus bukti pembayaran jika ada
            if ($order->paymentProof) {
                Storage::disk('public')->delete($order->paymentProof->file_path);
                $order->paymentProof->delete();
            }

            // Update status
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => 'user'
            ]);

            Log::info('Order cancelled successfully', ['order_sn' => $order->order_sn]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            Log::error('Cancel order failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-cancel saat timeout
     */
    public function autoCancel(Request $request)
    {
        $request->validate([
            'order_sn' => 'required|exists:orders,order_sn'
        ]);

        $order = Order::where('order_sn', $request->order_sn)->firstOrFail();
        
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Hapus bukti pembayaran jika ada
            if ($order->paymentProof) {
                Storage::disk('public')->delete($order->paymentProof->file_path);
                $order->paymentProof->delete();
            }

            // Update status
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'timeout',
                'cancelled_at' => now(),
                'cancelled_by' => 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan otomatis dibatalkan karena timeout'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal auto-cancel pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}