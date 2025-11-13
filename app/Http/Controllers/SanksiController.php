<?php

namespace App\Http\Controllers;

use App\Models\Sanksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SanksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sanksi::with(['user', 'ponpe']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('tanggal', '<=', $request->end_date);
        }

        $sanksi = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('pages.sangksi', compact('sanksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cek struktur tabel user untuk debugging
        try {
            $userColumns = DB::select('SHOW COLUMNS FROM user');
            $primaryKey = collect($userColumns)->where('Key', 'PRI')->first();
            
            Log::info('User table structure:', [
                'columns' => $userColumns,
                'primary_key' => $primaryKey
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking user table: ' . $e->getMessage());
        }

        $users = User::orderBy('username')->get();
        
        // Debug: cek data users
        if ($users->isNotEmpty()) {
            $firstUser = $users->first();
            Log::info('First user data:', [
                'id' => $firstUser->id ?? 'null',
                'id_user' => $firstUser->id_user ?? 'null',
                'username' => $firstUser->username ?? 'null'
            ]);
        }

        if ($users->isEmpty()) {
            return redirect()->route('sangksi')->with('error', 'Tidak ada data user yang tersedia. Silakan tambahkan user terlebih dahulu.');
        }

        return view('pages.action.create_sanksi', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Debug request data
    Log::info('Store request data:', $request->all());

    $messages = [
        'user_id.required' => 'Pilih user harus diisi.',
        'jenis.required' => 'Jenis sanksi harus diisi.',
        'deskripsi.required' => 'Deskripsi pelanggaran harus diisi.',
        'hukuman.required' => 'Hukuman harus diisi.',
        'tanggal.required' => 'Tanggal sanksi harus diisi.',
        'status.required' => 'Status harus dipilih.',
    ];

    // HAPUS SEMUA VALIDASI EXISTS - hanya pakai required
    $request->validate([
        'user_id' => 'required',
        'jenis' => 'required|string|max:50',
        'deskripsi' => 'required|string',
        'hukuman' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'status' => 'required|in:Aktif,Selesai'
    ], $messages);

    try {
        Sanksi::create([
            'user_id' => $request->user_id,
            'ponpes_id' => auth()->user()->ponpes_id ?? null,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'hukuman' => $request->hukuman,
            'tanggal' => $request->tanggal,
            'status' => $request->status
        ]);

        return redirect()->route('sangksi')->with('success', 'Data sanksi berhasil ditambahkan.');
    } catch (\Exception $e) {
        Log::error('Error storing sanksi: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Sanksi $sanksi)
    {
        $sanksi->load(['user', 'ponpe']);
        return view('pages.action.show_sanksi', compact('sanksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sanksi $sanksi)
    {
        $users = User::orderBy('username')->get();
        return view('pages.action.edit_sanksi', compact('sanksi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, Sanksi $sanksi)
{
    $messages = [
        'user_id.required' => 'Pilih user harus diisi.',
        'jenis.required' => 'Jenis sanksi harus diisi.',
        'deskripsi.required' => 'Deskripsi pelanggaran harus diisi.',
        'hukuman.required' => 'Hukuman harus diisi.',
        'tanggal.required' => 'Tanggal sanksi harus diisi.',
        'status.required' => 'Status harus dipilih.',
    ];

    // HAPUS SEMUA VALIDASI EXISTS - hanya pakai required
    $request->validate([
        'user_id' => 'required',
        'jenis' => 'required|string|max:50',
        'deskripsi' => 'required|string',
        'hukuman' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'status' => 'required|in:Aktif,Selesai'
    ], $messages);

    $sanksi->update([
        'user_id' => $request->user_id,
        'jenis' => $request->jenis,
        'deskripsi' => $request->deskripsi,
        'hukuman' => $request->hukuman,
        'tanggal' => $request->tanggal,
        'status' => $request->status
    ]);

    return redirect()->route('sangksi')->with('success', 'Data sanksi berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sanksi $sanksi)
    {
        $sanksi->delete();
        return redirect()->route('sangksi')->with('success', 'Data sanksi berhasil dihapus.');
    }
}