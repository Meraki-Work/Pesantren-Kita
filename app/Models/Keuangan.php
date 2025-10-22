<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Keuangan
 * 
 * @property int $id_keuangan
 * @property int|null $id_santri
 * @property string|null $ponpes_id
 * @property int|null $user_id
 * @property int|null $id_kategori
 * @property string|null $sumber_dana
 * @property string|null $keterangan
 * @property float|null $jumlah
 * @property string|null $status
 * @property Carbon|null $tanggal
 * 
 * @property Kategori|null $kategori
 * @property Santri|null $santri
 * @property Ponpe|null $ponpe
 * @property User|null $user
 *
 * @package App\Models
 */
class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';
    public $timestamps = false;

    protected $casts = [
        'id_santri' => 'int',
        'user_id' => 'int',
        'id_kategori' => 'int',
        'jumlah' => 'float',
        'tanggal' => 'datetime'
    ];

    protected $fillable = [
        'id_santri',
        'ponpes_id',
        'user_id',
        'id_kategori',
        'sumber_dana',
        'keterangan',
        'jumlah',
        'status',
        'tanggal'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri');
    }

    public function ponpe()
    {
        return $this->belongsTo(Ponpe::class, 'ponpes_id');
    }

    public function user()
    {
        // Pastikan foreign key dan primary key sesuai
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}