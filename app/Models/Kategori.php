<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    
    protected $fillable = [
        'ponpes_id',
        'nama_kategori',
    ];

    // 🔴🔴🔴 NONAKTIFKAN TIMESTAMPS - INI PENYEBAB UTAMA 🔴🔴🔴
    public $timestamps = false;

    // Relasi dengan ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }

    // Relasi dengan keuangan
    public function keuangans()
    {
        return $this->hasMany(Keuangan::class, 'id_kategori');
    }
}