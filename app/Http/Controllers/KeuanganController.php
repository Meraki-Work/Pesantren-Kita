<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
         public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $filter = $request->get('filter', '1-tahun'); // default 1 tahun

        // ==========================
        // 🔹 QUERY UNTUK CHART (dengan filter)
        // ==========================
        $chartQuery = Keuangan::with(['kategori', 'user']);
        $chartQuery = $this->applyDateFilter($chartQuery, $filter);
        $data = $chartQuery->orderBy('tanggal', 'asc')->get();

        // ==========================
        // 🔹 QUERY UNTUK TABEL (TANPA filter date, TAPI dengan pagination)
        // ==========================
        $tableQuery = Keuangan::with(['kategori', 'user']);

        
        // HAPUS filter date untuk tabel agar semua data muncul
        // $tableQuery = $this->applyDateFilter($tableQuery, $filter); // DIHAPUS
        
        // Pagination untuk tabel - 10 data per halaman
        $tableData = $tableQuery->orderBy('tanggal', 'desc')->paginate(10);

        $columns = ['User', 'Jumlah', 'Kategori', 'Sumber Dana', 'Tanggal', 'Status'];
        $rows = $tableData->map(function ($item) {
            return [
                'id' => $item->id_keuangan,
                'user' => $item->user->username ?? 'Tidak ada user',
                'jumlah' => 'Rp ' . number_format($item->jumlah, 0, ',', '.') . ',00',
                'jumlah_raw' => $item->jumlah,
                'jumlah_raw' => $item->keterangan,
                'kategori' => $item->kategori->nama_kategori ?? 'Tidak ada kategori',
                'id_kategori' => $item->id_kategori,
                'sumber_dana' => $item->sumber_dana ?? '-',
                'tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('d M Y') : '-',
                'tanggal_raw' => $item->tanggal,
                'status' => $item->status ?? '-',
                'keterangan' => $item->keterangan ?? '',
            ];
        })->toArray();

        // ==========================
        // 🔹 Data untuk chart kategori (PIE CHART) - dari data filtered
        // ==========================
        $grouped = $data->groupBy(function ($item) {
            return $item->kategori->nama_kategori ?? 'Tidak ada kategori';
        })->map(function ($items) {
            return [
                'total' => $items->sum('jumlah'),
                'sumber_dana' => $items->pluck('sumber_dana')->unique()->join(', ')
            ];
        });

        $labels = $grouped->keys()->toArray();
        $values = $grouped->pluck('total')->toArray();
        $sumber_dana = $grouped->pluck('sumber_dana')->toArray();

        // ==========================
        // 🔹 Data untuk CASH FLOW CHART (LINE CHART) - dari data filtered
        // ==========================
        $dates = $data->pluck('tanggal')
            ->filter()
            ->map(fn($t) => Carbon::parse($t)->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $dailyFlow = [];
        $total = 0; // Mulai dari saldo 0

        foreach ($dates as $tanggal) {
            $transactions = $data->filter(
                fn($i) =>
                $i->tanggal && Carbon::parse($i->tanggal)->format('Y-m-d') === $tanggal
            );

            $masuk = $transactions->where('status', 'Masuk')->sum('jumlah');
            $keluar = $transactions->where('status', 'Keluar')->sum('jumlah');

            // Akumulasi: saldo_hari_ini = saldo_kemarin + (pemasukan - pengeluaran)
            $total += ($masuk - $keluar);
            $dailyFlow[] = $total;
        }

        $saldo_terakhir = !empty($dailyFlow) ? end($dailyFlow) : 0;

        // Hitung total untuk cards - dari data filtered
        $totalPemasukan = $data->where('status', 'Masuk')->sum('jumlah');
        $totalPengeluaran = $data->where('status', 'Keluar')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

// dd($tableData);

        return view('pages.keuangan', compact(
            'data',
            'columns',
            'rows',
            'labels',
            'values',
            'sumber_dana',
            'dates',
            'dailyFlow',
            'saldo_terakhir',
            'filter',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'tableData' // Kirim pagination object
        ));
    }

    /**
     * Apply date filter to query - HANYA untuk chart
     */
    private function applyDateFilter($query, $filter)
    {
        $now = Carbon::now();

        switch ($filter) {
            case '1-bulan':
                $startDate = $now->copy()->subMonth();
                break;
            case '3-bulan':
                $startDate = $now->copy()->subMonths(3);
                break;
            case '6-bulan':
                $startDate = $now->copy()->subMonths(6);
                break;
            case '1-tahun':
                $startDate = $now->copy()->subYear();
                break;
            case '5-tahun':
                $startDate = $now->copy()->subYears(5);
                break;
            default:
                $startDate = $now->copy()->subYear(); // default 1 tahun
        }

        return $query->where('tanggal', '>=', $startDate->format('Y-m-d'))
            ->where('tanggal', '<=', $now->format('Y-m-d'));
    }

    // ... method lainnya tetap sama
    
    public function create()
    {
        $kategories = Kategori::all();
        return view('pages.keuangan-create', compact('kategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'sumber_dana' => 'required|string|max:100',
            'status' => 'required|in:Masuk,Keluar',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        Keuangan::create([
            'user_id' => auth()->id() ?? 1, // Sesuaikan dengan auth user
            'jumlah' => $request->jumlah,
            'id_kategori' => $request->id_kategori,
            'sumber_dana' => $request->sumber_dana,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $keuangan = Keuangan::with('kategori')->findOrFail($id);
        $kategories = Kategori::all();
        
        return view('pages.keuangan-edit', compact('keuangan', 'kategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'sumber_dana' => 'required|string|max:100',
            'status' => 'required|in:Masuk,Keluar',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $keuangan = Keuangan::findOrFail($id);
        $keuangan->update([
            'jumlah' => $request->jumlah,
            'id_kategori' => $request->id_kategori,
            'sumber_dana' => $request->sumber_dana,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();

        return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil dihapus!');
    }
}