<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DummyController extends Controller
{
    public function index()
    {
        $data = [
            [
                'userid' => '456789356',
                'transaction_date' => 'Sep 8, 2024, 03:13pm',
                'sumber' => 'Kas Tunai',
                'status' => 'Keluar',
                'kategori' => 'Asset',
                'jumlah' => '-$15,000.00',
                'keterangan' => 'Pembelian Rak Sepatu'
            ],
            [
                'userid' => '456789356',
                'transaction_date' => 'Sep 6, 2024, 07:00am',
                'sumber' => 'Bank BCA - Arifin Mustohar',
                'status' => 'Pemasukan',
                'kategori' => 'Donasi',
                'jumlah' => '+$30,000.00',
                'keterangan' => 'Untuk Katering Maulid Nabi'
            ],
        ];

        return view('pages.tes', compact('data'));
    }
}
