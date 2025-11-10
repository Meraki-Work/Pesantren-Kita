<?php

namespace App\Http\Controllers;

use App\Models\Notulen;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotulenController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan filter
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');
        $pimpinan = $request->get('pimpinan');

        // Query dasar dengan join gambar
        $query = Notulen::with(['user', 'gambar']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agenda', 'like', "%{$search}%")
                    ->orWhere('peserta', 'like', "%{$search}%")
                    ->orWhere('tempat', 'like', "%{$search}%")
                    ->orWhere('hasil', 'like', "%{$search}%");
            });
        }

        // Apply tanggal filter
        if ($tanggal) {
            $query->where('tanggal', $tanggal);
        }

        // Apply pimpinan filter
        if ($pimpinan) {
            $query->where('pimpinan', 'like', "%{$pimpinan}%");
        }

        // Data untuk tabel dengan pagination
        $notulen = $query->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(10);

        // Data untuk filter dropdown
        $pimpinans = Notulen::distinct()->pluck('pimpinan')->filter();
        $tanggalOptions = Notulen::distinct()->pluck('tanggal')->sortDesc();

        $recentGambar = \App\Models\Gambar::with('notulen')
            ->whereNotNull('id_notulen') // Hanya gambar yang punya relasi notulen
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();


        // Data untuk statistik
        $totalGambar = \App\Models\Gambar::whereNotNull('id_notulen')->count();
        $rapatBulanIni = \App\Models\Notulen::whereMonth('tanggal', now()->month)->count();
        $topPimpinan = \App\Models\Notulen::select('pimpinan')
            ->groupBy('pimpinan')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $recentlyGambar = \App\Models\Gambar::with(['notulen' => function ($query) {
            $query->select('id_notulen', 'agenda');
        }])
            ->whereNotNull('id_notulen')
            ->whereNotNull('path_gambar') // Hanya yang punya gambar
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Data untuk statistik
        $totalGambar = \App\Models\Gambar::whereNotNull('id_notulen')->whereNotNull('path_gambar')->count();
        $rapatBulanIni = \App\Models\Notulen::whereMonth('tanggal', now()->month)->count();
        $topPimpinan = \App\Models\Notulen::select('pimpinan')
            ->groupBy('pimpinan')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        // Recent activities
        $recentActivities = \App\Models\Notulen::with('user')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('pages.notulensi', compact(
            'notulen',
            'pimpinans',
            'tanggalOptions',
            'search',
            'tanggal',
            'pimpinan',
            'recentGambar',
            'recentlyGambar',
            'totalGambar',
            'rapatBulanIni',
            'topPimpinan',
            'recentActivities'
        ));
    }

    public function create()
    {
        return view('pages.action.create_notulensi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'agenda' => 'required|string|max:255',
            'pimpinan' => 'required|string|max:100',
            'peserta' => 'required|array|min:1',
            'peserta.*' => 'required|string',
            'tempat' => 'required|string|max:150',
            'alur_rapat' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'keterangan' => 'nullable|string',
            'hasil' => 'required|array|min:1',
            'hasil.*' => 'required|string',
            'ponpes_id' => 'nullable|string|max:64',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Convert array to string for database storage
        $pesertaString = implode(', ', array_filter($request->peserta));
        $hasilString = implode("\n", array_filter($request->hasil));

        // Create notulen
        $notulen = Notulen::create([
            'ponpes_id' => $request->ponpes_id ?? auth()->user()->ponpes_id ?? null,
            'user_id' => auth()->id(),
            'agenda' => $request->agenda,
            'pimpinan' => $request->pimpinan,
            'peserta' => $pesertaString,
            'tempat' => $request->tempat,
            'alur_rapat' => $request->alur_rapat,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keterangan' => $request->keterangan,
            'hasil' => $hasilString
        ]);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                try {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Store file to storage
                    $path = $file->storeAs('notulensi', $filename, 'public');

                    // DEBUG: Log storage info
                    \Log::info('File stored:', [
                        'storage_path' => storage_path('app/public/' . $path),
                        'public_path' => public_path('storage/' . $path),
                        'storage_exists' => file_exists(storage_path('app/public/' . $path)),
                        'public_exists' => file_exists(public_path('storage/' . $path)),
                        'asset_url' => asset('storage/' . $path)
                    ]);

                    // Create gambar record
                    Gambar::create([
                        'id_notulen' => $notulen->id_notulen,
                        'user_id' => auth()->id(),
                        'path_gambar' => $path,
                        'keterangan' => 'Dokumentasi rapat: ' . $request->agenda
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error storing image: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('notulen.index')
            ->with('success', 'Notulen rapat berhasil disimpan!');
    }


    public function show($id)
    {
        $notulen = Notulen::with(['user', 'gambar'])->findOrFail($id);

        return view('pages.action.show_notulensi', compact('notulen'));
    }

    public function edit($id)
    {
        $notulen = Notulen::with('gambar')->findOrFail($id);

        // Authorization check - hanya pembuat yang bisa edit
        if ($notulen->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit notulen ini.');
        }

        return view('pages.action.edit_notulensi', compact('notulen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'agenda' => 'required|string|max:255',
            'pimpinan' => 'required|string|max:100',
            'peserta' => 'required|string',
            'tempat' => 'required|string|max:150',
            'alur_rapat' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'keterangan' => 'nullable|string',
            'hasil' => 'required|string',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hapus_gambar' => 'nullable|array',
            'hapus_gambar.*' => 'integer'
        ]);

        $notulen = Notulen::findOrFail($id);

        // Authorization check
        if ($notulen->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit notulen ini.');
        }

        $notulen->update([
            'agenda' => $request->agenda,
            'pimpinan' => $request->pimpinan,
            'peserta' => $request->peserta,
            'tempat' => $request->tempat,
            'alur_rapat' => $request->alur_rapat,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keterangan' => $request->keterangan,
            'hasil' => $request->hasil
        ]);

        // Handle hapus gambar
        if ($request->has('hapus_gambar')) {
            foreach ($request->hapus_gambar as $gambarId) {
                $gambar = Gambar::where('id_gambar', $gambarId)
                    ->where('id_notulen', $id)
                    ->first();

                if ($gambar) {
                    // Hapus file dari storage
                    Storage::disk('public')->delete($gambar->path);
                    // Hapus record dari database
                    $gambar->delete();
                }
            }
        }

        // Handle tambah gambar baru
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('notulensi', $filename, 'public');

                Gambar::create([
                    'id_notulen' => $notulen->id_notulen,
                    'user_id' => auth()->id(),
                    'path_gambar' => $path,
                    'keterangan' => 'Dokumentasi rapat: ' . $request->agenda
                ]);
            }
        }

        return redirect()->route('notulen.index')
            ->with('success', 'Notulen rapat berhasil diperbarui!');
    }

    public function destroy($id)
{
    $notulen = Notulen::with('gambar')->findOrFail($id);

    // Authorization check - hanya pembuat yang bisa hapus
    if ($notulen->user_id !== auth()->id()) {
        abort(403, 'Anda tidak memiliki akses untuk menghapus notulen ini.');
    }

    // Hapus semua gambar terkait - PERBAIKAN: cek path_gambar tidak null
    foreach ($notulen->gambar as $gambar) {
        if ($gambar->path_gambar) {
            Storage::disk('public')->delete($gambar->path_gambar);
        }
        $gambar->delete();
    }

    $notulen->delete();

    return redirect()->route('notulen.index')
        ->with('success', 'Notulen rapat berhasil dihapus!');
}

    // Method untuk menghapus gambar individual
    public function hapusGambar($id)
{
    $gambar = Gambar::findOrFail($id);

    // Authorization check - hanya pemilik gambar yang bisa hapus
    if ($gambar->user_id !== auth()->id()) {
        abort(403, 'Anda tidak memiliki akses untuk menghapus gambar ini.');
    }

    // Hapus file dari storage - PERBAIKAN: cek path_gambar tidak null
    if ($gambar->path_gambar) {
        Storage::disk('public')->delete($gambar->path_gambar);
    }

    // Hapus record dari database
    $gambar->delete();

    return response()->json([
        'success' => true,
        'message' => 'Gambar berhasil dihapus!'
    ]);
}

    // Method untuk menambah gambar ke notulen yang sudah ada
    public function tambahGambar(Request $request, $id)
    {
        $request->validate([
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $notulen = Notulen::findOrFail($id);

        // Authorization check
        if ($notulen->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah gambar.');
        }

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('notulensi', $filename, 'public');

                Gambar::create([
                    'id_notulen' => $notulen->id_notulen,
                    'user_id' => auth()->id(),
                    'nama_file' => $filename,
                    'path' => $path,
                    'jenis' => 'notulensi',
                    'deskripsi' => 'Gambar untuk notulensi: ' . $notulen->agenda
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Gambar berhasil ditambahkan!');
    }

    // Quick stats untuk dashboard
    public function getStats()
    {
        $totalNotulen = Notulen::count();
        $notulenBulanIni = Notulen::whereMonth('tanggal', now()->month)->count();
        $notulenMingguIni = Notulen::whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return response()->json([
            'total_notulen' => $totalNotulen,
            'notulen_bulan_ini' => $notulenBulanIni,
            'notulen_minggu_ini' => $notulenMingguIni
        ]);
    }

    // Export notulen
    public function export($id)
    {
        $notulen = Notulen::with(['user', 'gambar'])->findOrFail($id);

        // Logic untuk export PDF
        // Bisa menggunakan DomPDF atau library lainnya

        return response()->json([
            'message' => 'Export PDF feature will be implemented',
            'data' => $notulen
        ]);
    }
}
