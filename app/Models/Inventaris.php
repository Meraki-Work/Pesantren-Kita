<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';
    protected $primaryKey = 'id_inventaris';
    
    protected $fillable = [
        'ponpes_id',
        'nama_barang',
        'kategori',
        'kondisi',
        'jumlah',
        'lokasi',
        'tanggal_beli',
        'keterangan'
    ];

    public $timestamps = false;

    // Relasi dengan ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }

    // Accessor untuk kondisi dengan badge color
    public function getKondisiBadgeAttribute()
    {
        $colors = [
            'Baik' => 'success',
            'Rusak' => 'danger',
            'Hilang' => 'warning'
        ];

        return $colors[$this->kondisi] ?? 'secondary';
    }

    // Accessor untuk format tanggal
    public function getTanggalBeliFormattedAttribute()
    {
        return $this->tanggal_beli 
            ? \Carbon\Carbon::parse($this->tanggal_beli)->format('d M Y')
            : '-';
    }

    // Scope untuk barang dengan kondisi tertentu
    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    // Scope untuk kategori tertentu
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}