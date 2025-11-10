<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('user.login');
    }

    public function showRegisterForm()
    {
        return view('user.register');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Periksa role pengguna
            if (Auth::user()->role !== 'user') {
                // Logout jika peran tidak sesuai
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect ke login dengan pesan error
                return back()->withErrors([
                    'email' => 'You are not authorized to access this page.',
                ])->withInput();
            }

            // Redirect ke halaman indeks jika peran valid
            return redirect()->intended('/');
        }

        // Jika gagal autentikasi, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Proses register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'user', // Role default
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password), // Hash password
        ]);

        // Login otomatis setelah register
        Auth::login($user);

        // Redirect ke halaman utama setelah berhasil register
        return redirect()->route('index')->with('success', 'Account successfully created!');
    }
}
