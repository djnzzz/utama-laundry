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