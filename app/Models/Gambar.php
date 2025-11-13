<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Gambar
 * 
 * @property int $id_gambar
 * @property string|null $ponpes_id
 * @property int|null $user_id
 * @property string|null $path_gambar
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * 
 * @property Ponpe|null $ponpe
 * @property User|null $user
 *
 * @package App\Models
 */
class Gambar extends Model
{
	protected $table = 'gambar';
	protected $primaryKey = 'id_gambar';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'ponpes_id',
		'user_id',
		'path_gambar',
		'keterangan',
		'id_notulen'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function notulen()
	{
		return $this->belongsTo(Notulen::class, 'id_notulen', 'id_notulen');
	}

	public function getImageUrlAttribute()
	{
		return $this->path_gambar ? asset('storage/' . $this->path_gambar) : null;
	}
}
