<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
class User extends Model
{
	protected $table = 'user';
	protected $primaryKey = 'id_user';
	public $timestamps = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'ponpes_id',
		'username',
		'password',
		'email',
		'role'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
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
