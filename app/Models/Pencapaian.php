<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencapaian extends Model
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
    protected $table = 'pencapaian';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_pencapaian';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'jumlah_kompetensi',
                  'keterangan',
                  'santri_id',
                  'semester',
                  'status',
                  'target_kompetensi',
                  'total_mengulang_hafalan'
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
     * Get the Santri for this model.
     *
     * @return App\Models\Santri
     */
    public function Santri()
    {
        return $this->belongsTo('App\Models\Santri','santri_id','id_santri');
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
