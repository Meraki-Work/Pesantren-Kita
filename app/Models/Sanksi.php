<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
{
    protected $table = 'sanksi';
    protected $primaryKey = 'id_sanksi';
    
    protected $fillable = [
        'ponpes_id', 'user_id', 'id_santri',
        'jenis', 'deskripsi', 'hukuman', 'tanggal', 'status'
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