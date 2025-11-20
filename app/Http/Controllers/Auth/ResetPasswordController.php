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
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('user')->where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Email tidak terdaftar'], 404);

        $otp = rand(100000, 999999);

        DB::table('user')->where('email', $request->email)->update([
            'otp_code' => $otp,
            'otp_expired_at' => Carbon::now()->addMinutes(5),
        ]);

        Mail::raw("Kode OTP Anda adalah: $otp", function ($m) use ($request) {
            $m->to($request->email)->subject('Kode OTP Reset Password');
        });

        return response()->json(['message' => 'OTP dikirim']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = DB::table('user')->where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Email tidak valid'], 404);

        if ($user->otp_code != $request->otp) {
            return response()->json(['message' => 'OTP salah'], 401);
        }

        if (Carbon::now()->gt(Carbon::parse($user->otp_expired_at))) {
            return response()->json(['message' => 'OTP kedaluwarsa'], 401);
        }

        return response()->json(['message' => 'OTP valid']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = DB::table('user')->where('email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Email tidak valid'], 404);

        DB::table('user')->where('email', $request->email)->update([
            'password' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expired_at' => null
        ]);

        return response()->json(['message' => 'Password diperbarui']);
    }
}
