<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }


    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($request->only('email','password'))) {
            $request->session()->regenerate(); // TAMBAHKAN INI
            
            return redirect('/')->with('success', 'Login berhasil! Selamat datang 🎉');
        }
    
        return back()->with('error', 'Login gagal! Email atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Berhasil logout dari akun');
    }
}