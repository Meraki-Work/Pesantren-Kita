<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:6',
                'ponpes_id' => 'required|integer',
                'role' => 'required',
            ]);

            $otp = rand(100000, 999999);

            $user = User::create([
                'username'       => $request->username,
                'email'          => $request->email,
                'password'       => bcrypt($request->password),
                'role'           => $request->role,
                'ponpes_id'      => $request->ponpes_id,
                'otp_code'       => $otp,
                'otp_expired_at' => now()->addMinutes(10),
            ]);

            Mail::to($user->email)->send(new OtpMail($otp));

            return redirect()->route('verify.form')->with([
                'email'   => $user->email,
                'success' => 'Kode OTP telah dikirim ke email Anda. Berlaku 10 menit.'
            ]);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    // Hanya menampilkan form OTP
    public function verifyForm()
    {
        return view('auth.verify-otp');
    }

    // Memproses OTP
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

        if (!$user) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        $user->update([
            'otp_code' => null,
            'otp_expired_at' => null,
            'email_verified_at' => now(),
        ]);

        return redirect('/login')->with('success', 'Verifikasi berhasil. Silakan login.');
    }
}
