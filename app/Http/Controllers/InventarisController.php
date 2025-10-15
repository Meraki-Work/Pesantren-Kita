<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;

class InventarisController extends Controller
{
    public function index()
    {
        $data = Inventaris::all();

        return view('pages.inventaris','data');
    }
}
