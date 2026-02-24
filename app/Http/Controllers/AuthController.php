<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    // Masukkan Service ke dalam Controller (Dependency Injection)
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('user.login');
    }
    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function register(RegisterRequest $request)
    {
        // 1. Validasi otomatis berjalan melalui RegisterRequest
        // 2. Logika simpan diserahkan ke Service
        $user = $this->authService->registerUser($request->validated());

        // 3. Login dan Redirect diatur oleh Controller
        Auth::login($user);

        return redirect()->route('index')->with('success', 'Account successfully created!');
    }
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/') // Redirect ke halaman yang dituju atau ke home
                ->with('success', 'Selamat datang kembali!');
        }

        // 3. Jika gagal, balikkan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ... method login tetap ada di sini namun dengan validasi dipisah jika perlu
}
