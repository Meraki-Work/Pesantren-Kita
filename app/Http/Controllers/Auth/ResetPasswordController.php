<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.lupakatasandi');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = DB::table('user')->where('email', $request->email)->first();
        if (!$user) return back()->withErrors(['email' => 'Email tidak terdaftar']);

        $otp = rand(100000, 999999);
        DB::table('user')->where('email', $request->email)->update([
            'otp_code' => $otp,
            'otp_expired_at' => Carbon::now()->addMinutes(5),
        ]);

        session(['email' => $request->email, 'otp_sent' => true]);

        Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($request) {
            $message->to($request->email)->subject('Kode OTP Reset Password');
        });

        return back()->with('success', 'Kode OTP telah dikirim ke email Anda');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $user = DB::table('user')->where('email', session('email'))->first();
        if (!$user) return back()->withErrors(['otp' => 'Email tidak valid']);
        if ($user->otp_code != $request->otp) return back()->withErrors(['otp' => 'Kode OTP salah']);
        if (Carbon::now()->gt(Carbon::parse($user->otp_expired_at))) return back()->withErrors(['otp' => 'Kode OTP kedaluwarsa']);

        session(['otp_verified' => true]);
        return back()->with('success', 'Kode OTP terverifikasi');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = session('email');
        DB::table('user')->where('email', $email)->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expired_at' => null,
        ]);

        session()->forget(['otp_sent', 'otp_verified', 'email']);

        return redirect('/login')->with('success', 'Kata sandi berhasil diubah');
    }
}
