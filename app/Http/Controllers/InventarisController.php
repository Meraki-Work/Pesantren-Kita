<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Inventaris;

class InventarisController extends Controller
{
    public function index()
    {
    $data = DB::table('inventaris')
        ->select('kategori', DB::raw('SUM(jumlah) as total_jumlah'))
        ->groupBy('kategori')
        ->get();

        // Kirim ke view
        return view('pages.inventaris', compact('data'));
    }
}
