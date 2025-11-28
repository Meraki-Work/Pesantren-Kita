<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFooters extends Model
{
    protected $table = 'landing_footers';

    protected $fillable = [
        'logo',
        'instagram',
        'whatsapp',
        'alamat',
        'copyright'
    ];

    public $timestamps = false; 
}
