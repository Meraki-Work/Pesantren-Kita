<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PonpesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ponpesList = Ponpes::orderBy('nama_ponpes')->paginate(20);
        return view('admin.ponpes.index', compact('ponpesList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ponpes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ponpes' => 'required|unique:ponpes,id_ponpes|max:64',
            'nama_ponpes' => 'required|string|max:100',
            'alamat' => 'required|string',
            'tahun_berdiri' => 'required|integer|digits:4',
            'telp' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'logo_ponpes' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jumlah_santri' => 'nullable|integer|min:0',
            'jumlah_staf' => 'nullable|integer|min:0',
            'pimpinan' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_ponpes')) {
            $logoPath = $request->file('logo_ponpes')->store('ponpes-logos', 'public');
            $validated['logo_ponpes'] = $logoPath;
        }

        Ponpes::create($validated);

        Log::info('Pesantren created', [
            'id_ponpes' => $validated['id_ponpes'],
            'nama_ponpes' => $validated['nama_ponpes']
        ]);

        return redirect()->route('admin.ponpes.index')
            ->with('success', 'Pesantren berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ponpes = Ponpes::findOrFail($id);

        // Hitung statistik
        $statistics = [
            'total_santri' => $ponpes->santri()->count(),
            'total_keuangan' => $ponpes->keuangan()->sum('jumlah'),
            'active_contents' => $ponpes->landingContents()->where('is_active', true)->count(),
            'total_gambar' => $ponpes->gambar()->count(),
        ];

        return view('admin.ponpes.show', compact('ponpes', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ponpes = Ponpes::findOrFail($id);
        return view('admin.ponpes.edit', compact('ponpes'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update logo for ponpes
     */
    public function updateLogo(Request $request, $id)
    {
        $ponpes = Ponpes::findOrFail($id);

        $request->validate([
            'logo_ponpes' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_logo' => 'nullable|boolean'
        ]);

        if ($request->has('remove_logo') && $request->remove_logo) {
            // Delete existing logo
            if ($ponpes->logo_ponpes && Storage::disk('public')->exists($ponpes->logo_ponpes)) {
                Storage::disk('public')->delete($ponpes->logo_ponpes);
            }
            $ponpes->logo_ponpes = null;
            $ponpes->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil dihapus'
            ]);
        }

        if ($request->hasFile('logo_ponpes')) {
            // Delete old logo if exists
            if ($ponpes->logo_ponpes && Storage::disk('public')->exists($ponpes->logo_ponpes)) {
                Storage::disk('public')->delete($ponpes->logo_ponpes);
            }

            $logoPath = $request->file('logo_ponpes')->store('ponpes-logos', 'public');
            $ponpes->logo_ponpes = $logoPath;
            $ponpes->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil diperbarui'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file logo yang diunggah'
        ]);
    }

    public function update(Request $request, $id)
    {
        $ponpes = Ponpes::findOrFail($id);

        $validated = $request->validate([
            'nama_ponpes' => 'required|string|max:100',
            'alamat' => 'required|string',
            'tahun_berdiri' => 'required|integer|digits:4',
            'telp' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'logo_ponpes' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jumlah_santri' => 'nullable|integer|min:0',
            'jumlah_staf' => 'nullable|integer|min:0',
            'pimpinan' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_ponpes')) {
            // Delete old logo if exists
            if ($ponpes->logo_ponpes && Storage::disk('public')->exists($ponpes->logo_ponpes)) {
                Storage::disk('public')->delete($ponpes->logo_ponpes);
            }

            $logoPath = $request->file('logo_ponpes')->store('ponpes-logos', 'public');
            $validated['logo_ponpes'] = $logoPath;
        }

        // Handle remove logo checkbox
        if ($request->has('remove_logo')) {
            if ($ponpes->logo_ponpes && Storage::disk('public')->exists($ponpes->logo_ponpes)) {
                Storage::disk('public')->delete($ponpes->logo_ponpes);
            }
            $validated['logo_ponpes'] = null;
        }

        $ponpes->update($validated);

        Log::info('Pesantren updated', [
            'id_ponpes' => $ponpes->id_ponpes,
            'nama_ponpes' => $validated['nama_ponpes']
        ]);

        return redirect()->route('admin.ponpes.index')
            ->with('success', 'Pesantren berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ponpes = Ponpes::findOrFail($id);

        // Delete logo if exists
        if ($ponpes->logo_ponpes && Storage::disk('public')->exists($ponpes->logo_ponpes)) {
            Storage::disk('public')->delete($ponpes->logo_ponpes);
        }

        $ponpes->delete();

        Log::info('Pesantren deleted', [
            'id_ponpes' => $ponpes->id_ponpes,
            'nama_ponpes' => $ponpes->nama_ponpes
        ]);

        return redirect()->route('admin.ponpes.index')
            ->with('success', 'Pesantren berhasil dihapus.');
    }
}
