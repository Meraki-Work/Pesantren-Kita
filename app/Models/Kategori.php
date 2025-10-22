<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kategori
 * 
 * @property int $id_kategori
 * @property string $ponpes_id
 * @property string|null $nama_kategori
 * @property Carbon|null $created_at
 * 
 * @property Ponpe $ponpe
 * @property Collection|Keuangan[] $keuangans
 *
 * @package App\Models
 */
class Kategori extends Model
{
	protected $table = 'kategori';
	protected $primaryKey = 'id_kategori';
	public $timestamps = false;

	protected $fillable = [
		'ponpes_id',
		'nama_kategori'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function keuangans()
	{
		return $this->hasMany(Keuangan::class, 'id_kategori');
	}
}
