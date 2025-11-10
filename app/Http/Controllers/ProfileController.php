<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function adminProfile()
    {
        $user = Auth::user();

        // Cek apakah user adalah admin
        if ($user->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.profile', compact('user'));
    }

    /**
     * Memproses pembaruan profil pengguna.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update profil
        $user->name = $request->name;
        $user->alamat = $request->alamat;

        // Update password jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    public function updateProfileAdmin(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update profil
        $user->name = $request->name;
        $user->alamat = $request->alamat;

        // Update password jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
}
