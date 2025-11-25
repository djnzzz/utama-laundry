<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RiwayatController extends Controller
{
    /**
     * Tampilkan halaman riwayat pesanan
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status pembayaran (opsional)
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan metode pembayaran (opsional)
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan tanggal dari (opsional)
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        // Filter berdasarkan tanggal sampai (opsional)
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // PENTING: Jika tidak ada filter sama sekali, tampilkan semua order user
        // Jika ada minimal 1 filter, tampilkan yang sesuai
        
        $orders = $query->paginate(10)->withQueryString(); // withQueryString untuk maintain filter di pagination

        return view('pages.riwayat', compact('orders'));
    }

    /**
     * Hapus order dari riwayat
     */
    public function destroy($order_sn)
    {
        try {
            $order = Order::where('order_sn', $order_sn)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Hapus bukti pembayaran jika ada
            if ($order->paymentProof) {
                Storage::disk('public')->delete($order->paymentProof->file_path);
                $order->paymentProof->delete();
            }

            // Hapus order
            $order->delete();

            Log::info('Order deleted from history', [
                'order_sn' => $order_sn,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat transaksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete order', [
                'error' => $e->getMessage(),
                'order_sn' => $order_sn
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat: ' . $e->getMessage()
            ], 500);
        }
    }
}