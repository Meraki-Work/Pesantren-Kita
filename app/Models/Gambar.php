<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    protected $table = 'gambar';
    protected $primaryKey = 'id_gambar';
    
    protected $fillable = [
        'ponpes_id', 'user_id', 'path_gambar',
        'keterangan', 'is_landing_gallery', 'display_order'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope untuk gambar galeri landing page
    public function scopeLandingGallery($query)
    {
        return $query->where('is_landing_gallery', true);
    }

    // Scope untuk pesantren tertentu
    public function scopeByPonpes($query, $ponpesId)
    {
        return $query->where('ponpes_id', $ponpesId);
    }
}