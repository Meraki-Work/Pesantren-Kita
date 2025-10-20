<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotulenController extends Controller
{
    public function index()
    {
        $cards = [
            ['kategori' => 'Pendidikan Kurikulum 2025', 'jumlah' => 48217300, 'sumber' => 'Kas Tunai, Donasi'],
            ['kategori' => 'Asset', 'jumlah' => 40121481, 'sumber' => 'Donasi Tetap, Pihak Ketiga'],
            ['kategori' => 'Inventaris', 'jumlah' => 21731200, 'sumber' => 'Kas Pondok'],
            ['kategori' => 'Kantor', 'jumlah' => 12873100, 'sumber' => 'Operasional'],
        ];

        $columns = ['UserId', 'Tanggal', 'Sumber', 'Status', 'Kategori', 'Jumlah', 'Keterangan'];
        $rows = [
            ['456789356', 'Sep 8, 2024, 03:13pm', 'Kas Tunai', 'Keluar', 'Aset', '+15.000,00', 'Pembelian Rak Sepatu'],
            ['456789356', 'Sep 7, 2024, 01:00pm', 'Kas Tunai', 'Keluar', 'Inventaris', '-3.456,00', 'Cat & Sapu untuk Kelas 6D'],
            ['456789356', 'Sep 6, 2024, 07:00am', 'Bank BCA - Arifin', 'Pemasukan', 'Donasi', '+30.000,00', 'Katering Maulid Nabi'],
        ];

        // ✅ Kembalikan view dengan data $cards
        return view('pages.notulensi', compact('cards', 'columns', 'rows'));
    }
}
