<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InventarisController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if inventaris belongs to user's ponpes
     */
    private function checkInventarisOwnership($inventarisId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $inventaris = Inventaris::where('id_inventaris', $inventarisId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$inventaris) {
            Log::warning('Akses inventaris ditolak', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'inventaris_id' => $inventarisId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $inventaris;
    }

    public function index(Request $request)
    {
        try {
            Log::info('Mengakses halaman inventaris index', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'search' => $request->get('search'),
                'kategori' => $request->get('kategori'),
                'kondisi' => $request->get('kondisi')
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil parameter pencarian dan filter
            $search = $request->get('search');
            $kategori = $request->get('kategori');
            $kondisi = $request->get('kondisi');

            // Query dasar dengan filter ponpes_id
            $query = Inventaris::where('ponpes_id', $userPonpesId);

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
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

            // Data untuk chart (ringkasan per kategori) - hanya data ponpes user
            $chartData = Inventaris::where('ponpes_id', $userPonpesId)
                ->select('kategori', DB::raw('SUM(jumlah) as total_jumlah'))
                ->groupBy('kategori')
                ->get();

            // Data untuk tabel dengan pagination
            $inventaris = $query->orderBy('created_at', 'desc')->paginate(10);

            // Data untuk filter dropdown - hanya data ponpes user
            $kategories = Inventaris::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('kategori')
                ->filter();

            $kondisis = ['Baik', 'Rusak', 'Hilang'];

            Log::info('Berhasil memuat data inventaris', [
                'user_id' => Auth::id(),
                'total_data' => $inventaris->total(),
                'current_page' => $inventaris->currentPage()
            ]);

            return view('pages.inventaris', compact(
                'inventaris',
                'chartData',
                'kategories',
                'kondisis',
                'search',
                'kategori',
                'kondisi'
            ));
        } catch (\Exception $e) {
            Log::error('Error pada inventaris index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data inventaris.');
        }
    }

    public function create()
    {
        try {
            Log::info('Mengakses form create inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $kondisis = ['Baik', 'Rusak', 'Hilang'];
            $kategories = Inventaris::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('kategori')
                ->filter();

            Log::debug('Data untuk form create', [
                'kondisis_count' => count($kondisis),
                'kategories_count' => $kategories->count(),
                'view_path' => 'pages.action.create_inventaris'
            ]);

            // PERBAIKI PATH VIEW DI SINI
            return view('pages.action.create_inventaris', compact('kondisis', 'kategories'));
        } catch (\Exception $e) {
            Log::error('Error pada inventaris create', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memproses store inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $request->validate([
                'nama_barang' => 'required|string|max:100',
                'kategori' => 'required|string|max:50',
                'kondisi' => 'required|in:Baik,Rusak,Hilang',
                'jumlah' => 'required|integer|min:1',
                'lokasi' => 'required|string|max:100',
                'tanggal_beli' => 'nullable|date',
                'keterangan' => 'nullable|string|max:500'
            ], [
                'jumlah.min' => 'Jumlah tidak boleh kurang dari 1',
                'kondisi.in' => 'Kondisi harus Baik, Rusak, atau Hilang'
            ]);

            $inventaris = Inventaris::create([
                'ponpes_id' => $userPonpesId,
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'kondisi' => $request->kondisi,
                'jumlah' => $request->jumlah,
                'lokasi' => $request->lokasi,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan
            ]);

            Log::info('Berhasil membuat inventaris baru', [
                'user_id' => Auth::id(),
                'inventaris_id' => $inventaris->id_inventaris,
                'nama_barang' => $inventaris->nama_barang
            ]);

            return redirect()->route('inventaris.index')
                ->with('success', 'Data inventaris berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada store inventaris', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada inventaris store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah data inventaris.')->withInput();
        }
    }

    public function show($id)
    {
        try {
            Log::info('Mengakses detail inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);

            $inventaris = $this->checkInventarisOwnership($id);

            Log::debug('Data inventaris ditemukan', [
                'inventaris_id' => $inventaris->id_inventaris,
                'nama_barang' => $inventaris->nama_barang
            ]);

            return view('pages.inventaris-show', compact('inventaris'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk show inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('inventaris.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Inventaris tidak ditemukan', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Data inventaris tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada inventaris show', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Terjadi kesalahan saat memuat data inventaris.');
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $inventaris = $this->checkInventarisOwnership($id);
            $kondisis = ['Baik', 'Rusak', 'Hilang'];
            $kategories = Inventaris::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('kategori')
                ->filter();

            Log::debug('Data untuk form edit', [
                'inventaris_id' => $inventaris->id_inventaris,
                'kondisis_count' => count($kondisis),
                'kategories_count' => $kategories->count()
            ]);

            return view('pages.action.edit_inventaris', compact('inventaris', 'kondisis', 'kategories'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('inventaris.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Inventaris tidak ditemukan untuk edit', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Data inventaris tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada inventaris edit', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();
            $inventaris = $this->checkInventarisOwnership($id);

            $request->validate([
                'nama_barang' => 'required|string|max:100',
                'kategori' => 'required|string|max:50',
                'kondisi' => 'required|in:Baik,Rusak,Hilang',
                'jumlah' => 'required|integer|min:1',
                'lokasi' => 'required|string|max:100',
                'tanggal_beli' => 'nullable|date',
                'keterangan' => 'nullable|string|max:500'
            ], [
                'jumlah.min' => 'Jumlah tidak boleh kurang dari 1',
                'kondisi.in' => 'Kondisi harus Baik, Rusak, atau Hilang'
            ]);

            $inventaris->update([
                'nama_barang' => $request->nama_barang,
                'kategori' => $request->kategori,
                'kondisi' => $request->kondisi,
                'jumlah' => $request->jumlah,
                'lokasi' => $request->lokasi,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan
            ]);

            Log::info('Berhasil update inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $inventaris->id_inventaris,
                'nama_barang' => $inventaris->nama_barang
            ]);

            return redirect()->route('inventaris.index')
                ->with('success', 'Data inventaris berhasil diperbarui!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('inventaris.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada update inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::warning('Inventaris tidak ditemukan untuk update', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Data inventaris tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada inventaris update', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data inventaris.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Memproses delete inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);

            $inventaris = $this->checkInventarisOwnership($id);

            $inventarisData = [
                'id_inventaris' => $inventaris->id_inventaris,
                'nama_barang' => $inventaris->nama_barang,
                'kategori' => $inventaris->kategori
            ];

            $inventaris->delete();

            Log::info('Berhasil delete inventaris', [
                'user_id' => Auth::id(),
                'deleted_inventaris' => $inventarisData
            ]);

            return redirect()->route('inventaris.index')
                ->with('success', 'Data inventaris berhasil dihapus!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete inventaris', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('inventaris.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Inventaris tidak ditemukan untuk delete', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Data inventaris tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada inventaris delete', [
                'user_id' => Auth::id(),
                'inventaris_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('inventaris.index')->with('error', 'Terjadi kesalahan saat menghapus data inventaris.');
        }
    }

    // API Endpoint untuk data chart
    public function getChartData()
    {
        try {
            Log::debug('Mengambil data chart inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $data = Inventaris::where('ponpes_id', $userPonpesId)
                ->select('kategori', DB::raw('SUM(jumlah) as total_jumlah'))
                ->groupBy('kategori')
                ->get();

            Log::debug('Data chart berhasil diambil', [
                'user_id' => Auth::id(),
                'data_count' => $data->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada getChartData inventaris', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data chart'
            ], 500);
        }
    }

    // Quick stats untuk dashboard
    public function getStats()
    {
        try {
            Log::debug('Mengambil stats inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $totalBarang = Inventaris::where('ponpes_id', $userPonpesId)->sum('jumlah');
            $totalKategori = Inventaris::where('ponpes_id', $userPonpesId)->distinct('kategori')->count('kategori');
            $barangBaik = Inventaris::where('ponpes_id', $userPonpesId)->where('kondisi', 'Baik')->sum('jumlah');
            $barangRusak = Inventaris::where('ponpes_id', $userPonpesId)->where('kondisi', 'Rusak')->sum('jumlah');

            Log::debug('Stats inventaris berhasil diambil', [
                'user_id' => Auth::id(),
                'total_barang' => $totalBarang,
                'total_kategori' => $totalKategori,
                'barang_baik' => $barangBaik,
                'barang_rusak' => $barangRusak
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_barang' => $totalBarang,
                    'total_kategori' => $totalKategori,
                    'barang_baik' => $barangBaik,
                    'barang_rusak' => $barangRusak
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada getStats inventaris', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik'
            ], 500);
        }
    }

    // Export data inventaris (placeholder)
    public function export(Request $request)
    {
        try {
            Log::info('Memproses export inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $inventaris = Inventaris::where('ponpes_id', $userPonpesId)->get();

            Log::info('Export inventaris berhasil', [
                'user_id' => Auth::id(),
                'data_count' => $inventaris->count()
            ]);

            // Logic untuk export Excel/PDF bisa ditambahkan di sini
            // Bisa menggunakan Laravel Excel atau PDF libraries

            return response()->json([
                'success' => true,
                'message' => 'Export feature will be implemented',
                'data_count' => $inventaris->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada export inventaris', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat export data'
            ], 500);
        }
    }

    // Get available categories for dropdown
    public function getCategories()
    {
        try {
            Log::debug('Mengambil kategori inventaris', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $categories = Inventaris::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('kategori')
                ->filter()
                ->values();

            Log::debug('Kategori inventaris berhasil diambil', [
                'user_id' => Auth::id(),
                'categories_count' => $categories->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada getCategories inventaris', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil kategori'
            ], 500);
        }
    }
}