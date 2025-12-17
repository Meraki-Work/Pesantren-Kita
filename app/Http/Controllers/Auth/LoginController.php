<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('status', 'active')
            ->first();
        if ($user) {
            // Coba bcrypt dulu (jika password baru menggunakan bcrypt)
            if (Hash::check($request->password, $user->password)) {
                \Log::info('Bcrypt password match');
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
            }

            // Jika bcrypt gagal, coba algoritma lain
            if ($this->checkLegacyPassword($request->password, $user->password)) {
                \Log::info('Legacy password match');

                // Opsional: Update ke bcrypt untuk masa depan
                $user->password = bcrypt($request->password);
                $user->save();
                \Log::info('Password updated to bcrypt');

                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
            }

            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email tidak ditemukan atau akun belum aktif.'
                ])->onlyInput('email');
            }
        }

        \Log::warning('Login failed for: ' . $request->email);
        return back()->withErrors([
            'email' => 'Email atau kata sandi salah',
        ])->onlyInput('email');
    }

    /**
     * Check password dengan algoritma legacy
     */
    private function checkLegacyPassword($plainPassword, $hashedPassword)
    {
        // Coba MD5 (umum di sistem legacy)
        if (md5($plainPassword) === $hashedPassword) {
            return true;
        }

        // Coba SHA1
        if (sha1($plainPassword) === $hashedPassword) {
            return true;
        }

        // Coba base64 (jika password disimpan plain text encoded)
        if (base64_encode($plainPassword) === $hashedPassword) {
            return true;
        }

        // Tambahkan algoritma lain sesuai kebutuhan
        return false;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil keluar');
    }
}
