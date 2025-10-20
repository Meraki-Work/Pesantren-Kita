<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
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
    protected $table = 'ponpes';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_ponpes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'alamat',
                  'fasilitas',
                  'gambar',
                  'jumlah_santri',
                  'jumlah_staf',
                  'jumlah_ustad',
                  'logo_ponpes',
                  'nama',
                  'pendiri',
                  'pimpinan',
                  'status',
                  'tahun_berdiri'
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
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->hasOne('App\Models\User','ponpes_id','id_ponpes');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

}
