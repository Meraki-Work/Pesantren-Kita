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
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /**
     * Menampilkan form registrasi
     */
    public function showRegistrationForm()
    {
        // Hapus sesi sebelumnya jika ada
        Session::forget('registration_pending');
        Session::forget('pending_user_id');

        return view('auth.registrasi');
    }

    private function createFreeSubscription($ponpesId)
    {
        // Ambil plan FREE
        $freePlan = DB::table('plans')->where('slug', 'free')->first();

        if (!$freePlan) {
            throw new \Exception("Free Plan tidak ditemukan. Harap isi tabel plans.");
        }

        // Cek apakah ponpes sudah punya subscription
        $existing = DB::table('subscriptions')
            ->where('ponpes_id', $ponpesId)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return; // sudah ada, jangan buat lagi
        }

        // Buat subscription gratis
        DB::table('subscriptions')->insert([
            'ponpes_id' => $ponpesId,
            'plan_id' => $freePlan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'start_date' => now()->toDateString(),
            'current_period_end' => now()->addDays(30)->toDateString(),
            'auto_renew' => 1,
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Proses registrasi dengan OTP Email
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

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
            $isNewPonpes = false;
            $newPonpesName = null;

            if ($data['ponpes_option'] === 'existing') {
                $ponpesId = $this->verifyManualPonpesId($data['manual_ponpes_id']);
                if (!$ponpesId) {
                    return back()->withErrors([
                        'manual_ponpes_id' => 'ID Pondok Pesantren tidak valid atau tidak ditemukan.'
                    ])->withInput();
                }
            } else {
                $ponpesId = $this->createNewPonpes(trim($data['new_ponpes_name']));
                $isNewPonpes = true;
                $newPonpesName = trim($data['new_ponpes_name']);
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

            // ✅ BUAT SUBSCRIPTION GRATIS
            $this->createFreeSubscription($ponpesId);

            // ✅ SIMPAN DATA UNTUK CLEANUP JIKA GAGAL
            Session::put('registration_pending', true);
            Session::put('pending_user_id', $user->id_user);
            Session::put('pending_ponpes_id', $ponpesId);
            Session::put('is_new_ponpes', $isNewPonpes);
            if ($isNewPonpes) {
                Session::put('new_ponpes_name', $newPonpesName);
            }
            Session::put('registration_time', now()->timestamp);

            // ✅ KIRIM OTP VIA BREVO API
            $brevo = new \App\Services\BrevoApiService();
            $emailResult = $brevo->sendOtp($user->email, $otp);

            DB::commit();

            if ($emailResult['status'] === 'success') {
                return redirect()->route('verify.form')->with([
                    'email' => $user->email,
                    'user_id' => $user->id_user,
                    'success' => 'Kode OTP telah dikirim ke email Anda. Berlaku 10 menit.'
                ]);
            } else {
                // fallback - simpan ke log dan tampilkan OTP
                Log::warning("Email OTP gagal dikirim: " . $emailResult['message']);

                // Simpan ke fallback methods juga
                $this->saveOtpToFallback($user->email, $otp, $user->username, $emailResult['message']);

                return redirect()->route('verify.form')->with([
                    'email' => $user->email,
                    'user_id' => $user->id_user,
                    'otp_display' => $otp,
                    'warning' => 'Email tidak terkirim. Catat kode OTP berikut: ' . $otp
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            // Hapus sesi jika ada
            Session::forget('registration_pending');
            Session::forget('pending_user_id');

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
            ])->withInput();
        }
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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $ponpesId;
    }

    /**
     * Verifikasi ID Ponpes manual
     */
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
     * Proses verifikasi OTP dengan cleanup jika gagal
     */
    /**
     * Proses verifikasi OTP dengan cleanup jika gagal
     */
    /**
 * Proses verifikasi OTP dengan cleanup jika gagal
 */
public function verifyOtp(Request $request)
{
    try {
        // PERBAIKI VALIDASI: Gunakan input dari form
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        // ✅ CEK APAKAH INI MASIH DALAM SESI REGISTRASI
        if (!Session::get('registration_pending') || !Session::get('pending_user_id')) {
            // Jika session hilang, hapus data yang mungkin masih ada
            $this->cleanupFailedRegistration();

            return redirect()->route('registrasi.index')->withErrors([
                'error' => 'Sesi registrasi telah berakhir. Silakan registrasi ulang.'
            ]);
        }

        // ✅ DAPATKAN EMAIL DARI SESSION JIKA REQUEST KOSONG
        $email = $request->email ?? session('email');

        if (empty($email)) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan. Silakan coba lagi.'
            ])->withInput();
        }

        // ✅ CARI USER YANG MASIH PENDING
        $user = User::where('email', $email)
            ->where('otp_code', $request->otp)
            ->where('otp_expired_at', '>', now())
            ->where('status', 'pending')
            ->where('id_user', Session::get('pending_user_id')) // Pastikan user sesuai sesi
            ->first();

        if (!$user) {
            Log::warning('OTP verification failed', [
                'email' => $email,
                'user_id' => Session::get('pending_user_id'),
                'otp_provided' => $request->otp
            ]);
            
            // ❌ OTP SALAH - HAPUS USER DAN DATA TERKAIT
            $this->cleanupFailedRegistration();

            return redirect()->route('registrasi.index')->withErrors([
                'error' => 'Kode OTP salah atau sudah kedaluwarsa. Silakan registrasi ulang.'
            ]);
        }

        // Gunakan DB::transaction untuk update user
        DB::transaction(function () use ($user) {
            // ✅ UPDATE USER MENJADI AKTIF
            $user->update([
                'otp_code' => null,
                'otp_expired_at' => null,
                'email_verified_at' => now(),
                'status' => 'active',
            ]);
        });

        // ✅ HAPUS SESI REGISTRASI
        Session::forget('registration_pending');
        Session::forget('pending_user_id');
        Session::forget('pending_ponpes_id');
        Session::forget('is_new_ponpes');
        Session::forget('registration_time');

        // ✅ AUTO LOGIN SETELAH VERIFIKASI
        auth()->login($user);

        return redirect()->route('dashboard')->with([
            'success' => 'Verifikasi berhasil! Selamat datang di PesantrenKita.'
        ]);
    } catch (\Exception $e) {
        Log::error('OTP verification error: ' . $e->getMessage());

        // Log detail error untuk debugging
        Log::error('OTP Request Data:', [
            'email' => $request->email,
            'otp' => $request->otp,
            'session_email' => session('email'),
            'session_pending_user_id' => Session::get('pending_user_id')
        ]);

        return back()->withErrors([
            'error' => 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.'
        ])->withInput();
    }
}
    /**
     * Debug method untuk memeriksa data yang akan dihapus
     */
    public function debugCleanupData()
    {
        try {
            $userId = Session::get('pending_user_id');
            $ponpesId = Session::get('pending_ponpes_id');

            $userData = null;
            $ponpesData = null;
            $subscriptionData = null;

            if ($userId) {
                $userData = User::where('id_user', $userId)->first();
            }

            if ($ponpesId) {
                $ponpesData = DB::table('ponpes')->where('id_ponpes', $ponpesId)->first();
                $subscriptionData = DB::table('subscriptions')
                    ->where('ponpes_id', $ponpesId)
                    ->get();
            }

            return response()->json([
                'session_data' => [
                    'pending_user_id' => $userId,
                    'pending_ponpes_id' => $ponpesId,
                    'is_new_ponpes' => Session::get('is_new_ponpes'),
                    'registration_pending' => Session::get('registration_pending')
                ],
                'user_data' => $userData,
                'ponpes_data' => $ponpesData,
                'subscription_data' => $subscriptionData,
                'all_users_in_ponpes' => $ponpesId ?
                    User::where('ponpes_id', $ponpesId)->pluck('id_user', 'email') : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
 * Hapus data user dan ponpes jika registrasi gagal (NO TRANSACTION VERSION)
 */
private function cleanupFailedRegistration()
{
    $userId = Session::get('pending_user_id');
    $ponpesId = Session::get('pending_ponpes_id');
    $isNewPonpes = Session::get('is_new_ponpes');

    Log::info('Cleanup started (NO TRANSACTION):', [
        'user_id' => $userId,
        'ponpes_id' => $ponpesId,
        'is_new_ponpes' => $isNewPonpes
    ]);

    try {
        // 1. Hapus user tanpa transaction
        if ($userId) {
            $userDeleted = DB::table('user')
                ->where('id_user', $userId)
                ->where('status', 'pending')
                ->delete();
            
            Log::info("NO TRANSACTION User deletion: deleted {$userDeleted} row(s) for user ID: {$userId}");
        }

        // 2. Hapus ponpes baru jika tidak ada user lain
        if ($isNewPonpes && $ponpesId) {
            // Cek apakah masih ada user lain di ponpes ini
            $otherUsersCount = DB::table('user')
                ->where('ponpes_id', $ponpesId)
                ->count();

            Log::info("NO TRANSACTION Other users in ponpes {$ponpesId}: {$otherUsersCount}");

            if ($otherUsersCount === 0) {
                // Hapus subscription
                $subscriptionsDeleted = DB::table('subscriptions')
                    ->where('ponpes_id', $ponpesId)
                    ->delete();

                Log::info("NO TRANSACTION Subscriptions deleted: {$subscriptionsDeleted} for ponpes: {$ponpesId}");

                // Hapus ponpes
                $ponpesDeleted = DB::table('ponpes')
                    ->where('id_ponpes', $ponpesId)
                    ->delete();

                Log::info("NO TRANSACTION Ponpes deleted: {$ponpesDeleted} for ponpes ID: {$ponpesId}");
                
                // Verifikasi penghapusan
                $verifyPonpes = DB::table('ponpes')->where('id_ponpes', $ponpesId)->exists();
                Log::info("NO TRANSACTION Verify ponpes exists after deletion: " . ($verifyPonpes ? 'YES' : 'NO'));
            } else {
                Log::info("NO TRANSACTION Ponpes {$ponpesId} not deleted because it has {$otherUsersCount} other user(s)");
            }
        }

        Log::info('NO TRANSACTION Cleanup completed successfully');
        
    } catch (\Exception $e) {
        Log::error('NO TRANSACTION Failed to cleanup registration data: ' . $e->getMessage());
        Log::error('NO TRANSACTION Error details:', ['trace' => $e->getTraceAsString()]);
    } finally {
        // Hapus sesi REGARDLESS of success or failure
        $this->clearRegistrationSession();
    }
}

    /**
     * Clear all registration session data
     */
    private function clearRegistrationSession()
    {
        Session::forget('registration_pending');
        Session::forget('pending_user_id');
        Session::forget('pending_ponpes_id');
        Session::forget('is_new_ponpes');
        Session::forget('registration_time');
        Session::forget('new_ponpes_name');

        Log::info('Registration session cleared');
    }

    /**
     * Check session status API
     */
    public function checkSessionStatus()
    {
        if (!Session::get('registration_pending')) {
            return response()->json(['valid' => false]);
        }

        // Cek waktu expired
        $registrationTime = Session::get('registration_time');
        $expiryTime = $registrationTime + (15 * 60); // 15 menit

        if (now()->timestamp > $expiryTime) {
            // Sesi expired, hapus data
            $this->cleanupFailedRegistration();
            return response()->json(['valid' => false]);
        }

        return response()->json(['valid' => true]);
    }

    /**
     * Middleware untuk membersihkan data expired
     */
    public function checkExpiredRegistration()
    {
        if (Session::get('registration_pending') && Session::get('registration_time')) {
            $registrationTime = Session::get('registration_time');
            $expiryTime = $registrationTime + (15 * 60); // 15 menit

            if (now()->timestamp > $expiryTime) {
                // Sesi expired, hapus data
                $this->cleanupFailedRegistration();

                return redirect()->route('registrasi.index')->withErrors([
                    'error' => 'Sesi registrasi telah berakhir. Silakan registrasi ulang.'
                ]);
            }
        }

        return null;
    }

    /**
     * Menampilkan form verifikasi OTP dengan pengecekan expired
     */
    public function verifyForm()
    {
        // Cek expired registration
        $expiredCheck = $this->checkExpiredRegistration();
        if ($expiredCheck) {
            return $expiredCheck;
        }

        if (!Session::get('registration_pending')) {
            return redirect()->route('registrasi.index')->withErrors([
                'error' => 'Sesi telah berakhir. Silakan registrasi ulang.'
            ]);
        }

        return view('auth.verify-otp', [
            'email' => session('email'),
            'otp_display' => session('otp_display')
        ]);
    }

    /**
     * Resend OTP dengan pengecekan sesi
     */
    public function resendOtp(Request $request)
    {
        // Cek expired registration
        $expiredCheck = $this->checkExpiredRegistration();
        if ($expiredCheck) {
            return $expiredCheck;
        }

        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            // ✅ VERIFIKASI SESI
            if (!Session::get('pending_user_id')) {
                return back()->withErrors([
                    'email' => 'Sesi tidak valid. Silakan registrasi ulang.'
                ]);
            }

            // ✅ HANYA CARI USER YANG MASIH PENDING DAN SESUAI SESSION
            $user = User::where('email', $request->email)
                ->where('id_user', Session::get('pending_user_id'))
                ->where('status', 'pending')
                ->first();

            if (!$user) {
                // User tidak ditemukan, cleanup
                $this->cleanupFailedRegistration();

                return redirect()->route('registrasi.index')->withErrors([
                    'email' => 'Sesi telah berakhir. Silakan registrasi ulang.'
                ]);
            }

            // GENERATE OTP BARU
            $otp = rand(100000, 999999);

            $user->update([
                'otp_code' => $otp,
                'otp_expired_at' => now()->addMinutes(10),
            ]);

            // KIRIM OTP BARU via Brevo API
            $brevo = new \App\Services\BrevoApiService();
            $emailResult = $brevo->sendOtp($user->email, $otp);

            if ($emailResult['status'] === 'success') {
                return back()->with([
                    'success' => 'Kode OTP baru telah dikirim ke email Anda.'
                ]);
            } else {
                // fallback
                $this->saveOtpToFallback($user->email, $otp, $user->username, $emailResult['message']);

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

    /**
     * Kirim OTP dengan multiple fallback
     */
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
     * Simpan OTP ke fallback methods
     */
    private function saveOtpToFallback($email, $otp, $username, $errorMessage)
    {
        // Simpan ke log
        $this->saveOtpToLog($email, $otp, $username);

        // Simpan ke file txt
        $this->saveOtpToFile($email, $otp, $username);

        // Log tambahan alasan gagal
        Log::warning("OTP fallback aktif. Karena email gagal dikirim: {$errorMessage}");
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
     * Cleanup expired registrations
     */
    public function cleanupExpiredRegistrations()
    {
        try {
            $expiredTime = now()->subMinutes(20); // 20 menit yang lalu

            // Cari user pending yang sudah expired
            $expiredUsers = User::where('status', 'pending')
                ->where('created_at', '<', $expiredTime)
                ->get();

            foreach ($expiredUsers as $user) {
                DB::transaction(function () use ($user) {
                    $ponpesId = $user->ponpes_id;

                    // Hapus user
                    $user->delete();

                    // Cek apakah ponpes baru dan tidak ada user lain
                    $otherUsers = User::where('ponpes_id', $ponpesId)->count();

                    if ($otherUsers === 0) {
                        // Hapus subscription
                        DB::table('subscriptions')->where('ponpes_id', $ponpesId)->delete();

                        // Hapus ponpes jika baru dibuat (cek berdasarkan waktu)
                        $ponpes = DB::table('ponpes')->where('id_ponpes', $ponpesId)->first();

                        if ($ponpes && $ponpes->created_at > now()->subDay()) {
                            DB::table('ponpes')->where('id_ponpes', $ponpesId)->delete();
                        }
                    }

                    Log::info("Cleanup expired registration for user: {$user->email}");
                });
            }

            return count($expiredUsers) . " expired registrations cleaned up";
        } catch (\Exception $e) {
            Log::error('Failed to cleanup expired registrations: ' . $e->getMessage());
            return 'Cleanup failed: ' . $e->getMessage();
        }
    }

    /**
     * Untuk testing - tampilkan semua user pending
     */
    public function showPendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')->get();

        return response()->json([
            'count' => $pendingUsers->count(),
            'users' => $pendingUsers
        ]);
    }
}
