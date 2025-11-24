<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Menampilkan form registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.registrasi');
    }

    /**
     * Proses registrasi dengan OTP Email
     */
    public function store(Request $request)
    {
        try {
            // HAPUS FIELD YANG TIDAK DIPERLUKAN
            $data = $request->all();

            if ($data['ponpes_option'] === 'existing') {
                unset($data['new_ponpes_name']);
            } else {
                unset($data['manual_ponpes_id']);
            }

            // VALIDASI
            $validator = validator($data, [
                'username' => 'required|string|max:50|unique:user,username',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:Admin,Pengajar,Keuangan',
                'ponpes_option' => 'required|in:existing,new',
                'manual_ponpes_id' => 'required_if:ponpes_option,existing|string|max:64',
                'new_ponpes_name' => 'required_if:ponpes_option,new|string|max:100',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // HANDLE PONDOK PESANTREN
            $ponpesId = null;

            if ($data['ponpes_option'] === 'existing') {
                $ponpesId = $this->verifyManualPonpesId($data['manual_ponpes_id']);
                if (!$ponpesId) {
                    return back()->withErrors([
                        'manual_ponpes_id' => 'ID Pondok Pesantren tidak valid atau tidak ditemukan.'
                    ])->withInput();
                }
            } else {
                $ponpesId = $this->createNewPonpes(trim($data['new_ponpes_name']));
            }

            // GENERATE OTP
            $otp = rand(100000, 999999);

            // ✅ BUAT USER DENGAN STATUS PENDING (BELUM VERIFIED)
            $user = User::create([
                'ponpes_id' => $ponpesId,
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'otp_code' => $otp,
                'otp_expired_at' => now()->addMinutes(10),
                'email_verified_at' => null, // ✅ BELUM VERIFIED
                'status' => 'pending', // ✅ TAMBAH FIELD STATUS
            ]);

            // ✅ KIRIM OTP
            $emailResult = $this->sendOtpWithRetry($user->email, $otp, $user->username);

            if ($emailResult['success']) {
                return redirect()->route('verify.form')->with([
                    'email' => $user->email,
                    'user_id' => $user->id_user, // ✅ SIMPAN USER ID DI SESSION
                    'success' => 'Kode OTP telah dikirim ke email Anda. Berlaku 10 menit.'
                ]);
            } else {
                return redirect()->route('verify.form')->with([
                    'email' => $user->email,
                    'user_id' => $user->id_user, // ✅ SIMPAN USER ID DI SESSION
                    'otp_display' => $otp,
                    'warning' => 'Email tidak terkirim. Catat kode OTP berikut: ' . $otp
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
            ])->withInput();
        }
    }

    /**
     * Kirim OTP dengan multiple fallback
     */
    private function sendOtpWithFallback($email, $otp, $username)
    {
        try {
            // ✅ COBA KIRIM EMAIL
            Mail::to($email)->send(new OtpMail($otp));

            Log::info('OTP email sent successfully to: ' . $email);
            return ['success' => true, 'method' => 'email'];
        } catch (\Exception $e) {
            Log::warning('Email failed, trying fallback methods: ' . $e->getMessage());

            // ✅ FALLBACK 1: SIMPAN OTP KE LOG FILE
            $this->saveOtpToLog($email, $otp, $username);

            // ✅ FALLBACK 2: SIMPAN OTP KE FILE TXT
            $this->saveOtpToFile($email, $otp, $username);

            return ['success' => false, 'method' => 'fallback'];
        }
    }

    /**
     * Simpan OTP ke log file (Fallback 1)
     */
    private function saveOtpToLog($email, $otp, $username)
    {
        $logMessage = "📧 OTP FOR: {$email} | USER: {$username} | OTP: {$otp} | TIME: " . now()->format('Y-m-d H:i:s');
        Log::channel('otp')->info($logMessage);

        // Juga log ke main log file
        Log::info($logMessage);
    }

    /**
     * Simpan OTP ke file txt (Fallback 2)
     */
    private function saveOtpToFile($email, $otp, $username)
    {
        try {
            $filename = storage_path('logs/otp_debug.txt');
            $content = "=== OTP DEBUG ===\n";
            $content .= "Email: {$email}\n";
            $content .= "Username: {$username}\n";
            $content .= "OTP: {$otp}\n";
            $content .= "Time: " . now()->format('Y-m-d H:i:s') . "\n";
            $content .= "================\n\n";

            file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            Log::error('Failed to save OTP to file: ' . $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|digits:6',
            ]);

            // ✅ CARI USER YANG MASIH PENDING
            $user = User::where('email', $request->email)
                ->where('otp_code', $request->otp)
                ->where('otp_expired_at', '>', now())
                ->where('status', 'pending') // ✅ HANYA USER PENDING
                ->first();

            if (!$user) {
                return back()->withErrors([
                    'otp' => 'Kode OTP salah, sudah kedaluwarsa, atau akun sudah diverifikasi.'
                ])->withInput();
            }

            // ✅ UPDATE USER MENJADI AKTIF
            $user->update([
                'otp_code' => null,
                'otp_expired_at' => null,
                'email_verified_at' => now(),
                'status' => 'active', // ✅ SEKARANG USER AKTIF
            ]);

            // ✅ AUTO LOGIN SETELAH VERIFIKASI
            auth()->login($user);

            return redirect()->route('dashboard')->with([
                'success' => 'Verifikasi berhasil! Selamat datang di PesantrenKita.'
            ]);
        } catch (\Exception $e) {
            Log::error('OTP verification error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.'
            ])->withInput();
        }
    }

    private function verifyManualPonpesId($manualPonpesId)
    {
        $cleanId = trim($manualPonpesId);

        if (empty($cleanId)) {
            return null;
        }

        $ponpes = DB::table('ponpes')
            ->where('id_ponpes', $cleanId)
            ->where('status', 'Aktif')
            ->first();

        return $ponpes ? $cleanId : null;
    }

    /**
     * Buat pondok pesantren baru
     */
    private function createNewPonpes($namaPonpes)
    {
        $ponpesId = 'ponpes_' . Str::random(16);

        while (DB::table('ponpes')->where('id_ponpes', $ponpesId)->exists()) {
            $ponpesId = 'ponpes_' . Str::random(16);
        }

        DB::table('ponpes')->insert([
            'id_ponpes' => $ponpesId,
            'nama_ponpes' => $namaPonpes,
            'alamat' => null,
            'tahun_berdiri' => null,
            'telp' => null,
            'email' => null,
            'logo_ponpes' => null,
            'jumlah_santri' => 0,
            'jumlah_staf' => 0,
            'pimpinan' => null,
            'status' => 'Aktif',
        ]);

        return $ponpesId;
    }

    /**
     * API untuk check ID Ponpes
     */
    public function checkPonpesId(Request $request)
    {
        try {
            $request->validate([
                'ponpes_id' => 'required|string|max:64'
            ]);

            $ponpes = DB::table('ponpes')
                ->where('id_ponpes', trim($request->ponpes_id))
                ->where('status', 'Aktif')
                ->first();

            if ($ponpes) {
                return response()->json([
                    'valid' => true,
                    'nama_ponpes' => $ponpes->nama_ponpes,
                    'message' => '✅ ID Pondok Pesantren valid'
                ]);
            }

            return response()->json([
                'valid' => false,
                'message' => '❌ ID Pondok Pesantren tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => '❌ Terjadi kesalahan saat memverifikasi ID'
            ], 500);
        }
    }

    /**
     * Menampilkan form verifikasi OTP
     */
    public function verifyForm()
    {
        if (!session('email')) {
            return redirect()->route('registrasi.index')->withErrors([
                'error' => 'Sesi telah berakhir. Silakan registrasi ulang.'
            ]);
        }

        return view('auth.verify-otp', [
            'email' => session('email'),
            'otp_display' => session('otp_display')
        ]);
    }

    private function sendOtpWithRetry($email, $otp, $username)
    {
        $maxRetries = 2;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                Log::info("Attempt {$attempt} to send OTP to: {$email}");

                Mail::to($email)->send(new OtpMail($otp));

                Log::info("✅ OTP email sent successfully to: {$email} on attempt {$attempt}");
                return ['success' => true, 'attempt' => $attempt];
            } catch (\Exception $e) {
                Log::warning("Attempt {$attempt} failed for {$email}: " . $e->getMessage());

                // Jika ini attempt terakhir, simpan ke fallback
                if ($attempt === $maxRetries) {
                    $this->saveOtpToFallback($email, $otp, $username, $e->getMessage());
                    return ['success' => false, 'error' => $e->getMessage()];
                }

                // Tunggu sebentar sebelum retry
                sleep(2);
            }
        }

        return ['success' => false, 'error' => 'All attempts failed'];
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            // ✅ HANYA CARI USER YANG MASIH PENDING
            $user = User::where('email', $request->email)
                ->where('status', 'pending')
                ->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email tidak ditemukan atau sudah diverifikasi.'
                ]);
            }

            // GENERATE OTP BARU
            $otp = rand(100000, 999999);

            $user->update([
                'otp_code' => $otp,
                'otp_expired_at' => now()->addMinutes(10),
            ]);

            // KIRIM OTP BARU
            $emailResult = $this->sendOtpWithRetry($user->email, $otp, $user->username);

            if ($emailResult['success']) {
                return back()->with([
                    'success' => 'Kode OTP baru telah dikirim ke email Anda.'
                ]);
            } else {
                return back()->with([
                    'otp_display' => $otp,
                    'warning' => 'Email tidak terkirim. Catat kode OTP berikut: ' . $otp
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Resend OTP error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengirim ulang OTP.'
            ]);
        }
    }
}
