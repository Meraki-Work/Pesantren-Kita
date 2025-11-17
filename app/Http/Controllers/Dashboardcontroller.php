<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Cuti',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id() ?? 1;
        $tanggalHariIni = Carbon::now()->toDateString();

        // ✅ Simpan absensi baru
        DB::table('absensi')->insert([
            'ponpes_id' => 'P001',
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

    public function getAllAbsensi()
    {
        $userId = auth()->id() ?? 1;

        $data = DB::table('absensi as a')
            ->select('a.*')
            ->where('a.user_id', $userId)
            ->whereIn('a.id_absensi', function ($query) use ($userId) {
                $query->selectRaw('MAX(id_absensi)')
                    ->from('absensi')
                    ->where('user_id', $userId)
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

    public function getAbsensi()
    {
        $userId = auth()->id() ?? 1;

        $absensi = DB::table('absensi')
            ->select('tanggal', 'status')
            ->where('user_id', $userId)
            ->get()
            ->groupBy('tanggal')
            ->map(function ($item) {
                return $item->pluck('status');
            });

        return response()->json($absensi);
    }

    public function index()
{
    $userId = auth()->id() ?? 1;

    // Hitung jumlah hadir, cuti, izin
    $jumlahHadir = DB::table('absensi')
        ->where('user_id', $userId)
        ->whereRaw('LOWER(status) = ?', ['hadir'])
        ->count();

    $jumlahCuti = DB::table('absensi')
        ->where('user_id', $userId)
        ->whereRaw('LOWER(status) = ?', ['cuti'])
        ->count();

    $jumlahIzin = DB::table('absensi')
        ->where('user_id', $userId)
        ->whereRaw('LOWER(status) = ?', ['izin'])
        ->count();

    $kelas = DB::table('kelas')
        ->select('id_kelas', 'nama_kelas')
        ->orderBy('tingkat')
        ->orderBy('nama_kelas')
        ->get();

    // 🔹 Akademik Terbaik
    $akademikTerbaik = DB::table('pencapaian as p')
            ->join('santri as s', 'p.id_santri', '=', 's.id_santri')
            ->select('s.id_santri', 's.nama', 's.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe', 'Akademik')
            ->groupBy('s.id_santri', 's.nama', 's.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

    // 🔹 Tahfidz Terbaik
   $tahfidzTerbaik = DB::table('pencapaian as p')
            ->join('santri as s', 'p.id_santri', '=', 's.id_santri')
            ->select('s.id_santri', 's.nama', 's.nisn', DB::raw('COUNT(*) as total'))
            ->where('p.tipe', 'Tahfidz')
            ->groupBy('s.id_santri', 's.nama', 's.nisn')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

    return view('pages.dashboard', compact(
            'jumlahHadir',
            'jumlahIzin',
            'jumlahCuti',
            'kelas',
            'akademikTerbaik',
            'tahfidzTerbaik'
        ));
    }

    public function getGrafikPrestasi(Request $request)
{
    $query = DB::table('pencapaian')
        ->join('santri', 'pencapaian.id_santri', '=', 'santri.id_santri')
        ->join('kelas', 'santri.id_kelas', '=', 'kelas.id_kelas')
        ->select('pencapaian.tipe', DB::raw('COUNT(*) as total'))
        ->groupBy('pencapaian.tipe');

    if ($request->has('kelas') && $request->kelas !== 'Semua') {
        $query->where('kelas.nama_kelas', $request->kelas);
    }

    $data = $query->pluck('total', 'tipe');

    $types = ['Akademik', 'Non Akademik', 'Tahfidz', 'Hafalan', 'Lainnya'];
    $result = [];
    foreach ($types as $type) {
        $result[$type] = $data[$type] ?? 0;
    }

    return response()->json($result);
}
    
}
