<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventaris extends Model
{
    protected $table = 'inventaris';
    protected $primaryKey = 'id_inventaris';
    public $timestamps = false;

    protected $fillable = [
        'ponpes_id',
        'nama_barang',
        'kategori',
        'kondisi',
        'jumlah',
        'lokasi',
        'tanggal_beli',
        'keterangan',
        'created_at'
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'created_at' => 'datetime',
        'jumlah' => 'integer'
    ];

    // Relationship dengan ponpes (jika ada)
    public function ponpes()
    {
        return $this->belongsTo(Ponpe::class, 'ponpes_id');
    }

    // Scope untuk kondisi barang
    public function scopeBaik($query)
    {
        return $query->where('kondisi', 'Baik');
    }

    public function scopeRusak($query)
    {
        return $query->where('kondisi', 'Rusak');
    }

    public function scopeHilang($query)
    {
        return $query->where('kondisi', 'Hilang');
    }

    // Scope untuk kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Accessor untuk status berdasarkan kondisi
    public function getStatusAttribute()
    {
        return match($this->kondisi) {
            'Baik' => 'success',
            'Rusak' => 'warning',
            'Hilang' => 'danger',
            default => 'secondary'
        };
    }

    // Accessor untuk format tanggal
    public function getTanggalBeliFormattedAttribute()
    {
        return $this->tanggal_beli 
            ? \Carbon\Carbon::parse($this->tanggal_beli)->format('d M Y')
            : '-';
    }
}