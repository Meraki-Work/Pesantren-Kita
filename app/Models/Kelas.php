<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    
    protected $fillable = [
        'ponpes_id',
        'nama_kelas',
        'tingkat'
    ];

    public $timestamps = false;

    // Relasi dengan ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }

    // Relasi dengan santri
    public function santri()
    {
        return $this->hasMany(Santri::class, 'id_kelas');
    }

    // Accessor untuk nama kelas lengkap
    public function getNamaLengkapAttribute()
    {
        return "{$this->tingkat} - {$this->nama_kelas}";
    }
}