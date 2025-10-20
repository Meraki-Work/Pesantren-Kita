<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
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
    protected $table = 'kelas';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_kelas';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'nama_kelas',
                  'santri_id'
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



}
