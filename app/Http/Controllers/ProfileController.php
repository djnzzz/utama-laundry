<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.editprofil', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name'  => 'required|string|max:50',
            'phone' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:300' // max 300 KB
        ]);

        // upload foto jika ada
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $path;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Perubahan telah tersimpan.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|different:current_password',
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        $user = User::find(Auth::id());
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function deleteAccount()
    {
        $user = User::find(Auth::id());
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}
