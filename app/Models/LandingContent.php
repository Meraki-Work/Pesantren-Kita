<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    protected $table = 'landing_content';

    protected $primaryKey = 'id_content';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'ponpes_id',
        'content_type',
        'title',
        'subtitle',
        'description',
        'image',
        'position',
        'url',
        'display_order',
        'is_active'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    // Scope untuk konten aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk tipe tertentu
    public function scopeType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    // Scope untuk pesantren tertentu
    public function scopeByPonpes($query, $ponpesId)
    {
        return $query->where('ponpes_id', $ponpesId);
    }
}