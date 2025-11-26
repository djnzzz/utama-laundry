<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    /**
     * Tampilkan halaman status cucian
     */
    public function index(Request $request)
    {
        $order = null;

        // Jika ada query order_sn, cari order tersebut
        if ($request->has('order_sn') && $request->order_sn) {
            $orderSn = $request->order_sn;

            // Cari order berdasarkan order_sn
            // Order hanya bisa dicek oleh pemiliknya
            $order = Order::where('order_sn', $orderSn)
                ->where('user_id', Auth::id())
                ->whereNotIn('status', ['cancelled']) // Tidak tampilkan order yang dibatalkan
                ->first();

            // Log untuk debugging
            Log::info('Status check', [
                'order_sn' => $orderSn,
                'user_id' => Auth::id(),
                'found' => $order ? 'yes' : 'no',
                'current_time_wib' => now()->format('Y-m-d H:i:s')
            ]);
        }

        return view('pages.status', compact('order'));
    }

    /**
     * Update status cucian (untuk admin - opsional)
     * Method ini bisa digunakan nanti jika ada panel admin
     */
    public function updateStatus(Request $request, $order_sn)
    {
        $validated = $request->validate([
            'status_cucian' => 'required|in:baru,dalam_antrean,proses_pengerjaan,siap_diambil,selesai'
        ]);

        try {
            $order = Order::where('order_sn', $order_sn)->firstOrFail();

            $order->update([
                'status_cucian' => $validated['status_cucian']
            ]);

            Log::info('Status cucian updated', [
                'order_sn' => $order_sn,
                'new_status' => $validated['status_cucian']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status cucian berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update status cucian', [
                'error' => $e->getMessage(),
                'order_sn' => $order_sn
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }
}