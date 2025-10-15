<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'santri';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id_santri';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alamat',
        'jenis_kelamin',
        'nama',
        'nama_ayah',
        'nama_ibu',
        'nisin',
        'no_induk_gp',
        'status',
        'tahun_masuk',
        'tanggal_lahir',
        'tempat_lahir',
        'tingkat'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the absensi for this model.
     *
     * @return App\Models\Absensi
     */
    public function absensi()
    {
        return $this->hasOne('App\Models\Absensi', 'santri_id', 'id_santri');
    }

    /**
     * Get the kela for this model.
     *
     * @return App\Models\Kela
     */
    public function kela()
    {
        return $this->hasOne('App\Models\Kela', 'santri_id', 'id_santri');
    }

    /**
     * Get the keuangan for this model.
     *
     * @return App\Models\Keuangan
     */
    public function keuangan()
    {
        return $this->hasOne('App\Models\Keuangan', 'santri_id', 'id_santri');
    }

    /**
     * Get the pencapaian for this model.
     *
     * @return App\Models\Pencapaian
     */
    public function pencapaian()
    {
        return $this->hasOne('App\Models\Pencapaian', 'santri_id', 'id_santri');
    }

    /**
     * Get the sanksi for this model.
     *
     * @return App\Models\Sanksi
     */
    public function sanksi()
    {
        return $this->hasOne('App\Models\Sanksi', 'santri_id', 'id_santri');
    }

    /**
     * Set the tanggal_lahir.
     *
     * @param  string  $value
     * @return void
     */
    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }

    /**
     * Get tanggal_lahir in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getTanggalLahirAttribute($value)
    {
        if (empty($value) || $value === '0000-00-00') {
            return null; // atau bisa return string kosong ''
        }

        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }
}
