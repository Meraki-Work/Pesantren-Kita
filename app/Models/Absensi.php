<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    
    protected $fillable = [
        'ponpes_id', 'id_santri', 'user_id',
        'tanggal', 'status', 'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
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