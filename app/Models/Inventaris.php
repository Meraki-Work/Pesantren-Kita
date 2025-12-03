<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';
    protected $primaryKey = 'id_inventaris';
    
    protected $fillable = [
        'ponpes_id', 'nama_barang', 'kategori', 'kondisi',
        'jumlah', 'lokasi', 'tanggal_beli', 'keterangan'
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'jumlah' => 'integer'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }
}