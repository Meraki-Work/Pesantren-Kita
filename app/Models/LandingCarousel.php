<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingCarousel extends Model {
    protected $table = 'landing_carousels';
    protected $fillable = [
        'ponpes_id',
        'image',
        'title',
        'subtitle'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }
}
