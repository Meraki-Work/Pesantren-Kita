<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function create()
    {
        return view('pages.kelas.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kelas' => 'required|string|max:50',
                'tingkat' => 'required|string|max:20',
            ]);

            DB::insert(
                'INSERT INTO kelas (ponpes_id, nama_kelas, tingkat) VALUES (?, ?, ?)',
                [null, $request->nama_kelas, $request->tingkat]
            );

            return redirect()->route('santri.index')->with('success', 'Kelas berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
