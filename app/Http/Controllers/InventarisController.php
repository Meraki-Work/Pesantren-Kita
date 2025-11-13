<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan filter
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $kondisi = $request->get('kondisi');

        // Query dasar
        $query = Inventaris::query();

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Apply kategori filter
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Apply kondisi filter
        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        // Data untuk chart (ringkasan per kategori)
        $chartData = Inventaris::select('kategori', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('kategori')
            ->get();

        // Data untuk tabel dengan pagination
        $inventaris = $query->orderBy('created_at', 'desc')->paginate(10);

        // Data untuk filter dropdown
        $kategories = Inventaris::distinct()->pluck('kategori')->filter();
        $kondisis = ['Baik', 'Rusak', 'Hilang'];

        return view('pages.inventaris', compact(
            'inventaris', 
            'chartData',
            'kategories',
            'kondisis',
            'search',
            'kategori',
            'kondisi'
        ));
    }

    public function create()
    {
        $kondisis = ['Baik', 'Rusak', 'Hilang'];
        $kategories = Inventaris::distinct()->pluck('kategori')->filter();
        
        return view('pages.action.create_inventaris', compact('kondisis', 'kategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Rusak,Hilang',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:100',
            'tanggal_beli' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'ponpes_id' => 'nullable|string|max:64'
        ]);

        Inventaris::create([
            'ponpes_id' => $request->ponpes_id ?? auth()->user()->ponpes_id ?? null,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'kondisi' => $request->kondisi,
            'jumlah' => $request->jumlah,
            'lokasi' => $request->lokasi,
            'tanggal_beli' => $request->tanggal_beli,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('inventaris.index')
            ->with('success', 'Data inventaris berhasil ditambahkan!');
    }

    public function show($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        
        return view('pages.inventaris-show', compact('inventaris'));
    }

    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $kondisis = ['Baik', 'Rusak', 'Hilang'];
        $kategories = Inventaris::distinct()->pluck('kategori')->filter();
        
        return view('pages.inventaris-edit', compact('inventaris', 'kondisis', 'kategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Rusak,Hilang',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:100',
            'tanggal_beli' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'ponpes_id' => 'nullable|string|max:64'
        ]);

        $inventaris = Inventaris::findOrFail($id);
        $inventaris->update([
            'ponpes_id' => $request->ponpes_id ?? $inventaris->ponpes_id,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'kondisi' => $request->kondisi,
            'jumlah' => $request->jumlah,
            'lokasi' => $request->lokasi,
            'tanggal_beli' => $request->tanggal_beli,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('inventaris.index')
            ->with('success', 'Data inventaris berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();

        return redirect()->route('inventaris.index')
            ->with('success', 'Data inventaris berhasil dihapus!');
    }

    // API Endpoint untuk data chart
    public function getChartData()
    {
        $data = Inventaris::select('kategori', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('kategori')
            ->get();

        return response()->json($data);
    }

    // Export data inventaris
    public function export(Request $request)
    {
        $inventaris = Inventaris::all();
        
        // Logic untuk export Excel/PDF
        // Bisa menggunakan Laravel Excel atau PDF libraries
        
        return response()->json(['message' => 'Export feature will be implemented']);
    }

    // Quick stats untuk dashboard
    public function getStats()
    {
        $totalBarang = Inventaris::sum('jumlah');
        $totalKategori = Inventaris::distinct('kategori')->count('kategori');
        $barangBaik = Inventaris::where('kondisi', 'Baik')->sum('jumlah');
        $barangRusak = Inventaris::where('kondisi', 'Rusak')->sum('jumlah');
        
        return response()->json([
            'total_barang' => $totalBarang,
            'total_kategori' => $totalKategori,
            'barang_baik' => $barangBaik,
            'barang_rusak' => $barangRusak
        ]);
    }
}