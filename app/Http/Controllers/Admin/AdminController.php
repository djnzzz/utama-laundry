<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaundryService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin
     */
    public function index()
    {
        // Statistik untuk dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_harga'),
            'total_users' => User::where('role', 'user')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Tampilkan halaman manage harga
     */
    public function priceManagement()
    {
        $services = LaundryService::all();
        return view('admin.price-management', compact('services'));
    }

    /**
     * Tampilkan halaman kelola pesanan
     */
    public function orderManagement(Request $request)
    {
        $query = Order::with(['user', 'paymentProof'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan status cucian
        if ($request->filled('status_cucian')) {
            $query->where('status_cucian', $request->status_cucian);
        }

        // Search by order_sn atau nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_sn', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Hitung statistik untuk filter
        $stats = [
            'waiting_verification' => Order::where('payment_status', 'waiting_verification')->count(),
            'pending' => Order::where('payment_status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.order-management', compact('orders', 'stats'));
    }

    /**
     * Detail pesanan
     */
    public function orderDetail($order_sn)
    {
        $order = Order::with(['user', 'paymentProof'])
            ->where('order_sn', $order_sn)
            ->firstOrFail();

        return view('admin.order-detail', compact('order'));
    }

    /**
     * Verifikasi bukti pembayaran
     */
    public function verifyPayment(Request $request, $order_sn)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string',
        ]);

        try {
            $order = Order::with('paymentProof')->where('order_sn', $order_sn)->firstOrFail();

            if (!$order->paymentProof) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukti pembayaran tidak ditemukan'
                ], 404);
            }

            if ($validated['action'] === 'approve') {
                // Approve pembayaran
                $order->paymentProof->update([
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);

                $order->update([
                    'payment_status' => 'paid'
                ]);

                Log::info('Payment verified by admin', [
                    'order_sn' => $order_sn,
                    'admin_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diverifikasi'
                ]);

            } else {
                // Reject pembayaran
                $order->paymentProof->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                ]);

                $order->update([
                    'payment_status' => 'pending'
                ]);

                Log::info('Payment rejected by admin', [
                    'order_sn' => $order_sn,
                    'reason' => $validated['rejection_reason'],
                    'admin_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran ditolak'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to verify payment', [
                'error' => $e->getMessage(),
                'order_sn' => $order_sn
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status cucian
     */
    public function updateOrderStatus(Request $request, $order_sn)
    {
        $validated = $request->validate([
            'status_cucian' => 'required|in:baru,dalam_antrean,proses_pengerjaan,siap_diambil,selesai'
        ]);

        try {
            $order = Order::where('order_sn', $order_sn)->firstOrFail();

            $order->update([
                'status_cucian' => $validated['status_cucian']
            ]);

            Log::info('Order status updated by admin', [
                'order_sn' => $order_sn,
                'new_status' => $validated['status_cucian'],
                'admin_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status cucian berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'error' => $e->getMessage(),
                'order_sn' => $order_sn
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update harga layanan
     */
    public function updatePrice(Request $request, $id)
    {
        $validated = $request->validate([
            'price_reguler' => 'required|numeric|min:0',
            'price_express' => 'required|numeric|min:0',
        ]);

        try {
            $service = LaundryService::findOrFail($id);
            
            $oldPriceReguler = $service->price_reguler;
            $oldPriceExpress = $service->price_express;

            $service->update([
                'price_reguler' => $validated['price_reguler'],
                'price_express' => $validated['price_express'],
            ]);

            Log::info('Price updated by admin', [
                'service_id' => $id,
                'service_name' => $service->name,
                'old_price_reguler' => $oldPriceReguler,
                'new_price_reguler' => $validated['price_reguler'],
                'old_price_express' => $oldPriceExpress,
                'new_price_express' => $validated['price_express'],
                'admin_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Harga berhasil diperbarui',
                'data' => $service
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update price', [
                'error' => $e->getMessage(),
                'service_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui harga: ' . $e->getMessage()
            ], 500);
        }
    }
}