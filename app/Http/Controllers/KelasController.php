<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KelasController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if kelas belongs to user's ponpes
     */
    private function checkKelasOwnership($kelasId)
    {
        $userPonpesId = $this->getUserPonpesId();
        
        $kelas = Kelas::where('id_kelas', $kelasId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$kelas) {
            Log::warning('Akses kelas ditolak', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'kelas_id' => $kelasId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $kelas;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::info('Mengakses halaman kelas index', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $kelas = Kelas::where('ponpes_id', $userPonpesId)
                ->select('id_kelas', 'nama_kelas', 'tingkat')
                ->orderBy('tingkat', 'asc')
                ->orderBy('nama_kelas', 'asc')
                ->paginate(10);

            Log::info('Berhasil memuat data kelas', [
                'user_id' => Auth::id(),
                'total_data' => $kelas->total(),
                'current_page' => $kelas->currentPage()
            ]);

            return view('pages.kelas', compact('kelas'));
        } catch (\Exception $e) {
            Log::error('Error pada kelas index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data kelas.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            Log::info('Mengakses form create kelas', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            return view('pages.kelas-create');
        } catch (\Exception $e) {
            Log::error('Error pada kelas create form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan saat memuat form tambah kelas.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Memproses store kelas', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $request->validate([
                'nama_kelas' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:kelas,nama_kelas,NULL,id_kelas,ponpes_id,' . $userPonpesId
                ],
                'tingkat' => 'required|string|max:20',
            ], [
                'nama_kelas.required' => 'Nama kelas wajib diisi',
                'nama_kelas.max' => 'Nama kelas maksimal 50 karakter',
                'nama_kelas.unique' => 'Nama kelas sudah ada untuk ponpes ini',
                'tingkat.required' => 'Tingkat kelas wajib diisi',
                'tingkat.max' => 'Tingkat kelas maksimal 20 karakter'
            ]);

            $kelas = Kelas::create([
                'ponpes_id' => $userPonpesId,
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
            ]);

            Log::info('Berhasil membuat kelas baru', [
                'user_id' => Auth::id(),
                'kelas_id' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat
            ]);

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada store kelas', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada kelas store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah kelas.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            Log::info('Mengakses detail kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);

            $kelas = $this->checkKelasOwnership($id);

            Log::debug('Data kelas ditemukan', [
                'kelas_id' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas
            ]);

            return view('pages.kelas-show', compact('kelas'));
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk show kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('kelas.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kelas tidak ditemukan', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada kelas show', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan saat memuat data kelas.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);

            $kelas = $this->checkKelasOwnership($id);

            Log::debug('Data untuk form edit kelas', [
                'kelas_id' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat
            ]);

            return view('pages.kelas-edit', compact('kelas'));
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('kelas.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kelas tidak ditemukan untuk edit', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada kelas edit', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();
            $kelas = $this->checkKelasOwnership($id);

            $request->validate([
                'nama_kelas' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:kelas,nama_kelas,' . $id . ',id_kelas,ponpes_id,' . $userPonpesId
                ],
                'tingkat' => 'required|string|max:20',
            ], [
                'nama_kelas.required' => 'Nama kelas wajib diisi',
                'nama_kelas.max' => 'Nama kelas maksimal 50 karakter',
                'nama_kelas.unique' => 'Nama kelas sudah ada untuk ponpes ini',
                'tingkat.required' => 'Tingkat kelas wajib diisi',
                'tingkat.max' => 'Tingkat kelas maksimal 20 karakter'
            ]);

            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
            ]);

            Log::info('Berhasil update kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat
            ]);

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('kelas.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada update kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::warning('Kelas tidak ditemukan untuk update', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada kelas update', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui kelas.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('Memproses delete kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);

            $kelas = $this->checkKelasOwnership($id);

            // Cek apakah kelas sedang digunakan di data santri
            $usedInSantri = \App\Models\Santri::where('id_kelas', $id)
                ->where('ponpes_id', $this->getUserPonpesId())
                ->exists();

            if ($usedInSantri) {
                Log::warning('Gagal delete kelas karena digunakan santri', [
                    'user_id' => Auth::id(),
                    'kelas_id' => $id,
                    'nama_kelas' => $kelas->nama_kelas
                ]);
                return redirect()->route('kelas.index')
                    ->with('error', 'Tidak dapat menghapus kelas karena sedang digunakan oleh data santri!');
            }

            $kelasData = [
                'id_kelas' => $kelas->id_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat
            ];

            $kelas->delete();

            Log::info('Berhasil delete kelas', [
                'user_id' => Auth::id(),
                'deleted_kelas' => $kelasData
            ]);

            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete kelas', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('kelas.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kelas tidak ditemukan untuk delete', [
                'user_id' => Auth::id(),
                'kelas_id' => $id
            ]);
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada kelas delete', [
                'user_id' => Auth::id(),
                'kelas_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('kelas.index')->with('error', 'Terjadi kesalahan saat menghapus kelas.');
        }
    }

    /**
     * Get kelas data for API (optional)
     */
    public function getKelasByPonpes()
    {
        try {
            Log::debug('Mengambil data kelas untuk API', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $kelas = Kelas::where('ponpes_id', $userPonpesId)
                ->select('id_kelas', 'nama_kelas', 'tingkat')
                ->orderBy('tingkat', 'asc')
                ->orderBy('nama_kelas', 'asc')
                ->get();

            Log::debug('Data kelas API berhasil diambil', [
                'user_id' => Auth::id(),
                'data_count' => $kelas->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $kelas
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error pada getKelasByPonpes API', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kelas'
            ], 500);
        }
    }
}