<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
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
    protected $table = 'user';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_user';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'email',
                  'password',
                  'ponpes_id',
                  'role',
                  'username'
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
     * Get the Ponpe for this model.
     *
     * @return App\Models\Ponpe
     */
    public function Ponpe()
    {
        return $this->belongsTo('App\Models\Ponpe','ponpes_id','id_ponpes');
    }

    /**
     * Get the absensi for this model.
     *
     * @return App\Models\Absensi
     */
    public function absensi()
    {
        return $this->hasOne('App\Models\Absensi','user_id','id_user');
    }

    /**
     * Get the inventari for this model.
     *
     * @return App\Models\Inventari
     */
    public function inventari()
    {
        return $this->hasOne('App\Models\Inventari','user_id','id_user');
    }

    /**
     * Get the keuangan for this model.
     *
     * @return App\Models\Keuangan
     */
    public function keuangan()
    {
        return $this->hasOne('App\Models\Keuangan','user_id','id_user');
    }

    /**
     * Get the notulensiRapat for this model.
     *
     * @return App\Models\Notulensi
     */
    public function notulensiRapat()
    {
        return $this->hasOne('App\Models\Notulensi','user_id','id_user');
    }

    /**
     * Get the sanksi for this model.
     *
     * @return App\Models\Sanksi
     */
    public function sanksi()
    {
        return $this->hasOne('App\Models\Sanksi','user_id','id_user');
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
