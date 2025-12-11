<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showForm()
    {
        Log::info('Mengakses halaman lupa kata sandi', [
            'session_otp_sent' => session('otp_sent'),
            'session_otp_verified' => session('otp_verified'),
            'session_email' => session('email')
        ]);

        // ✅ JANGAN reset session di sini, biarkan session tetap ada
        // session()->forget(['otp_sent', 'otp_verified', 'email']);

        return view('auth.lupakatasandi');
    }
    
    public function sendOtp(Request $request)
    {
        Log::info('Memproses permintaan OTP', [
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $request->validate(['email' => 'required|email']);

        $user = DB::table('user')->where('email', $request->email)->first();

        if (!$user) {
            Log::warning('Email tidak terdaftar saat permintaan OTP', [
                'email' => $request->email,
                'ip_address' => $request->ip()
            ]);
            return back()->withErrors(['email' => 'Email tidak terdaftar']);
        }

        $otp = rand(100000, 999999);
        $otpExpiredAt = Carbon::now()->addMinutes(5);

        DB::table('user')->where('email', $request->email)->update([
            'otp_code' => $otp,
            'otp_expired_at' => $otpExpiredAt,
        ]);

        // ✅ PERBAIKAN: Set session sebelum redirect
        session([
            'email' => $request->email,
            'otp_sent' => true,
            'otp_verified' => false
        ]);

        Log::info('OTP berhasil dibuat dan disimpan', [
            'email' => $request->email,
            'user_id' => $user->id_user ?? 'unknown',
            'otp_expires_at' => $otpExpiredAt->format('Y-m-d H:i:s')
        ]);

        try {
            Mail::raw("Kode OTP Anda adalah: $otp\n\nKode ini berlaku selama 5 menit.", function ($message) use ($request) {
                $message->to($request->email)->subject('Kode OTP Reset Password - PesantrenKita');
            });

            Log::info('Email OTP berhasil dikirim', [
                'email' => $request->email,
                'user_id' => $user->id_user ?? 'unknown'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email OTP', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'user_id' => $user->id_user ?? 'unknown'
            ]);

            return back()->withErrors(['email' => 'Gagal mengirim OTP. Silakan coba lagi.']);
        }

        // ✅ PERBAIKAN: Redirect dengan session yang sudah diset
        Log::info('Redirect ke form OTP setelah OTP terkirim', [
            'email' => $request->email,
            'session_set' => session('otp_sent')
        ]);

        return redirect()->route('password.reset.form')->with('success', 'Kode OTP telah dikirim ke email Anda');
    }

    public function verifyOtp(Request $request)
    {
        // Cek apakah OTP sudah dikirim sebelumnya
        if (!session('otp_sent') || !session('email')) {
            Log::warning('Akses verifikasi OTP tanpa session yang valid', [
                'ip_address' => $request->ip()
            ]);
            return redirect()->route('password.reset.form')->withErrors(['otp' => 'Silakan request OTP terlebih dahulu']);
        }

        Log::info('Memverifikasi kode OTP', [
            'email' => session('email'),
            'ip_address' => $request->ip()
        ]);

        $request->validate(['otp' => 'required|digits:6']);

        $email = session('email');
        $user = DB::table('user')->where('email', $email)->first();

        if (!$user) {
            Log::warning('Email tidak valid saat verifikasi OTP', [
                'session_email' => $email,
                'ip_address' => $request->ip()
            ]);
            return redirect()->route('password.reset.form')->withErrors(['otp' => 'Email tidak valid']);
        }

        if ($user->otp_code != $request->otp) {
            Log::warning('Kode OTP salah', [
                'email' => $email,
                'user_id' => $user->id_user ?? 'unknown'
            ]);
            return back()->withErrors(['otp' => 'Kode OTP salah']);
        }

        if (Carbon::now()->gt(Carbon::parse($user->otp_expired_at))) {
            Log::warning('Kode OTP kedaluwarsa', [
                'email' => $email,
                'otp_expired_at' => $user->otp_expired_at,
                'user_id' => $user->id_user ?? 'unknown'
            ]);
            return back()->withErrors(['otp' => 'Kode OTP kedaluwarsa']);
        }

        // Set session untuk menandai OTP sudah terverifikasi
        session(['otp_verified' => true]);

        Log::info('Kode OTP berhasil diverifikasi', [
            'email' => $email,
            'user_id' => $user->id_user ?? 'unknown'
        ]);

        return redirect()->route('password.reset.form')->with([
            'success' => 'Kode OTP terverifikasi',
            'showEmailForm' => false,
            'showOtpForm' => false,
            'showPasswordForm' => true
        ]);
    }

    public function updatePassword(Request $request)
    {
        // Validasi session sebelum update password
        if (!session('otp_verified') || !session('email')) {
            Log::warning('Akses update password tanpa verifikasi OTP', [
                'ip_address' => $request->ip()
            ]);
            return redirect()->route('password.reset.form')->withErrors(['password' => 'Silakan verifikasi OTP terlebih dahulu']);
        }

        Log::info('Memproses perubahan kata sandi', [
            'email' => session('email'),
            'ip_address' => $request->ip()
        ]);

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = session('email');
        $user = DB::table('user')->where('email', $email)->first();

        if (!$user) {
            Log::error('User tidak ditemukan saat update password', [
                'email' => $email,
                'ip_address' => $request->ip()
            ]);
            return redirect()->route('password.reset.form')->withErrors(['password' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }

        try {
            DB::table('user')->where('email', $email)->update([
                'password' => Hash::make($request->password),
                'otp_code' => null,
                'otp_expired_at' => null,
            ]);

            Log::info('Kata sandi berhasil diubah', [
                'email' => $email,
                'user_id' => $user->id_user ?? 'unknown',
                'ip_address' => $request->ip()
            ]);

            // Hapus semua session reset password
            session()->forget(['otp_sent', 'otp_verified', 'email']);

            Log::info('Session reset password berhasil dibersihkan', [
                'email' => $email,
                'user_id' => $user->id_user ?? 'unknown'
            ]);

            return redirect('/login')->with('success', 'Kata sandi berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Gagal mengubah kata sandi', [
                'email' => $email,
                'user_id' => $user->id_user ?? 'unknown',
                'error' => $e->getMessage(),
                'ip_address' => $request->ip()
            ]);

            return back()->withErrors(['password' => 'Terjadi kesalahan saat mengubah kata sandi. Silakan coba lagi.']);
        }
    }

    /**
     * Method untuk handle resend OTP
     */
    public function resendOtp(Request $request)
    {
        if (!session('email')) {
            return redirect()->route('password.reset.form')->withErrors(['email' => 'Silakan masukkan email terlebih dahulu']);
        }

        $email = session('email');
        $user = DB::table('user')->where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.reset.form')->withErrors(['email' => 'Email tidak terdaftar']);
        }

        // Panggil method sendOtp dengan email dari session
        $request->merge(['email' => $email]);
        return $this->sendOtp($request);
    }
}
