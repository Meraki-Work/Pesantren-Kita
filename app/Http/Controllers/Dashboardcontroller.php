<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Ponpes;

class DashboardController extends Controller
{
    /**
     * Menyimpan absensi hari ini
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string|max:255',
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
        // SIMPAN ABSENSI
        // ================================
        DB::table('absensi')->insert([
            'ponpes_id'   => $userPonpesId,
            'user_id'     => $userId,
            'tanggal'     => $tanggalHariIni,
            'jam'         => $jamSekarang,
            'status'      => $request->status,
            'keterangan'  => $request->keterangan ?? '-',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan!'
        ]);
    }

    /**
     * Ambil absensi terakhir (maks 6 hari)
     */
    public function getAllAbsensi(): JsonResponse
    {
        $user = Auth::user();

        $data = DB::table('absensi as a')
            ->select('a.id_absensi', 'a.jam', 'a.tanggal', 'a.status', 'a.keterangan')
            ->where('a.user_id', $user->id_user)
            ->where('a.ponpes_id', $user->ponpes_id)
            ->whereIn('a.id_absensi', function ($query) use ($user) {
                $query->selectRaw('MAX(id_absensi)')
                    ->from('absensi')
                    ->where('user_id', $user->id_user)
                    ->where('ponpes_id', $user->ponpes_id)
                    ->groupBy('tanggal');
            })
            ->orderByDesc('a.tanggal')
            ->limit(6)
            ->get();

        return response()->json(['success' => true, 'data' => $data]);
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
            ->map(fn($item) => $item->pluck('status')->toArray());

        return response()->json($absensi);
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

        // Ambil data ponpes untuk header
        $ponpes = Ponpes::where('id_ponpes', $user->ponpes_id)->first();

        return view('pages.dashboard', compact(
            'jumlahHadir',
            'jumlahIzin',
            'jumlahSakit',
            'jumlahAlpa',
            'kelas',
            'akademikTerbaik',
            'tahfidzTerbaik',
            'ponpes'
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

        return response()->json($result);
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

        return response()->json([
            'already_absened' => $absensiHariIni !== null,
            'status' => $absensiHariIni?->status,
            'keterangan' => $absensiHariIni?->keterangan
        ]);
    }

    /**
     * Riwayat Absensi
     */
    public function riwayat(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUserId = $request->query('user_id');

        /**
         * ========================
         * CASE ADMIN
         * ========================
         */
        if ($currentUser->role === 'Admin') {

            // ADMIN hanya melihat Pengajar & Keuangan dari ponpes sendiri
            $users = DB::table('user as u')
                ->join('ponpes as p', 'u.ponpes_id', '=', 'p.id_ponpes')
                ->select('u.id_user', 'u.username', 'u.role', 'p.nama_ponpes', 'u.email')
                ->where('u.ponpes_id', $currentUser->ponpes_id)   // << PROTEKSI PONPES
                ->whereIn('u.role', ['Pengajar', 'Keuangan'])     // << BATAS ROLE
                ->get();

            // Admin belum memilih user → kosongkan tampilan
            if (!$selectedUserId) {
                return view('pages.riwayat', [
                    'currentUser' => $currentUser,
                    'selectedUser' => null,
                    'users' => $users,
                    'jumlahHadir' => 0,
                    'jumlahIzin' => 0,
                    'jumlahSakit' => 0,
                    'jumlahAlpa' => 0,
                    'absensiPerTanggal' => [],
                    'month' => date('m'),
                    'year' => date('Y')
                ]);
            }

            // Admin memilih user → cek apakah dalam ponpes yang sama
            $selectedUser = DB::table('user')
                ->where('id_user', $selectedUserId)
                ->where('ponpes_id', $currentUser->ponpes_id) // << PROTEKSI
                ->first();

            if (!$selectedUser) {
                abort(403, "Anda tidak memiliki akses.");
            }

            $ponpesId = $selectedUser->ponpes_id;

            // Hitung absensi user yang dipilih
            $counts = DB::table('absensi')
                ->select('status', DB::raw('COUNT(*) as total'))
                ->where('user_id', $selectedUserId)
                ->where('ponpes_id', $ponpesId)
                ->groupBy('status')
                ->pluck('total', 'status');

            $jumlahHadir = $counts['Hadir'] ?? 0;
            $jumlahIzin  = $counts['Izin']  ?? 0;
            $jumlahSakit = $counts['Sakit'] ?? 0;
            $jumlahAlpa  = $counts['Alpa']  ?? 0;

            $month = $request->query('month', date('m'));
            $year  = $request->query('year', date('Y'));

            $absensiPerTanggal = DB::table('absensi')
                ->where('user_id', $selectedUserId)
                ->where('ponpes_id', $ponpesId)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->pluck('status', 'tanggal');

            return view('pages.riwayat', compact(
                'currentUser',
                'users',
                'selectedUser',
                'jumlahHadir',
                'jumlahIzin',
                'jumlahSakit',
                'jumlahAlpa',
                'absensiPerTanggal',
                'month',
                'year'
            ));
        }

        /**
         * ========================
         * CASE PENGAJAR / KEUANGAN
         * ========================
         */
        $selectedUser = $currentUser;
        $userId = $currentUser->id_user;
        $ponpesId = $currentUser->ponpes_id;

        $counts = DB::table('absensi')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->where('ponpes_id', $ponpesId)
            ->groupBy('status')
            ->pluck('total', 'status');

        $jumlahHadir = $counts['Hadir'] ?? 0;
        $jumlahIzin  = $counts['Izin']  ?? 0;
        $jumlahSakit = $counts['Sakit'] ?? 0;
        $jumlahAlpa  = $counts['Alpa'] ?? 0;

        $month = $request->query('month', date('m'));
        $year  = $request->query('year', date('Y'));

        $absensiPerTanggal = DB::table('absensi')
            ->where('user_id', $userId)
            ->where('ponpes_id', $ponpesId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->pluck('status', 'tanggal');

        return view('pages.riwayat', [
            'currentUser' => $currentUser,
            'selectedUser' => $selectedUser,
            'users' => null,
            'jumlahHadir' => $jumlahHadir,
            'jumlahIzin' => $jumlahIzin,
            'jumlahSakit' => $jumlahSakit,
            'jumlahAlpa' => $jumlahAlpa,
            'absensiPerTanggal' => $absensiPerTanggal,
            'month' => $month,
            'year' => $year
        ]);
    }
}
