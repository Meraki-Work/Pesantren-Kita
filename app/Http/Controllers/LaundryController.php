<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Santri;
use App\Models\Kategori;

class LaundryController extends Controller
{
    public function index()
    {
        // Ambil ID kategori laundry (asumsi nama kategori 'Laundry')
        $kategoriLaundry = DB::table('kategori')
            ->where('nama_kategori', 'like', '%laundry%')
            ->orWhere('nama_kategori', 'like', '%Laundry%')
            ->first();

        // Ambil data keuangan/laundry dengan relasi dan filter kategori laundry
        $keuangan = DB::table('keuangan as k')
            ->leftJoin('santri as s', 'k.id_santri', '=', 's.id_santri')
            ->leftJoin('kategori as kat', 'k.id_kategori', '=', 'kat.id_kategori')
            ->select(
                'k.id_keuangan',
                'k.id_santri',
                's.nama as nama_santri',
                'kat.nama_kategori',
                'k.sumber_dana',
                'k.keterangan',
                'k.jumlah',
                'k.status',
                'k.tanggal'
            )
            ->when($kategoriLaundry, function ($query) use ($kategoriLaundry) {
                return $query->where('k.id_kategori', $kategoriLaundry->id_kategori);
            })
            ->orderBy('k.tanggal', 'desc')
            ->get();

        // Ambil data santri untuk dropdown
        $santri = Santri::select('id_santri', 'nama')->get();

        // Ambil data kategori untuk dropdown (hanya kategori laundry)
        $kategori = Kategori::where('nama_kategori', 'like', '%laundry%')
            ->orWhere('nama_kategori', 'like', '%Laundry%')
            ->select('id_kategori', 'nama_kategori')
            ->get();

        return view('pages.laundry', compact('keuangan', 'santri', 'kategori'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_santri' => 'required|integer|exists:santri,id_santri',
                'id_kategori' => 'required|integer|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
                'jumlah' => 'required|numeric|min:0',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date'
            ]);

            // Insert data keuangan
            DB::table('keuangan')->insert([
                'id_santri' => $request->id_santri,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'ponpes_id' => null, // Sesuaikan dengan kebutuhan
                'user_id' => auth()->id() ?? null, // Jika menggunakan auth
            ]);

            return redirect()->route('laundry.index')
                ->with('success', 'Data keuangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $keuangan = DB::table('keuangan')->where('id_keuangan', $id)->first();

        if (!$keuangan) {
            return redirect()->route('laundry.index')
                ->with('error', 'Data keuangan tidak ditemukan!');
        }

        $santri = Santri::select('id_santri', 'nama')->get();
        $kategori = Kategori::select('id_kategori', 'nama_kategori')->get();

        return view('pages.action.laundry_Fedit', compact('keuangan', 'santri', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'id_santri' => 'required|integer|exists:santri,id_santri',
                'id_kategori' => 'required|integer|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'keterangan' => 'nullable|string',
                'jumlah' => 'required|numeric|min:0',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date'
            ]);

            $updated = DB::table('keuangan')
                ->where('id_keuangan', $id)
                ->update([
                    'id_santri' => $request->id_santri,
                    'id_kategori' => $request->id_kategori,
                    'sumber_dana' => $request->sumber_dana,
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'status' => $request->status,
                    'tanggal' => $request->tanggal,
                ]);

            if ($updated) {
                return redirect()->route('laundry.index')
                    ->with('success', 'Data keuangan berhasil diupdate!');
            }

            return redirect()->back()
                ->with('error', 'Gagal mengupdate data keuangan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('keuangan')->where('id_keuangan', $id)->delete();

            return redirect()->route('laundry.index')
                ->with('success', 'Data keuangan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk contoh insert data seperti query yang diminta
    public function insertExampleData()
    {
        try {
            DB::table('keuangan')->insert([
                'id_santri' => 1,
                'id_kategori' => 2,
                'sumber_dana' => 'SPP Bulanan',
                'keterangan' => 'Pembayaran SPP bulan Oktober',
                'jumlah' => 150000.00,
                'status' => 'Masuk',
                'tanggal' => '2025-10-20',
                'ponpes_id' => null,
                'user_id' => null,
            ]);

            return "Data contoh berhasil ditambahkan!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Method untuk mendapatkan statistik keuangan
    public function getStatistics()
    {
        $totalPemasukan = DB::table('keuangan')
            ->where('status', 'Masuk')
            ->sum('jumlah');

        $totalPengeluaran = DB::table('keuangan')
            ->where('status', 'Keluar')
            ->sum('jumlah');

        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];
    }
}
