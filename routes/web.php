<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StatusController;
use App\Models\LaundryService;
use App\Http\Controllers\ServiceController;

// == GUEST ONLY ==
Route::get('/', fn() => view('home'));
Route::get('/info-layanan', fn() => view('pages.info'));

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'));

// == PROSES AUTH ==
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// == USER ONLY ==
Route::middleware('auth')->group(function() {
    Route::get('/profile', function() {
        return "Halaman Edit Profil"; // nanti diganti tampilan profile.blade.php
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/order', function () {
        return view('pages.order');
    });
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{order_sn}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
});

// --- Edit Profil & Update Data ---
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile/delete', [ProfileController::class, 'deleteAccount']);
});

// --- Order Routes ---
Route::middleware(['auth'])->group(function () {
    Route::get('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    
    // Redirect otomatis ke halaman pembayaran yang sesuai berdasarkan metode
    Route::get('/order/payment/{order}', [PaymentController::class, 'show'])->name('order.payment');
});

// --- Payment Routes ---
Route::middleware(['auth'])->group(function () {
    // QRIS Pra-bayar (upload bukti pembayaran)
    Route::post('/payment/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload');
    Route::get('/payment/check-status/{order}', [PaymentController::class, 'checkStatus'])->name('payment.check');
    Route::get('/payment/check-proof/{order}', [PaymentController::class, 'checkProof'])->name('payment.checkProof');
    
    // Cancel & Auto-cancel (berlaku untuk semua metode)
    Route::post('/payment/cancel-order', [PaymentController::class, 'cancelOrder'])->name('payment.cancel');
    Route::post('/payment/auto-cancel', [PaymentController::class, 'autoCancel'])->name('payment.autoCancel');
});

// --- Service Data (untuk form order) ---
Route::get('/laundry-services', [ServiceController::class, 'index']);