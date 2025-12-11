<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    protected $table = 'ponpes';
    protected $primaryKey = 'id_ponpes';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_ponpes',
        'nama_ponpes',
        'alamat',
        'tahun_berdiri',
        'telp',
        'email',
        'logo_ponpes',
        'jumlah_santri',
        'jumlah_staf',
        'pimpinan',
        'status'
    ];

    // Relationship dengan Santri
    public function santri()
    {
        return $this->hasMany(Santri::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Keuangan
    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan LandingContent
    public function landingContents()
    {
        return $this->hasMany(LandingContent::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Gambar
    public function gambar()
    {
        return $this->hasMany(Gambar::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Inventaris
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Pencapaian
    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'ponpes_id', 'id_ponpes');
    }

    // Relationship dengan Sanksi
    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'ponpes_id', 'id_ponpes');
    }
}
