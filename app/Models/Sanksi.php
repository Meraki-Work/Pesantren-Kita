<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sanksi
 * 
 * @property int $id_sanksi
 * @property string|null $ponpes_id
 * @property int|null $user_id
 * @property int|null $id_santri
 * @property string|null $jenis
 * @property string|null $deskripsi
 * @property string|null $hukuman
 * @property Carbon|null $tanggal
 * @property string|null $status
 * 
 * @property Ponpe|null $ponpe
 * @property User|null $user
 * @property Santri|null $santri
 *
 * @package App\Models
 */
class Sanksi extends Model
{
	protected $table = 'sanksi';
	protected $primaryKey = 'id_sanksi';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'id_santri' => 'int',
		'tanggal' => 'datetime'
	];

	protected $fillable = [
		'ponpes_id',
		'user_id',
		'id_santri',
		'jenis',
		'deskripsi',
		'hukuman',
		'tanggal',
		'status'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function santri()
	{
		return $this->belongsTo(Santri::class, 'id_santri');
	}
}
