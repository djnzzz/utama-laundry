<?php

namespace App\Http\Controllers;

use App\Models\LaundryService;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Tampilkan halaman info layanan dengan harga dari database
     */
    public function index()
    {
        // Ambil semua layanan dari database
        $services = LaundryService::all();
        
        // Kelompokkan layanan berdasarkan tipe
        $kiloServices = $services->where('type', 'kiloan');
        $nonKiloServices = $services->where('type', 'non-kiloan');
        
        return view('pages.info', compact('kiloServices', 'nonKiloServices'));
    }
}