<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notulen extends Model
{
    protected $table = 'notulensi_rapat';
    protected $primaryKey = 'id_notulen';
    public $timestamps = false;

    protected $fillable = [
        'ponpes_id',
        'user_id',
        'agenda',
        'pimpinan',
        'peserta',
        'tempat',
        'alur_rapat',
        'tanggal',
        'waktu',
        'keterangan',
        'hasil',
        'created_at'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i',
        'created_at' => 'datetime'
    ];

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpe::class, 'ponpes_id');
    }

    // Accessor untuk format tanggal lengkap
    public function getTanggalWaktuAttribute()
    {
        $tanggal = $this->tanggal ? $this->tanggal->format('d M Y') : '-';
        $waktu = $this->waktu ? \Carbon\Carbon::parse($this->waktu)->format('H:i') : '-';

        return "{$tanggal} - {$waktu}";
    }

    // Accessor untuk format waktu
    public function getWaktuFormattedAttribute()
    {
        return $this->waktu ? \Carbon\Carbon::parse($this->waktu)->format('H:i') : '-';
    }

    // Scope untuk notulen bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month);
    }

    // Scope untuk notulen minggu ini
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Method untuk cek apakah bisa diedit/dihapus
    public function canEdit()
    {
        return $this->user_id === auth()->id();
    }

    public function gambar()
    {
        return $this->hasMany(Gambar::class, 'id_notulen', 'id_notulen');
    }
}
