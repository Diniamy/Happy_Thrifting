<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Hitung jumlah produk
        $productCount = Product::count();

        // Hitung jumlah user dengan role 'user'
        $userCount = User::where('role', 'user')->count();

        $orderCount = Order::count();

        // Hitung total profit berdasarkan total_harga dari order yang statusnya 'completed'
        $totalProfit = Order::where('status', 'completed')->sum('total_harga');

        // Kirim data ke view dashboard
        return view('admin.dashboard', compact('productCount', 'userCount', 'totalProfit', 'orderCount'));
    }


    public function showLoginForm()
    {
        return view('admin.login');
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
            if (Auth::user()->role !== 'admin') {
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
            return redirect()->intended('/admin/dashboard');
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

    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Kecualikan email milik user saat ini
            'alamat' => 'nullable|string|max:255',
            'role' => 'required|string|max:20', // Misalnya 'user' atau 'admin'
        ]);

        // Perbarui data user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'role' => $request->role,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }


    public function destroy(User $user)
    {


        // Hapus produk
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
