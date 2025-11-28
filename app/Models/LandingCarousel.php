<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingCarousel extends Model {
    protected $table = 'landing_carousels';
    protected $fillable = [
        'image',
        'title',
        'subtitle'
    ];
}
