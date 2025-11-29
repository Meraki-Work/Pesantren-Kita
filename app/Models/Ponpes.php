<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'ponpes';
    protected $primaryKey = 'id_ponpes';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; 

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

    public function carousels()
    {
        return $this->hasMany(LandingCarousel::class, 'ponpes_id', 'id_ponpes');
    }

    public function galleries()
    {
        return $this->hasMany(LandingGalleries::class, 'ponpes_id', 'id_ponpes');
    }

    public function footers()
    {
        return $this->hasMany(LandingFooters::class, 'ponpes_id', 'id_ponpes');
    }

    public function abouts()
    {
        return $this->hasMany(LandingAbouts::class, 'ponpes_id', 'id_ponpes');
    }
}
