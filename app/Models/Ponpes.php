<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'ponpes';
    protected $primaryKey = 'id_ponpes';
    public $timestamps = false; // karena created_at tidak otomatis pakai updated_at

    protected $fillable = [
        'nama',
        'alamat',
        'tahun_berdiri',
        'pendiri',
        'fasilitas',
        'jumlah_staf',
        'jumlah_santri',
        'logo_ponpes',
        'gambar',
        'pimpinan',
        'jumlah_ustad',
        'status',
        'created_at',
    ];

    // Relasi: satu pondok punya banyak user
    public function users()
    {
        return $this->hasMany(User::class, 'ponpes_id', 'id_ponpes');
    }
}
