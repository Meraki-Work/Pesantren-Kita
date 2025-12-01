<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'status',
        'ponpes_id',
        'otp_code',
        'otp_expired_at',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'otp_code',
    ];

    protected $casts = [
        'otp_expired_at' => 'datetime',
    ];
}
