<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    /**
     * Registrasi user baru (API).
     * Buat akun + kirim OTP ke email, lalu kirim response JSON.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:user,username',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required'
        ]);

        // Generate kode OTP
        $otp = rand(100000, 999999);

        // Simpan user baru
        $user = User::create([
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'otp_code'       => $otp,
            'otp_expired_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP ke email
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengirim email OTP',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Registrasi berhasil! Kode OTP telah dikirim ke email Anda (berlaku 5 menit)',
            'user'    => [
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
            ],
        ], 201);
    }

    /**
     * Verifikasi OTP user (API).
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp)
            ->where('otp_expired_at', '>', now())
            ->first();

        if (! $user) {
            return response()->json([
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.'
            ], 400);
        }

        // Update status user
        $user->update([
            'otp_code'          => null,
            'otp_expired_at'    => null,
            'email_verified_at' => now(),
        ]);

        // Buat token API langsung
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Verifikasi berhasil!',
            'token'   => $token,
            'user'    => $user
        ], 200);
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Pastikan email sudah diverifikasi
        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Email belum diverifikasi'], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
