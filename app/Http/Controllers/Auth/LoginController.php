<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required|min:6',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->kata_sandi,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role: Admin & Pengajar → /kepegawaian, lainnya → landing utama
            $user = Auth::user();
            $role = strtolower($user->role ?? '');
            $default = in_array($role, ['admin', 'pengajar']) ? '/kepegawaian' : '/';

            return redirect()->intended($default)->with('success', 'Login berhasil');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil keluar');
    }
}
