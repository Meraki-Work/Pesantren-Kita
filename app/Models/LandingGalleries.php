<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingGalleries extends Model
{
    use HasFactory;

    protected $table = 'landing_galleries';

    public $timestamps = false;
    protected $fillable = [
        'ponpes_id',
        'image',
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }
}
