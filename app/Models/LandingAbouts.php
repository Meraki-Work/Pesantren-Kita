<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingAbouts extends Model
{
    protected $table = 'landing_abouts';

    protected $fillable = [
        'founder_name',
        'founder_position',
        'founder_description',
        'founder_image',
        'leader_name',
        'leader_position',
        'leader_description',
        'leader_image',
    ];

    public $timestamps = false; 
}
