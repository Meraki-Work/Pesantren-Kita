<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Keuangan extends Model
{
    public $timestamps = false;

    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';

    protected $fillable = [
        'jumlah',
        'kategori_id',
        'keterangan',
        'santri_id',
        'status',
        'sumber_dana',
        'tanggal',
        'user_id'
    ];

    /**
     * Relasi ke tabel kategori
     * keuangan.kategori_id → kategori.id_kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    /**
     * Relasi ke tabel santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'id_santri');
    }

    /**
     * Relasi ke tabel user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Accessor untuk tanggal (format d/m/Y)
     */
    public function getTanggalAttribute($value)
    {
        if (empty($value) || $value === '0000-00-00') {
            return null;
        }

        return Carbon::parse($value)->format('d/m/Y');
    }
}
