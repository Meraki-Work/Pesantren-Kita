<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';
    
    protected $fillable = [
        'id_santri', 'ponpes_id', 'user_id', 'id_kategori',
        'sumber_dana', 'keterangan', 'jumlah', 'status', 'tanggal'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}