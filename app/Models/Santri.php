<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';
    protected $primaryKey = 'id_santri';
    
    protected $fillable = [
        'ponpes_id',
        'nama',
        'nisn',
        'nik',
        'id_kelas',
        'tahun_masuk',
        'jenis_kelamin',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'alamat',
        'status_ujian'
    ];

    public $timestamps = false;

    protected $casts = [
        'tahun_masuk' => 'integer',
        'tanggal_lahir' => 'date',
    ];

    // 🔥 PERBAIKAN: Relasi yang benar adalah 'kelas' bukan 'kela'
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Relasi dengan ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }

    // Relasi dengan pencapaian
    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'id_santri');
    }

    // Accessor untuk usia
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir 
            ? \Carbon\Carbon::parse($this->tanggal_lahir)->age
            : null;
    }

    // Accessor untuk status ujian dengan badge color
    public function getStatusUjianBadgeAttribute()
    {
        $colors = [
            'Lulus' => 'success',
            'Belum Lulus' => 'warning'
        ];

        return $colors[$this->status_ujian] ?? 'secondary';
    }
}