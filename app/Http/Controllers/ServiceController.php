<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaundryService;

class ServiceController extends Controller
{
    public function index()
    {
        $services = LaundryService::all();
        return response()->json($services);
    }
}
