<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Santri;

class Sanksi extends Model
{
    protected $table = 'sanksi';
    protected $primaryKey = 'id_sanksi';
    
    // 🔥 PERBAIKAN: Nonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'ponpes_id',
        'user_id',
        'id_santri',
        'jenis',
        'deskripsi',
        'hukuman',
        'tanggal',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function ponpe()
    {
        return $this->belongsTo(Ponpe::class, 'ponpes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri');
    }
}