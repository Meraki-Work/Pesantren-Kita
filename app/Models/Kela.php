<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kela
 * 
 * @property int $id_kelas
 * @property string|null $ponpes_id
 * @property string $nama_kelas
 * @property string $tingkat
 * 
 * @property Ponpe|null $ponpe
 * @property Collection|Santri[] $santris
 *
 * @package App\Models
 */
class Kela extends Model
{
	protected $table = 'kelas';
	protected $primaryKey = 'id_kelas';
	public $timestamps = false;

	protected $fillable = [
		'ponpes_id',
		'nama_kelas',
		'tingkat'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function santris()
	{
		return $this->hasMany(Santri::class, 'id_kelas');
	}
}
