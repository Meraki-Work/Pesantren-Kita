// app/Models/UserActivity.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities'; // Sesuaikan dengan nama tabel yang benar
    
    protected $fillable = [
        'user_id',
        'ponpes_id',
        'action',
        'feature',
        'value',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke ponpes
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }
}