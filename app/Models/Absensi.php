<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Absensi
 * 
 * @property int $id_absensi
 * @property string|null $ponpes_id
 * @property int|null $id_santri
 * @property int|null $user_id
 * @property Carbon|null $tanggal
 * @property string|null $status
 * @property string|null $keterangan
 * 
 * @property Ponpe|null $ponpe
 * @property Santri|null $santri
 * @property User|null $user
 *
 * @package App\Models
 */
class Absensi extends Model
{
	protected $table = 'absensi';
	protected $primaryKey = 'id_absensi';
	public $timestamps = false;

	protected $casts = [
		'id_santri' => 'int',
		'user_id' => 'int',
		'tanggal' => 'datetime'
	];

	protected $fillable = [
		'ponpes_id',
		'user_id',
		'tanggal',
		'status',
		'keterangan'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function santri()
	{
		return $this->belongsTo(Santri::class, 'id_santri');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
