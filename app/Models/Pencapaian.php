<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencapaian extends Model
{
    protected $table = 'pencapaian';
    protected $primaryKey = 'id_pencapaian';
    
    protected $fillable = [
        'ponpes_id', 'id_santri', 'user_id',
        'judul', 'deskripsi', 'tipe', 'skor', 'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'skor' => 'integer'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}