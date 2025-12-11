<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ponpes_id',
        'user_id',
        'tanggal',
        'status',
        'jam',
        'keterangan'
    ];
}
