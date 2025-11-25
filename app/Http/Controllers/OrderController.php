<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LaundryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function create()
    {
        return view('pages.order');
    }

    public function store(Request $request)
    {
        // Log untuk debugging - PERBAIKAN DI SINI
        Log::info('ORDER STORE CALLED');
        Log::info('Request data:', $request->all());
        Log::info('User ID:', ['user_id' => Auth::id()]); // UBAH DARI Auth::id() LANGSUNG

        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'paket' => 'required|in:Reguler,Express',
            'service_code' => 'required|string',
            'payment_method' => 'required|in:qris_pra,qris_pasca,cash_pra,cash_pasca',
            'total_harga' => 'required|numeric|min:0',
            'estimasi_berat' => 'nullable|numeric|min:0', // UBAH JADI NUMERIC
            'jumlah_item' => 'nullable|numeric|min:0',
            'pakaian_dalam' => 'nullable|in:Ya,Tidak',
            'jumlah_pakaian_dalam' => 'nullable|numeric|min:0',
        ]);

        try {
            // Ambil data service
            $service = LaundryService::where('code', $validated['service_code'])->firstOrFail();

            Log::info('Service found:', ['service' => $service->toArray()]);

            // Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'service_code' => $validated['service_code'],
                'service_name' => $service->name,
                'service_type' => $service->type,
                'paket' => $validated['paket'],
                'type' => $service->type,
                'payment_method' => $validated['payment_method'],
                'total_harga' => $validated['total_harga'],
                'estimasi_berat' => $validated['estimasi_berat'] ?? null,
                'jumlah_item' => $validated['jumlah_item'] ?? 0,
                'pakaian_dalam' => $validated['pakaian_dalam'] ?? null,
                'jumlah_pakaian_dalam' => $validated['jumlah_pakaian_dalam'] ?? 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'order_date' => now(),
            ]);

            Log::info('Order created successfully:', ['order_id' => $order->id]);

            // Redirect ke halaman pembayaran sesuai metode
            return redirect()->route('order.payment', $order->order_sn)  // GUNAKAN order_sn
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Order creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}