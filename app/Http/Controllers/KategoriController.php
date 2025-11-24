<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if kategori belongs to user's ponpes
     */
    private function checkKategoriOwnership($kategoriId)
    {
        $userPonpesId = $this->getUserPonpesId();
        
        $kategori = Kategori::where('id_kategori', $kategoriId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$kategori) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $kategori;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $kategories = Kategori::where('ponpes_id', $userPonpesId)
                ->select('id_kategori', 'nama_kategori')
                ->orderBy('nama_kategori', 'asc')
                ->paginate(10);

            return view('pages.kategori-index', compact('kategories'));
        } catch (\Exception $e) {
            Log::error('Error in KategoriController index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data kategori.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.kategori-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $request->validate([
                'nama_kategori' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:kategori,nama_kategori,NULL,id_kategori,ponpes_id,' . $userPonpesId
                ]
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
                'nama_kategori.unique' => 'Nama kategori sudah ada untuk ponpes ini'
            ]);

            Kategori::create([
                'ponpes_id' => $userPonpesId,
                'nama_kategori' => $request->nama_kategori,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error in KategoriController store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah kategori.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $kategori = $this->checkKategoriOwnership($id);

            return view('pages.kategori-show', compact('kategori'));
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to kategori show: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('kategori.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kategori not found: ' . $id);
            return redirect()->route('kategori.index')->with('error', 'Data kategori tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in KategoriController show: ' . $e->getMessage());
            return redirect()->route('kategori.index')->with('error', 'Terjadi kesalahan saat memuat data kategori.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $kategori = $this->checkKategoriOwnership($id);

            return view('pages.kategori-edit', compact('kategori'));
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to kategori edit: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('kategori.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kategori not found for edit: ' . $id);
            return redirect()->route('kategori.index')->with('error', 'Data kategori tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in KategoriController edit: ' . $e->getMessage());
            return redirect()->route('kategori.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $userPonpesId = $this->getUserPonpesId();
            $kategori = $this->checkKategoriOwnership($id);

            $request->validate([
                'nama_kategori' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:kategori,nama_kategori,' . $id . ',id_kategori,ponpes_id,' . $userPonpesId
                ]
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
                'nama_kategori.unique' => 'Nama kategori sudah ada untuk ponpes ini'
            ]);

            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to kategori update: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('kategori.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::warning('Kategori not found for update: ' . $id);
            return redirect()->route('kategori.index')->with('error', 'Data kategori tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in KategoriController update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui kategori.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kategori = $this->checkKategoriOwnership($id);

            // Cek apakah kategori sedang digunakan di data keuangan
            $usedInKeuangan = \App\Models\Keuangan::where('id_kategori', $id)
                ->where('ponpes_id', $this->getUserPonpesId())
                ->exists();

            if ($usedInKeuangan) {
                return redirect()->route('kategori.index')
                    ->with('error', 'Tidak dapat menghapus kategori karena sedang digunakan dalam data keuangan!');
            }

            $kategori->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to kategori destroy: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('kategori.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Kategori not found for delete: ' . $id);
            return redirect()->route('kategori.index')->with('error', 'Data kategori tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in KategoriController destroy: ' . $e->getMessage());
            return redirect()->route('kategori.index')->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }

    /**
     * Get kategori data for API (optional)
     */
    public function getKategoriByPonpes()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $kategories = Kategori::where('ponpes_id', $userPonpesId)
                ->select('id_kategori', 'nama_kategori')
                ->orderBy('nama_kategori', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $kategories
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in KategoriController getKategoriByPonpes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kategori'
            ], 500);
        }
    }
}