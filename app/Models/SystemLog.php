<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;
    
    // Tentukan nama tabel yang benar
    protected $table = 'absensi_system_logs';
    
    protected $fillable = [
        'user_id',
        'username',
        'role',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'data',
        'created_at'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}