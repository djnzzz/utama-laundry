<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
            }
            
            // User biasa redirect ke home
            return redirect()->intended('/')
                ->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Proses Register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            //'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            //'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default role adalah user
        ]);

        Auth::login($user);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silahkan Login.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $isAdmin = Auth::user()->role === 'admin';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke login dengan pesan berbeda
        if ($isAdmin) {
            return redirect('/login')->with('success', 'Anda telah logout dari admin panel');
        }

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}