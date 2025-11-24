<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Pencapaian
 * 
 * @property int $id_pencapaian
 * @property string $ponpes_id
 * @property int $id_santri
 * @property int|null $user_id
 * @property string $judul
 * @property string|null $deskripsi
 * @property string|null $tipe
 * @property int|null $skor
 * @property Carbon|null $tanggal
 * @property Carbon|null $created_at
 * 
 * @property Ponpe $ponpe
 * @property Santri $santri
 * @property User|null $user
 *
 * @package App\Models
 */
class Pencapaian extends Model
{
	protected $table = 'pencapaian';
	protected $primaryKey = 'id_pencapaian';
	public $timestamps = false;

	protected $casts = [
		'id_santri' => 'int',
		'user_id' => 'int',
		'skor' => 'int',
		'tanggal' => 'datetime'
	];

	protected $fillable = [
		'ponpes_id',
		'id_santri',
		'user_id',
		'judul',
		'deskripsi',
		'tipe',
		'skor',
		'tanggal'
	];

	public function ponpe()
	{
		return $this->belongsTo(Ponpe::class, 'ponpes_id');
	}

	public function santri()
	{
		return $this->belongsTo(Santri::class, 'id_santri');
	}

	    public function scopeByPonpes(Builder $query, $ponpesId)
    {
        return $query->where('ponpes_id', $ponpesId);
    }

    /**
     * Scope untuk data milik user yang login
     */
    public function scopeOwnedByCurrentUser(Builder $query)
    {
        return $query->where('ponpes_id', auth()->user()->ponpes_id);
    }

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
