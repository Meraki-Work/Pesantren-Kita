<?php

namespace App\Http\Controllers;

use App\Models\Notulen;
use App\Models\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class NotulenController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * 🔥 FIXED: Get authenticated user ID yang benar
     * Auth::id() mengembalikan email, kita perlu id_user
     */
    private function getAuthUserId()
    {
        $user = Auth::user();
        
        // Coba berbagai kemungkinan kolom ID
        $userId = $user->id_user ?? $user->id ?? null;
        
        // Jika masih null, coba ambil dari Auth::id() sebagai fallback
        if (!$userId && Auth::id()) {
            // Coba parse jika Auth::id() adalah string yang bisa di-convert ke int
            if (is_numeric(Auth::id())) {
                $userId = (int) Auth::id();
            }
        }
        
        return $userId;
    }

    /**
     * 🔥 FIXED: Check if notulen belongs to user's ponpes
     */
    private function checkNotulenOwnership($notulenId)
    {
        $userPonpesId = $this->getUserPonpesId();
        $authUserId = $this->getAuthUserId();

        $notulen = Notulen::where('id_notulen', $notulenId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$notulen) {
            Log::warning('Akses notulen ditolak - Data tidak ditemukan', [
                'auth_user_id' => $authUserId,
                'ponpes_id' => $userPonpesId,
                'notulen_id' => $notulenId
            ]);
            abort(403, 'Data tidak ditemukan atau akses ditolak.');
        }

        return $notulen;
    }

    /**
     * 🔥 FIXED: Check if user can modify notulen
     */
    private function checkNotulenPermission($notulen)
    {
        $authUserId = $this->getAuthUserId();
        
        // Debug untuk melihat perbandingan
        $isOwner = $notulen->user_id == $authUserId;
        
        if (!$isOwner) {
            Log::warning('User tidak diizinkan mengakses notulen', [
                'notulen_id' => $notulen->id_notulen,
                'db_user_id' => $notulen->user_id,
                'type_db' => gettype($notulen->user_id),
                'auth_user_id' => $authUserId,
                'type_auth' => gettype($authUserId),
                'is_equal' => $isOwner,
                'is_identical' => $notulen->user_id === $authUserId
            ]);
            return false;
        }
        
        return true;
    }

    public function index(Request $request)
    {
        try {
            Log::info('Mengakses halaman notulen index', [
                'user_id' => $this->getAuthUserId(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Query dasar dengan proteksi ponpes_id
            $query = Notulen::with(['user', 'gambar'])
                ->where('ponpes_id', $userPonpesId);

            // Apply search filter
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('agenda', 'like', "%{$search}%")
                        ->orWhere('peserta', 'like', "%{$search}%")
                        ->orWhere('tempat', 'like', "%{$search}%")
                        ->orWhere('hasil', 'like', "%{$search}%")
                        ->orWhere('pimpinan', 'like', "%{$search}%");
                });
            }

            // Apply tanggal filter
            if ($request->has('tanggal') && $request->tanggal != '') {
                $query->where('tanggal', $request->tanggal);
            }

            // Apply pimpinan filter
            if ($request->has('pimpinan') && $request->pimpinan != '') {
                $query->where('pimpinan', 'like', "%{$request->pimpinan}%");
            }

            // Data untuk tabel dengan pagination
            $notulen = $query->orderBy('tanggal', 'desc')
                ->orderBy('waktu', 'desc')
                ->paginate(10);

            // Data untuk filter dropdown (hanya data ponpes user)
            $pimpinans = Notulen::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('pimpinan')
                ->filter();

            $tanggalOptions = Notulen::where('ponpes_id', $userPonpesId)
                ->distinct()
                ->pluck('tanggal')
                ->sortDesc();

            // Recent gambar dengan proteksi ponpes_id
            $recentGambar = Gambar::with(['notulen' => function($query) use ($userPonpesId) {
                    $query->where('ponpes_id', $userPonpesId);
                }])
                ->whereHas('notulen', function($query) use ($userPonpesId) {
                    $query->where('ponpes_id', $userPonpesId);
                })
                ->whereNotNull('id_notulen')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            // Data untuk statistik (hanya data ponpes user)
            $totalGambar = Gambar::whereHas('notulen', function($query) use ($userPonpesId) {
                    $query->where('ponpes_id', $userPonpesId);
                })
                ->whereNotNull('id_notulen')
                ->count();

            $rapatBulanIni = Notulen::where('ponpes_id', $userPonpesId)
                ->whereMonth('tanggal', now()->month)
                ->count();

            $topPimpinan = Notulen::where('ponpes_id', $userPonpesId)
                ->select('pimpinan')
                ->groupBy('pimpinan')
                ->orderByRaw('COUNT(*) DESC')
                ->first();

            // Recent activities dengan proteksi ponpes_id
            $recentActivities = Notulen::with('user')
                ->where('ponpes_id', $userPonpesId)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            Log::info('Berhasil memuat data notulen', [
                'user_id' => $this->getAuthUserId(),
                'total_notulen' => $notulen->total(),
                'filters' => $request->all()
            ]);

            return view('pages.notulensi', compact(
                'notulen',
                'pimpinans',
                'tanggalOptions',
                'recentGambar',
                'totalGambar',
                'rapatBulanIni',
                'topPimpinan',
                'recentActivities'
            ));

        } catch (\Exception $e) {
            Log::error('Error pada notulen index', [
                'user_id' => $this->getAuthUserId(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data notulen.');
        }
    }

    public function create()
    {
        try {
            Log::info('Mengakses form create notulen', [
                'user_id' => $this->getAuthUserId(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            return view('pages.action.create_notulensi');
        } catch (\Exception $e) {
            Log::error('Error pada notulen create form', [
                'user_id' => $this->getAuthUserId(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memproses store notulen', [
                'user_id' => $this->getAuthUserId(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token', 'gambar'])
            ]);

            $userPonpesId = $this->getUserPonpesId();
            
            // 🔥 GUNAKAN METHOD HELPER
            $userId = $this->getAuthUserId();

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
                'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Convert array to string for database storage
            $pesertaString = implode(', ', array_filter($request->peserta));
            $hasilString = implode("\n", array_filter($request->hasil));

            $notulenData = [
                'ponpes_id' => $userPonpesId,
                'user_id' => $userId, // 🔥 GUNAKAN $userId DARI HELPER
                'agenda' => $request->agenda,
                'pimpinan' => $request->pimpinan,
                'peserta' => $pesertaString,
                'tempat' => $request->tempat,
                'alur_rapat' => $request->alur_rapat,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'keterangan' => $request->keterangan,
                'hasil' => $hasilString
            ];

            Log::debug('Data notulen yang akan disimpan', $notulenData);

            // Create notulen
            $notulen = Notulen::create($notulenData);

            // Handle gambar upload
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    try {
                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Store file to storage
                        $path = $file->storeAs('notulensi', $filename, 'public');

                        // Create gambar record
                        Gambar::create([
                            'id_notulen' => $notulen->id_notulen,
                            'user_id' => $userId, // 🔥 GUNAKAN INTEGER YANG SAMA
                            'path_gambar' => $path,
                            'keterangan' => 'Dokumentasi rapat: ' . $request->agenda
                        ]);

                        Log::info('Gambar berhasil diupload', [
                            'notulen_id' => $notulen->id_notulen,
                            'filename' => $filename,
                            'path' => $path
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Error storing image: ' . $e->getMessage(), [
                            'notulen_id' => $notulen->id_notulen,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            Log::info('Berhasil membuat notulen baru', [
                'user_id' => $userId,
                'notulen_id' => $notulen->id_notulen,
                'agenda' => $notulen->agenda
            ]);

            return redirect()->route('notulen.index')
                ->with('success', 'Notulen rapat <strong>' . $notulen->agenda . '</strong> berhasil disimpan!');

        } catch (\Exception $e) {
            Log::error('Error pada notulen store', [
                'user_id' => $this->getAuthUserId(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan notulen.')
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            Log::info('Mengakses detail notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id
            ]);

            // Cek kepemilikan data notulen
            $notulen = $this->checkNotulenOwnership($id);
            $notulen->load(['user', 'gambar']);

            return view('pages.action.show_notulensi', compact('notulen'));

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk show notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada notulen show', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', 'Terjadi kesalahan saat memuat detail notulen.');
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id
            ]);

            // Cek kepemilikan data notulen
            $notulen = $this->checkNotulenOwnership($id);
            $notulen->load('gambar');

            // 🔥 FIXED: Gunakan method helper untuk permission check
            if (!$this->checkNotulenPermission($notulen)) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit notulen ini.');
            }

            return view('pages.action.edit_notulensi', compact('notulen'));

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada notulen edit', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('notulen.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'input_data' => $request->except(['_token', '_method', 'gambar', 'hapus_gambar'])
            ]);

            // Cek kepemilikan data notulen
            $notulen = $this->checkNotulenOwnership($id);

            // 🔥 FIXED: Gunakan method helper untuk permission check
            if (!$this->checkNotulenPermission($notulen)) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit notulen ini.');
            }

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
                        if ($gambar->path_gambar) {
                            Storage::disk('public')->delete($gambar->path_gambar);
                        }
                        // Hapus record dari database
                        $gambar->delete();
                        
                        Log::info('Gambar berhasil dihapus', [
                            'gambar_id' => $gambarId,
                            'notulen_id' => $id
                        ]);
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
                        'user_id' => $this->getAuthUserId(),
                        'path_gambar' => $path,
                        'keterangan' => 'Dokumentasi rapat: ' . $request->agenda
                    ]);

                    Log::info('Gambar baru ditambahkan', [
                        'notulen_id' => $notulen->id_notulen,
                        'filename' => $filename
                    ]);
                }
            }

            Log::info('Berhasil update notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $notulen->id_notulen,
                'agenda' => $notulen->agenda
            ]);

            return redirect()->route('notulen.index')
                ->with('success', 'Notulen rapat <strong>' . $notulen->agenda . '</strong> berhasil diperbarui!');

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada notulen update', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui notulen.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Memproses delete notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id
            ]);

            // Cek kepemilikan data notulen
            $notulen = $this->checkNotulenOwnership($id);

            // 🔥 FIXED: Gunakan method helper untuk permission check
            if (!$this->checkNotulenPermission($notulen)) {
                Log::warning('User tidak diizinkan menghapus notulen', [
                    'auth_user_id' => $this->getAuthUserId(),
                    'notulen_user_id' => $notulen->user_id,
                    'notulen_id' => $notulen->id_notulen
                ]);
                abort(403, 'Anda tidak memiliki akses untuk menghapus notulen ini.');
            }

            $notulenData = [
                'id_notulen' => $notulen->id_notulen,
                'agenda' => $notulen->agenda,
                'user_id' => $notulen->user_id
            ];

            // Hapus semua gambar terkait
            foreach ($notulen->gambar as $gambar) {
                if ($gambar->path_gambar) {
                    Storage::disk('public')->delete($gambar->path_gambar);
                }
                $gambar->delete();
            }

            $notulen->delete();

            Log::info('Berhasil delete notulen', [
                'user_id' => $this->getAuthUserId(),
                'deleted_notulen' => $notulenData
            ]);

            return redirect()->route('notulen.index')
                ->with('success', 'Notulen rapat berhasil dihapus!');

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete notulen', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada notulen delete', [
                'user_id' => $this->getAuthUserId(),
                'notulen_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('notulen.index')->with('error', 'Terjadi kesalahan saat menghapus notulen.');
        }
    }

    // Method untuk menghapus gambar individual
    public function hapusGambar($id)
    {
        try {
            Log::info('Memproses hapus gambar', [
                'user_id' => $this->getAuthUserId(),
                'gambar_id' => $id
            ]);

            $gambar = Gambar::findOrFail($id);

            // Cek notulen terkait
            $notulen = $gambar->notulen;
            if (!$notulen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar tidak terkait dengan notulen.'
                ], 404);
            }

            // 🔥 FIXED: Cek permission menggunakan method helper
            if (!$this->checkNotulenPermission($notulen)) {
                Log::warning('User tidak diizinkan menghapus gambar', [
                    'auth_user_id' => $this->getAuthUserId(),
                    'gambar_user_id' => $gambar->user_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus gambar ini.'
                ], 403);
            }

            $gambarData = [
                'id_gambar' => $gambar->id_gambar,
                'path_gambar' => $gambar->path_gambar
            ];

            // Hapus file dari storage
            if ($gambar->path_gambar) {
                Storage::disk('public')->delete($gambar->path_gambar);
            }

            // Hapus record dari database
            $gambar->delete();

            Log::info('Berhasil hapus gambar', [
                'user_id' => $this->getAuthUserId(),
                'deleted_gambar' => $gambarData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error pada hapus gambar', [
                'user_id' => $this->getAuthUserId(),
                'gambar_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus gambar.'
            ], 500);
        }
    }

    // Quick stats untuk dashboard
    public function getStats()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $totalNotulen = Notulen::where('ponpes_id', $userPonpesId)->count();
            $notulenBulanIni = Notulen::where('ponpes_id', $userPonpesId)
                ->whereMonth('tanggal', now()->month)
                ->count();
            $notulenMingguIni = Notulen::where('ponpes_id', $userPonpesId)
                ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_notulen' => $totalNotulen,
                    'notulen_bulan_ini' => $notulenBulanIni,
                    'notulen_minggu_ini' => $notulenMingguIni
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error pada getStats notulen', [
                'user_id' => $this->getAuthUserId(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik.'
            ], 500);
        }
    }
}