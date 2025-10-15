<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
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
    protected $table = 'sanksi';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_sanksi';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'deskripsi',
                  'hukuman',
                  'jenis',
                  'santri_id',
                  'status',
                  'tanggal',
                  'user_id'
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
     * Get the User for this model.
     *
     * @return App\Models\User
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','id_user');
    }

    /**
     * Set the tanggal.
     *
     * @param  string  $value
     * @return void
     */
    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
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

    /**
     * Get tanggal in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getTanggalAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

}
