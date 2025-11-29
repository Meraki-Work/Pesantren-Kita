<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFooters extends Model
{
    protected $table = 'landing_footers';

    protected $fillable = [
        'ponpes_id',
        'logo',
        'instagram',
        'whatsapp',
        'alamat',
        'copyright'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    public $timestamps = false; 
}
