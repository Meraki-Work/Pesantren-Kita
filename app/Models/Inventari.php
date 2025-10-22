<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventari
 * 
 * @property int $id_inventaris
 * @property string|null $ponpes_id
 * @property string|null $nama_barang
 * @property string|null $kategori
 * @property string|null $kondisi
 * @property int|null $jumlah
 * @property string|null $lokasi
 * @property Carbon|null $tanggal_beli
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * 
 * @property Ponpe|null $ponpe
 *
 * @package App\Models
 */
class Inventari extends Model
{
	protected $table = 'inventaris';
	protected $primaryKey = 'id_inventaris';
	public $timestamps = false;

	protected $casts = [
		'jumlah' => 'int',
		'tanggal_beli' => 'datetime'
	];

	protected $fillable = [
		'ponpes_id',
		'nama_barang',
		'kategori',
		'kondisi',
		'jumlah',
		'lokasi',
		'tanggal_beli',
		'keterangan'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}
}
