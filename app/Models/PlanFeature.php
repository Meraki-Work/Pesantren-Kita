<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';
    
    public $timestamps = false;
    
    protected $fillable = [
        'plan_id',
        'feature_key',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    /**
     * Get the plan that owns the feature
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}