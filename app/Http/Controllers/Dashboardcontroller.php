<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Constructor untuk inisialisasi
     */
    public function __construct()
    {
        // Jalankan pengecekan absensi otomatis setiap kali controller diakses
        $this->checkAndMarkAutoAlfa();
    }

    /**
     * Otomatis menandai alfa untuk user yang tidak absen
     */
    private function checkAndMarkAutoAlfa()
    {
        // Hanya jalankan sekali per hari per user
        $cacheKey = 'auto_alfa_check_' . Auth::id() . '_' . Carbon::today()->toDateString();
        
        if (!cache()->has($cacheKey)) {
            $this->processAutoAlfa();
            cache()->put($cacheKey, true, now()->addHours(23)); // Cache untuk 23 jam
        }
    }

    /**
     * Proses penandaan alfa otomatis
     */
    private function processAutoAlfa()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');
        
        // Waktu batas akhir untuk absen (17:00 WIB)
        $cutoffTime = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');

        // Cek apakah sudah lewat jam 17:00
        if ($now->greaterThan($cutoffTime)) {
            // Cek apakah user sudah absen hari ini
            $hasAbsenToday = DB::table('absensi')
                ->where('user_id', $user->id_user)
                ->where('ponpes_id', $user->ponpes_id)
                ->whereDate('tanggal', $today)
                ->exists();

            // Jika belum absen dan sudah lewat jam 17:00, tandai sebagai alfa
            if (!$hasAbsenToday) {
                DB::table('absensi')->insert([
                    'ponpes_id'   => $user->ponpes_id,
                    'user_id'     => $user->id_user,
                    'tanggal'     => $today,
                    'jam'         => '17:00:00',
                    'status'      => 'Alpa',
                    'keterangan'  => 'Auto: Tidak absen sampai batas waktu',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                \Illuminate\Support\Facades\Log::info('Auto Alfa marked', [
                    'user_id' => $user->id_user,
                    'username' => $user->username,
                    'date' => $today,
                    'time' => $now->toTimeString()
                ]);
            }
        }
    }

    /**
     * Scheduled task untuk penandaan alfa massal
     */
    public static function scheduleAutoAlfa()
    {
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // Ambil semua user aktif
        $activeUsers = DB::table('user')
            ->where('status', 'active')
            ->whereNotNull('ponpes_id')
            ->get(['id_user', 'ponpes_id', 'username']);

        $markedCount = 0;
        
        foreach ($activeUsers as $user) {
            // Cek apakah user sudah absen hari ini
            $hasAbsen = DB::table('absensi')
                ->where('user_id', $user->id_user)
                ->where('ponpes_id', $user->ponpes_id)
                ->whereDate('tanggal', $today)
                ->exists();

            // Jika belum absen, tandai sebagai alfa
            if (!$hasAbsen) {
                // Cek apakah sudah ada record alfa untuk hari ini (untuk menghindari duplikat)
                $hasAlfa = DB::table('absensi')
                    ->where('user_id', $user->id_user)
                    ->where('ponpes_id', $user->ponpes_id)
                    ->whereDate('tanggal', $today)
                    ->where('status', 'Alpa')
                    ->where('keterangan', 'like', 'Auto:%')
                    ->exists();

                if (!$hasAlfa) {
                    DB::table('absensi')->insert([
                        'ponpes_id'   => $user->ponpes_id,
                        'user_id'     => $user->id_user,
                        'tanggal'     => $today,
                        'jam'         => '17:00:00',
                        'status'      => 'Alpa',
                        'keterangan'  => 'Auto: Tidak absen sampai batas waktu',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);

                    $markedCount++;
                    \Illuminate\Support\Facades\Log::info('Scheduled Auto Alfa marked', [
                        'user_id' => $user->id_user,
                        'username' => $user->username,
                        'date' => $today
                    ]);
                }
            }
        }

        return "Auto alfa check completed. Marked {$markedCount} users from " . count($activeUsers) . " active users";
    }

    /**
     * Menyimpan absensi hari ini
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // ================================
        // VALIDASI JAM ABSEN (05:00–17:00)
        // ================================
        $now = Carbon::now('Asia/Jakarta');
        $start = Carbon::createFromTime(5, 0, 0, 'Asia/Jakarta');
        $end   = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');

        if (!$now->between($start, $end)) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi hanya dapat dilakukan dari jam 05:00 sampai 17:00 WIB.'
            ], 403);
        }

        // ================================
        // AMBIL DATA USER & WAKTU SEKARANG
        // ================================
        $user = Auth::user();
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->toDateString();
        $jamSekarang = Carbon::now('Asia/Jakarta')->toTimeString();

        // ================================
        // CEK SUDAH ABSEN BELUM
        // ================================
        $exists = DB::table('absensi')
            ->where('user_id', $userId)
            ->where('ponpes_id', $userPonpesId)
            ->where('tanggal', $tanggalHariIni)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen hari ini!'
            ], 400);
        }

        // ================================
        // HAPUS AUTO ALFA JIKA ADA
        // ================================
        DB::table('absensi')
            ->where('user_id', $userId)
            ->where('ponpes_id', $userPonpesId)
            ->where('tanggal', $tanggalHariIni)
            ->where('status', 'Alpa')
            ->where('keterangan', 'like', 'Auto:%')
            ->delete();

        // ================================
        // SIMPAN ABSENSI
        // ================================
        $absensiId = DB::table('absensi')->insertGetId([
            'ponpes_id'   => $userPonpesId,
            'user_id'     => $userId,
            'tanggal'     => $tanggalHariIni,
            'jam'         => $jamSekarang,
            'status'      => $request->status,
            'keterangan'  => $request->keterangan ?? '-',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Log aktivitas
        \Illuminate\Support\Facades\Log::info('Absensi stored', [
            'user_id' => $userId,
            'username' => $user->username,
            'status' => $request->status,
            'tanggal' => $tanggalHariIni,
            'jam' => $jamSekarang
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan!',
            'data' => [
                'id_absensi' => $absensiId,
                'tanggal' => $tanggalHariIni,
                'jam' => $jamSekarang,
                'status' => $request->status
            ]
        ]);
    }

    /**
     * Ambil absensi terakhir (maks 6 hari)
     */
    public function getAllAbsensi(): JsonResponse
    {
        $user = Auth::user();

        $data = DB::table('absensi')
            ->select('id_absensi', 'jam', 'tanggal', 'status', 'keterangan')
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->whereIn('id_absensi', function ($query) use ($user) {
                $query->selectRaw('MAX(id_absensi)')
                    ->from('absensi')
                    ->where('user_id', $user->id_user)
                    ->where('ponpes_id', $user->ponpes_id)
                    ->groupBy('tanggal');
            })
            ->orderByDesc('tanggal')
            ->limit(6)
            ->get();

        // Format tanggal dan jam
        $formattedData = $data->map(function ($item) {
            return [
                'id_absensi' => $item->id_absensi,
                'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                'hari' => Carbon::parse($item->tanggal)->translatedFormat('l'),
                'jam' => $item->jam ? Carbon::parse($item->jam)->format('H:i') : '-',
                'status' => $item->status,
                'keterangan' => $item->keterangan ?? '-',
                'status_color' => $this->getStatusColor($item->status)
            ];
        });

        return response()->json([
            'success' => true, 
            'data' => $formattedData
        ]);
    }

    /**
     * Helper: Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'Hadir' => 'success',
            'Izin' => 'info',
            'Sakit' => 'warning',
            'Alpa' => 'danger'
        ];
        
        return $colors[$status] ?? 'secondary';
    }

    /**
     * Absensi untuk kalender
     */
    public function getAbsensi(): JsonResponse
    {
        $user = Auth::user();

        $absensi = DB::table('absensi')
            ->select('tanggal', 'status')
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->get()
            ->groupBy('tanggal')
            ->map(function ($items) {
                return [
                    'status' => $items->first()->status,
                    'color' => $this->getStatusColor($items->first()->status)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }

    /**
     * Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Hitung total absensi
        $counts = DB::table('absensi')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->groupBy('status')
            ->pluck('total', 'status');

        $jumlahHadir = $counts['Hadir'] ?? 0;
        $jumlahIzin = $counts['Izin'] ?? 0;
        $jumlahSakit = $counts['Sakit'] ?? 0;
        $jumlahAlpa = $counts['Alpa'] ?? 0;

        // Ambil kelas
        $kelas = DB::table('kelas')
            ->select('id_kelas', 'nama_kelas')
            ->where('ponpes_id', $user->ponpes_id)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Akademik terbaik
        $akademikTerbaik = DB::table('pencapaian as p')
            ->join('santri as s', 'p.id_santri', '=', 's.id_santri')
            ->select('s.id_santri', 's.nama', 's.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe', 'Akademik')
            ->where('p.ponpes_id', $user->ponpes_id)
            ->where('s.ponpes_id', $user->ponpes_id)
            ->groupBy('s.id_santri', 's.nama', 's.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        // Tahfidz terbaik
        $tahfidzTerbaik = DB::table('pencapaian as p')
            ->join('santri as s', 'p.id_santri', '=', 's.id_santri')
            ->select('s.id_santri', 's.nama', 's.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe', 'Tahfidz')
            ->where('p.ponpes_id', $user->ponpes_id)
            ->where('s.ponpes_id', $user->ponpes_id)
            ->groupBy('s.id_santri', 's.nama', 's.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        // Cek absensi hari ini
        $todayAbsensi = DB::table('absensi')
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        // Data untuk chart absensi bulan ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $absensiBulanIni = DB::table('absensi')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->groupBy('status')
            ->pluck('total', 'status');

        // Persentase kehadiran
        $hariKerjaBulanIni = $this->hitungHariKerja($bulanIni, $tahunIni);
        $hadirBulanIni = $absensiBulanIni['Hadir'] ?? 0;
        $persentaseKehadiran = $hariKerjaBulanIni > 0 
            ? round(($hadirBulanIni / $hariKerjaBulanIni) * 100, 1) 
            : 0;

        return view('pages.dashboard', compact(
            'jumlahHadir',
            'jumlahIzin',
            'jumlahSakit',
            'jumlahAlpa',
            'kelas',
            'akademikTerbaik',
            'tahfidzTerbaik',
            'todayAbsensi',
            'absensiBulanIni',
            'persentaseKehadiran',
            'hariKerjaBulanIni',
            'hadirBulanIni'
        ));
    }

    /**
     * Grafik prestasi
     */
    public function getGrafikPrestasi(Request $request): JsonResponse
    {
        $userPonpesId = Auth::user()->ponpes_id;

        $query = DB::table('pencapaian')
            ->join('santri', 'pencapaian.id_santri', '=', 'santri.id_santri')
            ->join('kelas', 'santri.id_kelas', '=', 'kelas.id_kelas')
            ->select('pencapaian.tipe', DB::raw('COUNT(*) as total'))
            ->where('pencapaian.ponpes_id', $userPonpesId)
            ->where('santri.ponpes_id', $userPonpesId)
            ->where('kelas.ponpes_id', $userPonpesId)
            ->groupBy('pencapaian.tipe');

        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->where('kelas.nama_kelas', $request->kelas);
        }

        $data = $query->pluck('total', 'tipe');

        $types = ['Akademik', 'Non-Akademik', 'Tahfidz', 'Hafalan', 'Lainnya'];
        $result = [];
        foreach ($types as $type) {
            $result[$type] = $data[$type] ?? 0;
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Cek absensi hari ini
     */
    public function checkTodayAbsensi(): JsonResponse
    {
        $user = Auth::user();
        $tanggalHariIni = Carbon::now()->toDateString();

        $absensiHariIni = DB::table('absensi')
            ->where('user_id', $user->id_user)
            ->where('ponpes_id', $user->ponpes_id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        $response = [
            'already_absened' => $absensiHariIni !== null,
            'is_auto_alfa' => false
        ];

        if ($absensiHariIni) {
            $response['status'] = $absensiHariIni->status;
            $response['keterangan'] = $absensiHariIni->keterangan;
            $response['jam'] = $absensiHariIni->jam ? Carbon::parse($absensiHariIni->jam)->format('H:i') : null;
            
            // Cek apakah ini auto alfa
            if (strpos($absensiHariIni->keterangan ?? '', 'Auto:') !== false) {
                $response['is_auto_alfa'] = true;
                $response['can_update'] = true; // User bisa update auto alfa
            }
        }

        return response()->json($response);
    }

    /**
     * Riwayat Absensi
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $userPonpesId = $user->ponpes_id;
        $userId = $user->id_user;

        // Hitung statistik
        $stats = DB::table('absensi')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->where('ponpes_id', $userPonpesId)
            ->where('user_id', $userId)
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalAbsensi = array_sum($stats->toArray());
        $hadir = $stats['Hadir'] ?? 0;
        $izin = $stats['Izin'] ?? 0;
        $sakit = $stats['Sakit'] ?? 0;
        $alpa = $stats['Alpa'] ?? 0;

        // Hitung persentase kehadiran bulan ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $hariKerjaBulanIni = $this->hitungHariKerja($bulanIni, $tahunIni);
        $hadirBulanIni = DB::table('absensi')
            ->where('ponpes_id', $userPonpesId)
            ->where('user_id', $userId)
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->count();
        
        $persentaseKehadiran = $hariKerjaBulanIni > 0 
            ? round(($hadirBulanIni / $hariKerjaBulanIni) * 100, 1) 
            : 0;

        // Query dengan filter
        $query = DB::table('absensi')
            ->where('ponpes_id', $userPonpesId)
            ->where('user_id', $userId);

        // Filter bulan
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('tanggal', $request->month);
        }

        // Filter tahun
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('tanggal', $request->year);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Order by
        $query->orderBy('tanggal', 'desc')
              ->orderBy('jam', 'desc');

        $absensi = $query->paginate(15)->appends(request()->query());

        // Format data untuk view
        $formattedAbsensi = collect($absensi->items())->map(function ($item) {
            return (object) [
                'id_absensi' => $item->id_absensi,
                'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                'hari' => Carbon::parse($item->tanggal)->translatedFormat('l'),
                'jam' => $item->jam ? Carbon::parse($item->jam)->format('H:i') : '-',
                'status' => $item->status,
                'status_color' => $this->getStatusColor($item->status),
                'keterangan' => $item->keterangan ?? '-',
                'is_auto_alfa' => strpos($item->keterangan ?? '', 'Auto:') !== false,
                'created_at' => Carbon::parse($item->created_at)->translatedFormat('d/m/Y H:i')
            ];
        });

        return view('pages.riwayat', [
            'absensi' => $absensi,
            'formattedAbsensi' => $formattedAbsensi,
            'totalAbsensi' => $totalAbsensi,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'persentaseKehadiran' => $persentaseKehadiran,
            'hadirBulanIni' => $hadirBulanIni,
            'hariKerjaBulanIni' => $hariKerjaBulanIni,
            'filter' => [
                'month' => $request->month,
                'year' => $request->year,
                'status' => $request->status
            ]
        ]);
    }

    /**
     * Hitung jumlah hari kerja dalam bulan tertentu
     */
    private function hitungHariKerja($month, $year)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $workingDays = 0;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            // Hitung hari Senin-Jumat (1-5)
            if ($date->dayOfWeek >= 1 && $date->dayOfWeek <= 5) {
                $workingDays++;
            }
        }
        
        return $workingDays;
    }

    /**
     * API untuk mendapatkan detail absensi
     */
    public function getAbsensiDetail($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $absensi = DB::table('absensi')
                ->where('id_absensi', $id)
                ->where('ponpes_id', $user->ponpes_id)
                ->where('user_id', $user->id_user)
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }

            $responseData = [
                'id_absensi' => $absensi->id_absensi,
                'tanggal' => $absensi->tanggal,
                'tanggal_formatted' => Carbon::parse($absensi->tanggal)->translatedFormat('d F Y'),
                'hari' => Carbon::parse($absensi->tanggal)->translatedFormat('l'),
                'jam' => $absensi->jam ? Carbon::parse($absensi->jam)->format('H:i') : '-',
                'status' => $absensi->status,
                'status_color' => $this->getStatusColor($absensi->status),
                'keterangan' => $absensi->keterangan ?? '-',
                'is_auto_alfa' => strpos($absensi->keterangan ?? '', 'Auto:') !== false,
                'created_at' => $absensi->created_at,
                'created_at_formatted' => Carbon::parse($absensi->created_at)->translatedFormat('d F Y H:i'),
                'updated_at' => $absensi->updated_at,
                'updated_at_formatted' => Carbon::parse($absensi->updated_at)->translatedFormat('d F Y H:i'),
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting absensi detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk checkout (keluar)
     */
    public function checkout(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            $now = Carbon::now('Asia/Jakarta');
            
            // Validasi: Hanya bisa checkout pada hari yang sama
            $absensi = DB::table('absensi')
                ->where('id_absensi', $id)
                ->where('ponpes_id', $user->ponpes_id)
                ->where('user_id', $user->id_user)
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }

            if ($absensi->tanggal != Carbon::today()->toDateString()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checkout hanya dapat dilakukan untuk absensi hari ini'
                ], 400);
            }

            if ($absensi->status != 'Hadir') {
                return response()->json([
                    'success' => false,
                    'message' => 'Checkout hanya dapat dilakukan untuk status Hadir'
                ], 400);
            }

            // Update checkout time
            DB::table('absensi')
                ->where('id_absensi', $id)
                ->where('ponpes_id', $user->ponpes_id)
                ->where('user_id', $user->id_user)
                ->update([
                    'updated_at' => $now,
                    'keterangan' => ($absensi->keterangan ?? '') . ' [Checkout: ' . $now->format('H:i') . ']'
                ]);

            \Illuminate\Support\Facades\Log::info('Checkout successful', [
                'user_id' => $user->id_user,
                'absensi_id' => $id,
                'time' => $now->toTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil',
                'data' => [
                    'checkout_time' => $now->format('H:i'),
                    'updated_at' => $now->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error during checkout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk update status absensi (khusus Auto Alfa)
     */
    public function updateAbsensi(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Hadir,Izin,Sakit',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $user = Auth::user();
            $today = Carbon::today()->toDateString();

            // Cek apakah absensi adalah auto alfa hari ini
            $absensi = DB::table('absensi')
                ->where('id_absensi', $id)
                ->where('ponpes_id', $user->ponpes_id)
                ->where('user_id', $user->id_user)
                ->where('tanggal', $today)
                ->where('status', 'Alpa')
                ->where('keterangan', 'like', 'Auto:%')
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Auto Alfa hari ini yang dapat diupdate'
                ], 400);
            }

            // Update status dan waktu
            $now = Carbon::now('Asia/Jakarta');
            $jamSekarang = $now->toTimeString();
            
            DB::table('absensi')
                ->where('id_absensi', $id)
                ->where('ponpes_id', $user->ponpes_id)
                ->where('user_id', $user->id_user)
                ->update([
                    'status' => $validated['status'],
                    'jam' => $jamSekarang,
                    'keterangan' => 'Diupdate dari Auto Alfa: ' . ($validated['keterangan'] ?? 'Tidak ada keterangan'),
                    'updated_at' => $now,
                ]);

            \Illuminate\Support\Facades\Log::info('Absensi updated from auto alfa', [
                'user_id' => $user->id_user,
                'absensi_id' => $id,
                'old_status' => 'Alpa',
                'new_status' => $validated['status'],
                'time' => $jamSekarang
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status absensi berhasil diperbarui',
                'data' => [
                    'status' => $validated['status'],
                    'jam' => $jamSekarang,
                    'tanggal' => $today
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating absensi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk menghapus absensi (hanya untuk admin atau yang bersangkutan)
     */
    public function deleteAbsensi($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Cek apakah user adalah admin atau pemilik absensi
            $absensi = DB::table('absensi')
                ->where('id_absensi', $id)
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }

            // Hanya admin atau pemilik yang bisa menghapus
            $isAdmin = in_array($user->role, ['Admin', 'Super']);
            $isOwner = $absensi->user_id == $user->id_user;
            
            if (!$isAdmin && !$isOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus absensi ini'
                ], 403);
            }

            // Tidak bisa menghapus absensi hari ini
            if ($absensi->tanggal == Carbon::today()->toDateString()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus absensi hari ini'
                ], 400);
            }

            DB::table('absensi')
                ->where('id_absensi', $id)
                ->delete();

            \Illuminate\Support\Facades\Log::info('Absensi deleted', [
                'user_id' => $user->id_user,
                'deleted_by' => $user->username,
                'absensi_id' => $id,
                'absensi_owner' => $absensi->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting absensi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk statistik absensi bulanan
     */
    public function getMonthlyStats(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);

            $stats = DB::table('absensi')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('user_id', $user->id_user)
                ->where('ponpes_id', $user->ponpes_id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->groupBy('status')
                ->pluck('total', 'status');

            $total = array_sum($stats->toArray());
            $hadir = $stats['Hadir'] ?? 0;
            $izin = $stats['Izin'] ?? 0;
            $sakit = $stats['Sakit'] ?? 0;
            $alpa = $stats['Alpa'] ?? 0;

            $hariKerja = $this->hitungHariKerja($month, $year);
            $persentaseKehadiran = $hariKerja > 0 
                ? round(($hadir / $hariKerja) * 100, 1) 
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'persentase_kehadiran' => $persentaseKehadiran,
                    'hari_kerja' => $hariKerja,
                    'bulan' => Carbon::create($year, $month, 1)->translatedFormat('F Y')
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting monthly stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik bulanan: ' . $e->getMessage()
            ], 500);
        }
    }
}