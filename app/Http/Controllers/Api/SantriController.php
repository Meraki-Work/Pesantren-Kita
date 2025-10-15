<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        // Ambil semua data santri dari database
        $data = Santri::all();
        return response()->json($data);
    }

    public function show($id)
    {
        // Ambil satu data berdasarkan ID
        $data = Santri::findOrFail($id);
        return response()->json($data);
    }
}
