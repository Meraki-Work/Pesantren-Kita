<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Santri;
use App\Models\Kategori;
use App\Models\Keuangan;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LaundryController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if keuangan belongs to user's ponpes
     */
    private function checkKeuanganOwnership($keuanganId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $keuangan = Keuangan::where('id_keuangan', $keuanganId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$keuangan) {
            Log::warning('Akses keuangan laundry ditolak', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'keuangan_id' => $keuanganId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $keuangan;
    }

    public function index(Request $request)
    {
        try {
            Log::info('Mengakses halaman laundry index', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil ID kategori laundry untuk ponpes user
            $kategoriLaundry = Kategori::where('ponpes_id', $userPonpesId)
                ->where(function($query) {
                    $query->where('nama_kategori', 'like', '%laundry%')
                          ->orWhere('nama_kategori', 'like', '%Laundry%');
                })
                ->first();

            // Query dasar dengan filter ponpes_id
            $query = DB::table('keuangan as k')
                ->leftJoin('santri as s', 'k.id_santri', '=', 's.id_santri')
                ->leftJoin('kategori as kat', 'k.id_kategori', '=', 'kat.id_kategori')
                ->where('k.ponpes_id', $userPonpesId)
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
                );

            // Filter by kategori laundry jika ditemukan
            if ($kategoriLaundry) {
                $query->where('k.id_kategori', $kategoriLaundry->id_kategori);
            }

            // Apply search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('s.nama', 'like', "%{$search}%")
                      ->orWhere('k.sumber_dana', 'like', "%{$search}%")
                      ->orWhere('k.keterangan', 'like', "%{$search}%");
                });
            }

            // Apply status filter
            if ($request->has('status') && $request->status) {
                $query->where('k.status', $request->status);
            }

            // Apply date filter
            if ($request->has('start_date') && $request->start_date) {
                $query->where('k.tanggal', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->where('k.tanggal', '<=', $request->end_date);
            }

            $keuangan = $query->orderBy('k.tanggal', 'desc')
                            ->paginate(10);

            // Ambil data santri untuk dropdown (hanya santri dari ponpes user)
            $santri = Santri::where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama')
                ->get();

            // Ambil data kategori laundry untuk dropdown (hanya kategori dari ponpes user)
            $kategori = Kategori::where('ponpes_id', $userPonpesId)
                ->where(function($query) {
                    $query->where('nama_kategori', 'like', '%laundry%')
                          ->orWhere('nama_kategori', 'like', '%Laundry%');
                })
                ->select('id_kategori', 'nama_kategori')
                ->get();

            // Get statistics
            $statistics = $this->getStatistics($userPonpesId, $kategoriLaundry?->id_kategori);

            Log::info('Berhasil memuat data laundry', [
                'user_id' => Auth::id(),
                'total_data' => $keuangan->total(),
                'statistics' => $statistics
            ]);

            return view('pages.laundry', compact(
                'keuangan', 
                'santri', 
                'kategori', 
                'statistics'
            ));
        } catch (\Exception $e) {
            Log::error('Error pada laundry index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data laundry.');
        }
    }

    public function create()
    {
        try {
            Log::info('Mengakses form create laundry', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil data santri untuk dropdown (hanya santri dari ponpes user)
            $santri = Santri::where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama')
                ->get();

            // Ambil data kategori laundry untuk dropdown (hanya kategori dari ponpes user)
            $kategori = Kategori::where('ponpes_id', $userPonpesId)
                ->where(function($query) {
                    $query->where('nama_kategori', 'like', '%laundry%')
                          ->orWhere('nama_kategori', 'like', '%Laundry%');
                })
                ->select('id_kategori', 'nama_kategori')
                ->get();

            Log::debug('Data untuk form create laundry', [
                'santri_count' => $santri->count(),
                'kategori_count' => $kategori->count()
            ]);

            return view('pages.action.create_laundry', compact('santri', 'kategori'));
        } catch (\Exception $e) {
            Log::error('Error pada laundry create form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('laundry.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memproses store laundry', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $request->validate([
                'id_santri' => 'required|integer|exists:santri,id_santri',
                'id_kategori' => 'required|integer|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'keterangan' => 'nullable|string|max:500',
                'jumlah' => 'required|numeric|min:0',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date'
            ], [
                'id_santri.exists' => 'Santri tidak ditemukan',
                'id_kategori.exists' => 'Kategori tidak ditemukan',
                'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
                'status.in' => 'Status harus Masuk atau Keluar'
            ]);

            // Check if santri belongs to user's ponpes
            $santri = Santri::where('id_santri', $request->id_santri)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$santri) {
                throw new \Exception('Santri tidak ditemukan atau tidak memiliki akses');
            }

            // Check if kategori belongs to user's ponpes
            $kategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$kategori) {
                throw new \Exception('Kategori tidak ditemukan atau tidak memiliki akses');
            }

            Keuangan::create([
                'ponpes_id' => $userPonpesId,
                'user_id' => Auth::id(),
                'id_santri' => $request->id_santri,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'status' => $request->status,
                'tanggal' => $request->tanggal
            ]);

            Log::info('Berhasil membuat data laundry baru', [
                'user_id' => Auth::id(),
                'santri_id' => $request->id_santri,
                'kategori_id' => $request->id_kategori
            ]);

            return redirect()->route('laundry.index')
                ->with('success', 'Data laundry berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada store laundry', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada laundry store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah data laundry.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $keuangan = $this->checkKeuanganOwnership($id);

            // Ambil data santri untuk dropdown (hanya santri dari ponpes user)
            $santri = Santri::where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama')
                ->get();

            // Ambil data kategori laundry untuk dropdown (hanya kategori dari ponpes user)
            $kategori = Kategori::where('ponpes_id', $userPonpesId)
                ->where(function($query) {
                    $query->where('nama_kategori', 'like', '%laundry%')
                          ->orWhere('nama_kategori', 'like', '%Laundry%');
                })
                ->select('id_kategori', 'nama_kategori')
                ->get();

            Log::debug('Data untuk form edit laundry', [
                'keuangan_id' => $keuangan->id_keuangan,
                'santri_count' => $santri->count(),
                'kategori_count' => $kategori->count()
            ]);

            return view('pages.action.laundry_Fedit', compact('keuangan', 'santri', 'kategori'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('laundry.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Data laundry tidak ditemukan untuk edit', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('laundry.index')->with('error', 'Data laundry tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada laundry edit', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('laundry.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();
            $keuangan = $this->checkKeuanganOwnership($id);

            $request->validate([
                'id_santri' => 'required|integer|exists:santri,id_santri',
                'id_kategori' => 'required|integer|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'keterangan' => 'nullable|string|max:500',
                'jumlah' => 'required|numeric|min:0',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date'
            ], [
                'id_santri.exists' => 'Santri tidak ditemukan',
                'id_kategori.exists' => 'Kategori tidak ditemukan',
                'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
                'status.in' => 'Status harus Masuk atau Keluar'
            ]);

            // Check if santri belongs to user's ponpes
            $santri = Santri::where('id_santri', $request->id_santri)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$santri) {
                throw new \Exception('Santri tidak ditemukan atau tidak memiliki akses');
            }

            // Check if kategori belongs to user's ponpes
            $kategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$kategori) {
                throw new \Exception('Kategori tidak ditemukan atau tidak memiliki akses');
            }

            $keuangan->update([
                'id_santri' => $request->id_santri,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'status' => $request->status,
                'tanggal' => $request->tanggal
            ]);

            Log::info('Berhasil update data laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $keuangan->id_keuangan,
                'santri_id' => $request->id_santri
            ]);

            return redirect()->route('laundry.index')
                ->with('success', 'Data laundry berhasil diperbarui!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('laundry.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada update laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::warning('Data laundry tidak ditemukan untuk update', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('laundry.index')->with('error', 'Data laundry tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada laundry update', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data laundry.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Memproses delete laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);

            $keuangan = $this->checkKeuanganOwnership($id);

            $keuanganData = [
                'id_keuangan' => $keuangan->id_keuangan,
                'sumber_dana' => $keuangan->sumber_dana,
                'jumlah' => $keuangan->jumlah
            ];

            $keuangan->delete();

            Log::info('Berhasil delete data laundry', [
                'user_id' => Auth::id(),
                'deleted_keuangan' => $keuanganData
            ]);

            return redirect()->route('laundry.index')
                ->with('success', 'Data laundry berhasil dihapus!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete laundry', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('laundry.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Data laundry tidak ditemukan untuk delete', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('laundry.index')->with('error', 'Data laundry tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada laundry delete', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('laundry.index')->with('error', 'Terjadi kesalahan saat menghapus data laundry.');
        }
    }

    /**
     * Get statistics for laundry
     */
    /**
 * Get statistics for laundry - UBAH DARI PRIVATE KE PUBLIC
 */
public function getStatistics()
{
    try {
        $userPonpesId = $this->getUserPonpesId();
        
        // Ambil ID kategori laundry untuk ponpes user
        $kategoriLaundry = Kategori::where('ponpes_id', $userPonpesId)
            ->where(function($query) {
                $query->where('nama_kategori', 'like', '%laundry%')
                      ->orWhere('nama_kategori', 'like', '%Laundry%');
            })
            ->first();

        $query = Keuangan::where('ponpes_id', $userPonpesId);

        if ($kategoriLaundry) {
            $query->where('id_kategori', $kategoriLaundry->id_kategori);
        } else {
            // Jika tidak ada kategori laundry spesifik, filter by nama kategori
            $query->whereHas('kategori', function($q) {
                $q->where('nama_kategori', 'like', '%laundry%')
                  ->orWhere('nama_kategori', 'like', '%Laundry%');
            });
        }

        $totalPemasukan = (float) $query->clone()->where('status', 'Masuk')->sum('jumlah');
        $totalPengeluaran = (float) $query->clone()->where('status', 'Keluar')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'total_transaksi' => $query->count()
        ];
    } catch (\Exception $e) {
        Log::error('Error getting laundry statistics', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage()
        ]);
        return [
            'total_pemasukan' => 0,
            'total_pengeluaran' => 0,
            'saldo' => 0,
            'total_transaksi' => 0
        ];
    }
}
    // Method untuk contoh insert data (hanya untuk development)
    public function insertExampleData()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();
            
            // Cari kategori laundry untuk ponpes user
            $kategoriLaundry = Kategori::where('ponpes_id', $userPonpesId)
                ->where('nama_kategori', 'like', '%laundry%')
                ->first();

            if (!$kategoriLaundry) {
                return "Kategori laundry tidak ditemukan untuk ponpes ini";
            }

            // Cari santri untuk ponpes user
            $santri = Santri::where('ponpes_id', $userPonpesId)->first();

            if (!$santri) {
                return "Santri tidak ditemukan untuk ponpes ini";
            }

            Keuangan::create([
                'ponpes_id' => $userPonpesId,
                'user_id' => Auth::id(),
                'id_santri' => $santri->id_santri,
                'id_kategori' => $kategoriLaundry->id_kategori,
                'sumber_dana' => 'Laundry Santri',
                'keterangan' => 'Pembayaran laundry bulan Oktober',
                'jumlah' => 50000.00,
                'status' => 'Masuk',
                'tanggal' => now()->format('Y-m-d')
            ]);

            Log::info('Contoh data laundry berhasil ditambahkan', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId
            ]);

            return "Data contoh laundry berhasil ditambahkan!";
        } catch (\Exception $e) {
            Log::error('Error inserting example laundry data', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return "Error: " . $e->getMessage();
        }
    }
}