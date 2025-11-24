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
     * Menyimpan absensi hari ini
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;
        $tanggalHariIni = Carbon::now()->toDateString();

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

        DB::table('absensi')->insert([
            'ponpes_id' => $userPonpesId,
            'user_id' => $userId,
            'tanggal' => $tanggalHariIni,
            'status' => $request->status,
            'keterangan' => $request->keterangan ?? '-',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan!'
        ]);
    }

    /**
     * Mendapatkan absensi terakhir per hari (limit 6)
     */
    public function getAllAbsensi(): JsonResponse
    {
        $user = Auth::user();
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;

        $data = DB::table('absensi as a')
            ->select('a.id_absensi', 'a.tanggal', 'a.status', 'a.keterangan')
            ->where('a.user_id', $userId)
            ->where('a.ponpes_id', $userPonpesId)
            ->whereIn('a.id_absensi', function ($query) use ($userId, $userPonpesId) {
                $query->selectRaw('MAX(id_absensi)')
                    ->from('absensi')
                    ->where('user_id', $userId)
                    ->where('ponpes_id', $userPonpesId)
                    ->groupBy('tanggal');
            })
            ->orderByDesc('a.tanggal')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Data absensi untuk kalender
     */
    public function getAbsensi(): JsonResponse
    {
        $user = Auth::user();
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;

        $absensi = DB::table('absensi')
            ->select('tanggal','status')
            ->where('user_id', $userId)
            ->where('ponpes_id', $userPonpesId)
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
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;

        // Hitung jumlah status
        $counts = DB::table('absensi')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->where('ponpes_id', $userPonpesId)
            ->groupBy('status')
            ->pluck('total','status');

        $jumlahHadir = $counts['Hadir'] ?? 0;
        $jumlahIzin = $counts['Izin'] ?? 0;
        $jumlahSakit = $counts['Sakit'] ?? 0;
        $jumlahAlpa = $counts['Alpa'] ?? 0;

        // Ambil kelas
        $kelas = DB::table('kelas')
            ->select('id_kelas','nama_kelas')
            ->where('ponpes_id', $userPonpesId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Akademik terbaik
        $akademikTerbaik = DB::table('pencapaian as p')
            ->join('santri as s','p.id_santri','=','s.id_santri')
            ->select('s.id_santri','s.nama','s.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe','Akademik')
            ->where('p.ponpes_id',$userPonpesId)
            ->where('s.ponpes_id',$userPonpesId)
            ->groupBy('s.id_santri','s.nama','s.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        // Tahfidz terbaik
        $tahfidzTerbaik = DB::table('pencapaian as p')
            ->join('santri as s','p.id_santri','=','s.id_santri')
            ->select('s.id_santri','s.nama','s.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe','Tahfidz')
            ->where('p.ponpes_id',$userPonpesId)
            ->where('s.ponpes_id',$userPonpesId)
            ->groupBy('s.id_santri','s.nama','s.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        return view('pages.dashboard', compact(
            'jumlahHadir','jumlahIzin','jumlahSakit','jumlahAlpa',
            'kelas','akademikTerbaik','tahfidzTerbaik'
        ));
    }

    /**
     * Grafik prestasi
     */
    public function getGrafikPrestasi(Request $request): JsonResponse
    {
        $userPonpesId = Auth::user()->ponpes_id;

        $query = DB::table('pencapaian')
            ->join('santri','pencapaian.id_santri','=','santri.id_santri')
            ->join('kelas','santri.id_kelas','=','kelas.id_kelas')
            ->select('pencapaian.tipe', DB::raw('COUNT(*) as total'))
            ->where('pencapaian.ponpes_id',$userPonpesId)
            ->where('santri.ponpes_id',$userPonpesId)
            ->where('kelas.ponpes_id',$userPonpesId)
            ->groupBy('pencapaian.tipe');

        if ($request->filled('kelas') && $request->kelas !== 'Semua') {
            $query->where('kelas.nama_kelas', $request->kelas);
        }

        $data = $query->pluck('total','tipe');

        $types = ['Akademik','Non-Akademik','Tahfidz','Hafalan','Lainnya'];
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
        $userId = (int)$user->id_user;
        $userPonpesId = $user->ponpes_id;
        $tanggalHariIni = Carbon::now()->toDateString();

        $absensiHariIni = DB::table('absensi')
            ->where('user_id',$userId)
            ->where('ponpes_id',$userPonpesId)
            ->where('tanggal',$tanggalHariIni)
            ->first();

        return response()->json([
            'already_absened' => $absensiHariIni !== null,
            'status' => $absensiHariIni?->status,
            'keterangan' => $absensiHariIni?->keterangan
        ]);
    }
}
