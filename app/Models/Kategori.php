<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
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
    protected $table = 'kategori';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id_kategori';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'nama_kategori'
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
     * Get the keuangan for this model.
     *
     * @return App\Models\Keuangan
     */
    public function keuangan()
    {
        return $this->hasOne('App\Models\Keuangan','kategori_id','id_kategori');
    }



}
