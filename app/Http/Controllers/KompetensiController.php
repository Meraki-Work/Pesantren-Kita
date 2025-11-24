<?php

namespace App\Http\Controllers;

use App\Models\Pencapaian;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KompetensiController extends Controller
{
    /**
     * Mendapatkan ponpes_id user yang login
     */
    private function getUserPonpesId(): string
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Menampilkan halaman kompetensi dengan proteksi
     */
    public function index(): View
    {
        try {
            Log::info('Mengakses halaman kompetensi index', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil pencapaian hanya dari ponpes yang sama
            $pencapaian = Pencapaian::with(['santri', 'santri.kelas']) // 🔥 PERBAIKAN: kela -> kelas
                ->where('ponpes_id', $userPonpesId)
                ->orderBy('tanggal', 'desc')
                ->get();

            // Ambil kelas hanya dari ponpes yang sama
            $kelas = Kelas::where('ponpes_id', $userPonpesId)->get();

            // Ambil santri hanya dari ponpes yang sama
            $santri = Santri::with('kelas')
                ->where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama', 'id_kelas')
                ->get();

            $selectedKelas = $santri->first()?->id_kelas ?? null; 
            $selectedKelasNama = $santri->first()?->kelas->nama_kelas ?? '-- Pilih Kelas --'; // 🔥 PERBAIKAN: kela -> kelas

            Log::info('Berhasil memuat data kompetensi', [
                'user_id' => Auth::id(),
                'total_pencapaian' => $pencapaian->count(),
                'total_santri' => $santri->count(),
                'total_kelas' => $kelas->count()
            ]);

            return view('pages.create_kompetensi', compact(
                'santri', 
                'kelas', 
                'pencapaian', 
                'selectedKelas', 
                'selectedKelasNama'
            ));

        } catch (\Exception $e) {
            Log::error('Error pada kompetensi index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e; // Biarkan exception handler menangani
        }
    }

    /**
     * Menyimpan data kompetensi baru dengan proteksi
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            Log::info('Memproses store kompetensi', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();
            
            // Validasi input
            $request->validate([
                'id_santri'  => 'required|integer',
                'judul'      => 'required|string|max:150',
                'deskripsi'  => 'nullable|string',
                'tipe'       => 'required|in:Akademik,Non-Akademik,Tahfidz,Lainnya',
                'skor'       => 'nullable|integer|min:0|max:100',
                'tanggal'    => 'required|date',
            ]);

            // Validasi tambahan: pastikan santri milik ponpes yang sama
            $santri = Santri::where('id_santri', $request->id_santri)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$santri) {
                Log::warning('Santri tidak valid untuk store kompetensi', [
                    'user_id' => Auth::id(),
                    'santri_id' => $request->id_santri
                ]);
                return redirect()->back()
                    ->with('error', 'Santri tidak valid atau tidak memiliki akses')
                    ->withInput();
            }

            // Gunakan Eloquent untuk insert yang lebih aman
            $pencapaian = Pencapaian::create([
                'ponpes_id'  => $userPonpesId,
                'id_santri'  => $request->id_santri,
                'user_id'    => Auth::id(), // Isi dengan user yang login
                'judul'      => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'tipe'       => $request->tipe,
                'skor'       => $request->skor,
                'tanggal'    => $request->tanggal,
                'created_at' => now()
            ]);

            Log::info('Berhasil membuat kompetensi baru', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $pencapaian->id_pencapaian,
                'santri_id' => $request->id_santri,
                'judul' => $request->judul
            ]);

            return redirect()->route('kompetensi.index')
                ->with('success', 'Pencapaian berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada store kompetensi', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada kompetensi store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan form edit kompetensi dengan proteksi
     */
    public function edit($id): View|RedirectResponse
    {
        try {
            Log::info('Mengakses form edit kompetensi', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $pencapaian = Pencapaian::with(['santri', 'santri.kelas']) // 🔥 PERBAIKAN: kela -> kelas
                ->where('id_pencapaian', $id)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$pencapaian) {
                Log::warning('Kompetensi tidak ditemukan untuk edit', [
                    'user_id' => Auth::id(),
                    'pencapaian_id' => $id
                ]);
                return redirect()->route('kompetensi.index')
                    ->with('error', 'Data kompetensi tidak ditemukan atau tidak memiliki akses');
            }

            // Ambil santri hanya dari ponpes yang sama untuk dropdown
            $santri = Santri::where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama', 'id_kelas')
                ->get();

            Log::debug('Data untuk form edit kompetensi', [
                'pencapaian_id' => $pencapaian->id_pencapaian,
                'santri_count' => $santri->count()
            ]);

            return view('pages.action.kompetensi_edit', compact('pencapaian', 'santri'));

        } catch (\Exception $e) {
            Log::error('Error pada kompetensi edit', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('kompetensi.index')
                ->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update data kompetensi dengan proteksi
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            Log::info('Memproses update kompetensi', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data sebelum update
            $pencapaian = Pencapaian::where('id_pencapaian', $id)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$pencapaian) {
                Log::warning('Kompetensi tidak ditemukan untuk update', [
                    'user_id' => Auth::id(),
                    'pencapaian_id' => $id
                ]);
                return redirect()->route('kompetensi.index')
                    ->with('error', 'Data kompetensi tidak ditemukan atau tidak memiliki akses');
            }

            // Validasi input
            $request->validate([
                'id_santri'  => 'required|integer',
                'judul'      => 'required|string|max:150',
                'deskripsi'  => 'nullable|string',
                'tipe'       => 'required|in:Akademik,Non-Akademik,Tahfidz,Lainnya',
                'skor'       => 'nullable|integer|min:0|max:100',
                'tanggal'    => 'required|date',
            ]);

            // Validasi tambahan: pastikan santri baru (jika berubah) milik ponpes yang sama
            if ($request->id_santri != $pencapaian->id_santri) {
                $santri = Santri::where('id_santri', $request->id_santri)
                    ->where('ponpes_id', $userPonpesId)
                    ->first();

                if (!$santri) {
                    Log::warning('Santri tidak valid untuk update kompetensi', [
                        'user_id' => Auth::id(),
                        'santri_id' => $request->id_santri
                    ]);
                    return redirect()->back()
                        ->with('error', 'Santri tidak valid atau tidak memiliki akses')
                        ->withInput();
                }
            }

            // Update data
            $pencapaian->update([
                'id_santri'  => $request->id_santri,
                'judul'      => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'tipe'       => $request->tipe,
                'skor'       => $request->skor,
                'tanggal'    => $request->tanggal,
            ]);

            Log::info('Berhasil update kompetensi', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $pencapaian->id_pencapaian,
                'judul' => $pencapaian->judul
            ]);

            return redirect()->route('kompetensi.index')
                ->with('success', 'Data kompetensi berhasil diupdate!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada update kompetensi', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada kompetensi update', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus data kompetensi dengan proteksi
     */
    public function destroy($id): RedirectResponse
    {
        try {
            Log::info('Memproses delete kompetensi', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $pencapaian = Pencapaian::where('id_pencapaian', $id)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$pencapaian) {
                Log::warning('Kompetensi tidak ditemukan untuk delete', [
                    'user_id' => Auth::id(),
                    'pencapaian_id' => $id
                ]);
                return redirect()->route('kompetensi.index')
                    ->with('error', 'Data kompetensi tidak ditemukan atau tidak memiliki akses');
            }

            $pencapaianData = [
                'id_pencapaian' => $pencapaian->id_pencapaian,
                'judul' => $pencapaian->judul,
                'santri_id' => $pencapaian->id_santri
            ];

            $pencapaian->delete();

            Log::info('Berhasil delete kompetensi', [
                'user_id' => Auth::id(),
                'deleted_pencapaian' => $pencapaianData
            ]);

            return redirect()->route('kompetensi.index')
                ->with('success', 'Data kompetensi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error pada kompetensi delete', [
                'user_id' => Auth::id(),
                'pencapaian_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Gagal menghapus data kompetensi: ' . $e->getMessage());
        }
    }

    /**
     * Get data kompetensi berdasarkan santri (untuk AJAX/API)
     */
    public function getBySantri($santriId): \Illuminate\Http\JsonResponse
    {
        try {
            Log::debug('Mengambil kompetensi by santri', [
                'user_id' => Auth::id(),
                'santri_id' => $santriId
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Validasi santri milik ponpes yang sama
            $santri = Santri::where('id_santri', $santriId)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$santri) {
                Log::warning('Santri tidak ditemukan untuk getBySantri', [
                    'user_id' => Auth::id(),
                    'santri_id' => $santriId
                ]);
                return response()->json(['error' => 'Santri tidak ditemukan'], 404);
            }

            $kompetensi = Pencapaian::where('id_santri', $santriId)
                ->where('ponpes_id', $userPonpesId)
                ->orderBy('tanggal', 'desc')
                ->get();

            Log::debug('Berhasil mengambil kompetensi by santri', [
                'user_id' => Auth::id(),
                'santri_id' => $santriId,
                'data_count' => $kompetensi->count()
            ]);

            return response()->json($kompetensi);

        } catch (\Exception $e) {
            Log::error('Error pada getBySantri', [
                'user_id' => Auth::id(),
                'santri_id' => $santriId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }

    /**
     * Get data kompetensi berdasarkan kelas (untuk filter)
     */
    public function getByKelas($kelasId): \Illuminate\Http\JsonResponse
    {
        try {
            Log::debug('Mengambil kompetensi by kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $kelasId
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Validasi kelas milik ponpes yang sama
            $kelas = Kelas::where('id_kelas', $kelasId)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$kelas) {
                Log::warning('Kelas tidak ditemukan untuk getByKelas', [
                    'user_id' => Auth::id(),
                    'kelas_id' => $kelasId
                ]);
                return response()->json(['error' => 'Kelas tidak ditemukan'], 404);
            }

            $kompetensi = Pencapaian::with(['santri', 'santri.kelas']) // 🔥 PERBAIKAN: kela -> kelas
                ->whereHas('santri', function($query) use ($kelasId, $userPonpesId) {
                    $query->where('id_kelas', $kelasId)
                          ->where('ponpes_id', $userPonpesId);
                })
                ->where('ponpes_id', $userPonpesId)
                ->orderBy('tanggal', 'desc')
                ->get();

            Log::debug('Berhasil mengambil kompetensi by kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $kelasId,
                'data_count' => $kompetensi->count()
            ]);

            return response()->json($kompetensi);

        } catch (\Exception $e) {
            Log::error('Error pada getByKelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $kelasId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }
}