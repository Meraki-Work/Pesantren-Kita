<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Santri
 * 
 * @property int $id_santri
 * @property string|null $ponpes_id
 * @property int|null $id_kelas
 * @property string|null $nama
 * @property string|null $nisn
 * @property string|null $nik
 * @property string|null $status_ujian
 * @property Carbon|null $tahun_masuk
 * @property string|null $alamat
 * @property string|null $jenis_kelamin
 * @property Carbon|null $tanggal_lahir
 * @property string|null $nama_ayah
 * @property string|null $nama_ibu
 * 
 * @property Ponpe|null $ponpe
 * @property Kela|null $kela
 * @property Collection|Absensi[] $absensis
 * @property Collection|Keuangan[] $keuangans
 * @property Collection|Pencapaian[] $pencapaians
 * @property Collection|Sanksi[] $sanksis
 *
 * @package App\Models
 */
class Santri extends Model
{
	protected $table = 'santri';
	protected $primaryKey = 'id_santri';
	public $timestamps = false;

	protected $casts = [
		'id_kelas' => 'int',
		'tanggal_lahir' => 'datetime'
	];

	protected $fillable = [
		'ponpes_id',
		'id_kelas',
		'nama',
		'nisn',
		'nik',
		'status_ujian',
		'tahun_masuk',
		'alamat',
		'jenis_kelamin',
		'tanggal_lahir',
		'nama_ayah',
		'nama_ibu'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function kela()
	{
		return $this->belongsTo(Kela::class, 'id_kelas');
	}

	public function absensis()
	{
		return $this->hasMany(Absensi::class, 'id_santri');
	}

	public function keuangans()
	{
		return $this->hasMany(Keuangan::class, 'id_santri');
	}

	public function pencapaians()
	{
		return $this->hasMany(Pencapaian::class, 'id_santri');
	}

	public function sanksis()
	{
		return $this->hasMany(Sanksi::class, 'id_santri');
	}
}
