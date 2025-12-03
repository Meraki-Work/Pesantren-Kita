<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    
    protected $fillable = [
        'ponpes_id', 'nama_kategori'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_kategori');
    }
}