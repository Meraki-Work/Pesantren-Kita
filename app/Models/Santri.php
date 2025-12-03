<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santri';
    protected $primaryKey = 'id_santri';
    
    protected $fillable = [
        'ponpes_id', 'id_kelas', 'nama', 'nisn', 'nik',
        'status_ujian', 'tahun_masuk', 'alamat',
        'jenis_kelamin', 'tanggal_lahir', 'nama_ayah', 'nama_ibu'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_santri');
    }

    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'id_santri');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_santri');
    }
}