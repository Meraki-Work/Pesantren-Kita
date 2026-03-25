<?php

namespace App\Exports;

use App\Models\Keuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class KeuanganExport implements FromCollection, WithHeadings
{
    protected $ponpesId;
    protected $filter;

    public function __construct($ponpesId, $filter)
    {
        $this->ponpesId = $ponpesId;
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Keuangan::with(['kategori', 'user'])
            ->where('ponpes_id', $this->ponpesId);

        // Filter tanggal sesuai pilihan user
        $now = Carbon::now();
        switch ($this->filter) {
            case 'hari-ini':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'minggu-ini':
                $startDate = $now->copy()->startOfWeek();
                break;
            case '1-bulan':
                $startDate = $now->copy()->subMonth();
                break;
            case '3-bulan':
                $startDate = $now->copy()->subMonths(3);
                break;
            case '6-bulan':
                $startDate = $now->copy()->subMonths(6);
                break;
            case '1-tahun':
                $startDate = $now->copy()->subYear();
                break;
            case '5-tahun':
                $startDate = $now->copy()->subYears(5);
                break;
            default:
                $startDate = $now->copy()->subYear();
        }

        $query->where('tanggal', '>=', $startDate->format('Y-m-d'))
              ->where('tanggal', '<=', $now->format('Y-m-d'));

        return $query->get()->map(function ($item) {
            return [
                'Tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('d M Y') : '-',
                'Jumlah' => $item->jumlah,
                'Status' => $item->status,
                'Kategori' => $item->kategori->nama_kategori ?? '-',
                'Sumber Dana' => $item->sumber_dana ?? '-',
                'Keterangan' => $item->keterangan ?? '-',
                'User' => $item->user->username ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jumlah', 'Status', 'Kategori', 'Sumber Dana', 'Keterangan', 'User'];
    }
}