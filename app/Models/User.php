<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id_user
 * @property string|null $ponpes_id
 * @property string $username
 * @property string $password
 * @property string|null $email
 * @property string $role
 * @property Carbon|null $created_at
 * 
 * @property Ponpe|null $ponpe
 * @property Collection|Absensi[] $absensis
 * @property Collection|Gambar[] $gambars
 * @property Collection|Keuangan[] $keuangans
 * @property Collection|Pencapaian[] $pencapaians
 * @property Collection|Sanksi[] $sanksis
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $hidden = [
        'password',
        'otp_code',
        'remember_token'
    ];

    protected $fillable = [
        'ponpes_id',
        'username',
        'email',
        'password',
        'role',
        'otp_code',
        'otp_expired_at',
        'email_verified_at',
        'status',
    ];

    protected $casts = [
        'otp_expired_at' => 'datetime',
    ];

    // Jika nama kolom password bukan 'password', tambahkan method ini
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Jika Anda ingin menggunakan kolom 'username' untuk login juga
    public function getAuthIdentifierName()
    {
        return 'email'; // atau 'username' tergantung kebutuhan
    }

    public function ponpe()
    {
        return $this->belongsTo(Ponpe::class, 'ponpes_id');
    }

        public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function gambars()
    {
        return $this->hasMany(Gambar::class);
    }

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class);
    }

    public function pencapaians()
    {
        return $this->hasMany(Pencapaian::class);
    }

    public function sanksis()
    {
        return $this->hasMany(Sanksi::class);
    }
}