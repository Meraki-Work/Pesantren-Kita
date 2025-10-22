<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PencapaianController extends Controller
{
    /**
     * Menampilkan form edit pencapaian
     */
    public function edit($id)
    {
        $pencapaian = DB::table('pencapaian as p')
            ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
            ->join('kelas as k', 'k.id_kelas', '=', 's.id_kelas')
            ->where('p.id_pencapaian', $id)
            ->select('p.*', 's.nama as nama_santri', 'k.nama_kelas')
            ->first();

        if (!$pencapaian) {
            return redirect()->route('santri.index')->with('error', 'Data pencapaian tidak ditemukan');
        }

        return view('pages.action.pencapaian_edit', compact('pencapaian'));
    }

    /**
     * Update data pencapaian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'skor' => 'required|numeric|min:0|max:100'
        ]);

        // Hapus updated_at dari query update
        $updated = DB::table('pencapaian')
            ->where('id_pencapaian', $id)
            ->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal,
                'skor' => $request->skor
                // HAPUS: 'updated_at' => now()
            ]);

        if ($updated) {
            return redirect()->route('santri.index')->with('success', 'Data pencapaian berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate data pencapaian');
    }
    /**
     * Hapus data pencapaian
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('pencapaian')
                ->where('id_pencapaian', $id)
                ->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'Data pencapaian berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Data pencapaian tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pencapaian: ' . $e->getMessage());
        }
    }
}
