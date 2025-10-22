<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ponpe
 * 
 * @property string $id_ponpes
 * @property string $nama_ponpes
 * @property string|null $alamat
 * @property Carbon|null $tahun_berdiri
 * @property string|null $telp
 * @property string|null $email
 * @property string|null $logo_ponpes
 * @property int|null $jumlah_santri
 * @property int|null $jumlah_staf
 * @property string|null $pimpinan
 * @property string|null $status
 * 
 * @property Collection|Absensi[] $absensis
 * @property Collection|Gambar[] $gambars
 * @property Collection|Inventari[] $inventaris
 * @property Collection|Kategori[] $kategoris
 * @property Collection|Kela[] $kelas
 * @property Collection|Keuangan[] $keuangans
 * @property Collection|Pencapaian[] $pencapaians
 * @property Collection|Sanksi[] $sanksis
 * @property Collection|Santri[] $santris
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Ponpe extends Model
{
	protected $table = 'ponpes';
	protected $primaryKey = 'id_ponpes';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tahun_berdiri' => 'datetime',
		'jumlah_santri' => 'int',
		'jumlah_staf' => 'int'
	];

	protected $fillable = [
		'nama_ponpes',
		'alamat',
		'tahun_berdiri',
		'telp',
		'email',
		'logo_ponpes',
		'jumlah_santri',
		'jumlah_staf',
		'pimpinan',
		'status'
	];

	public function absensis()
	{
		return $this->hasMany(Absensi::class, 'ponpes_id');
	}

	public function gambars()
	{
		return $this->hasMany(Gambar::class, 'ponpes_id');
	}

	public function inventaris()
	{
		return $this->hasMany(Inventari::class, 'ponpes_id');
	}

	public function kategoris()
	{
		return $this->hasMany(Kategori::class, 'ponpes_id');
	}

	public function kelas()
	{
		return $this->hasMany(Kela::class, 'ponpes_id');
	}

	public function keuangans()
	{
		return $this->hasMany(Keuangan::class, 'ponpes_id');
	}

	public function pencapaians()
	{
		return $this->hasMany(Pencapaian::class, 'ponpes_id');
	}

	public function sanksis()
	{
		return $this->hasMany(Sanksi::class, 'ponpes_id');
	}

	public function santris()
	{
		return $this->hasMany(Santri::class, 'ponpes_id');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'ponpes_id');
	}
}
