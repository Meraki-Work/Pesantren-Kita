<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Keuangan;

class CashController extends Controller
{
    public function index()
    {
        $data = Keuangan::with(['kategori', 'user'])
            ->orderBy('tanggal')
            ->get();

        if ($data->isEmpty()) {
            dd('Data Keuangan kosong!');
        }

        // Format tanggal unik
        $dates = $data->pluck('tanggal')
            ->filter()
            ->map(fn($t) => Carbon::parse($t)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // Hitung cash flow kumulatif
        $dailyFlow = [];
        $total = 0;

        foreach ($dates as $tanggal) {
            $transactions = $data->filter(
                fn($i) =>
                Carbon::parse($i->tanggal)->format('Y-m-d') === $tanggal
            );

            $masuk = $transactions->where('status', 'Masuk')->sum('jumlah');
            $keluar = $transactions->where('status', 'Keluar')->sum('jumlah');

            $total += ($masuk - $keluar);
            $dailyFlow[] = $total;
        }

        // Debug isi variabel
        dd([
            'dates' => $dates,
            'dailyFlow' => $dailyFlow,
            'data_count' => $data->count(),
        ]);

        // Setelah dd() dihapus
        return view('pages.keuangan', [
            'dates' => $dates,
            'dailyFlow' => $dailyFlow,
            'labels' => $dates, // supaya chart tidak error
            'columns' => [],    // kosongkan dulu kalau belum dipakai
            'rows' => [],       // kosongkan juga
        
        ]);
    }
}
