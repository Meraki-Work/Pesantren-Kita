<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;

class KeuanganController extends Controller
{
    public function index()
    {
        // Ambil data beserta relasi kategori dan user
        $data = Keuangan::with(['kategori', 'user'])->get();

        // Buat header kolom dan isi tabel
        $columns = ['User', 'Jumlah', 'Kategori', 'Sumber Dana', 'Tanggal'];
        $rows = $data->map(function ($item) {
            return [
                // $item->id_keuangan,
                $item->user->username ?? '-', // ambil username dari relasi user
                'Rp ' . number_format($item->jumlah, 0, ',', '.') . ',00',
                $item->kategori->nama_kategori ?? '-',
                $item->sumber_dana ?? '-',
                $item->tanggal ?? '-',
            ];
        })->toArray();

        // Data untuk chart
        $labels = $data->pluck('kategori.nama_kategori')->toArray();
        $values = $data->pluck('jumlah')->toArray();
        $dates  = $data->pluck('tanggal')->toArray();

        // Return ke view
        return view('pages.keuangan', compact('data', 'columns', 'rows', 'labels', 'values', 'dates'));
    }
}
