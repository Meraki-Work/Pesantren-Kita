<?php

namespace App\Http\Controllers;

use App\Models\Pencapaian;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kela;

class KompetensiController extends Controller
{
    public function index()
    {
        // Ambil semua pencapaian
        $pencapaian = Pencapaian::orderBy('tanggal', 'desc')->get();

        // Ambil semua kelas untuk dropdown
        $kelas = Kela::all();

        $santri = Santri::with('kela')->select('id_santri', 'nama', 'id_kelas')->get();

        // Kirim semua variabel ke view
        $selectedKelas = $santri->first()?->id_kelas ?? null; 
    $selectedKelasNama = $santri->first()?->kela->nama_kelas ?? '-- Pilih Kelas --';

    return view('pages.create_kompetensi', compact('santri', 'kelas', 'pencapaian', 'selectedKelas', 'selectedKelasNama'));
}


    public function store(Request $request)
{
    try {
        $santri = Santri::find($request->id_santri);
        $ponpes_id = $santri->ponpes_id;

        $request->validate([
            'id_santri'  => 'required|integer',
            'judul'      => 'required|string|max:150',
            'deskripsi'  => 'nullable|string',
            'tipe'       => 'required|in:Akademik,Non-Akademik,Tahfidz,Lainnya',
            'skor'       => 'nullable|integer',
            'tanggal'    => 'required|date',
        ]);

        DB::insert(
            'INSERT INTO pencapaian (ponpes_id, id_santri, user_id, judul, deskripsi, tipe, skor, tanggal)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$ponpes_id, $request->id_santri, null, $request->judul, $request->deskripsi, $request->tipe, $request->skor, $request->tanggal]
        );

        return redirect()->route('kompetensi.index')
            ->with('success', 'Pencapaian berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
