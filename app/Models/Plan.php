<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';
    
    protected $fillable = [
        'slug',
        'name',
        'description',
        'price_month',
        'price_year',
        'limits_json',
        'is_active'
    ];

    protected $casts = [
        'price_month' => 'decimal:2',
        'price_year' => 'decimal:2',
        'limits_json' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    /**
     * Get plan features
     */
    public function features()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get price based on billing cycle
     */
    public function getPrice($cycle = 'monthly')
    {
        return $cycle === 'yearly' ? $this->price_year : $this->price_month;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice($cycle = 'monthly')
    {
        $price = $this->getPrice($cycle);
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Check if plan has specific feature
     */
    public function hasFeature($featureKey)
    {
        if ($this->limits_json && isset($this->limits_json[$featureKey])) {
            return $this->limits_json[$featureKey];
        }
        
        $feature = $this->features()->where('feature_key', $featureKey)->first();
        return $feature ? (bool)$feature->enabled : false;
    }

    /**
     * Get limit value
     */
    public function getLimit($key, $default = null)
    {
        if ($this->limits_json && isset($this->limits_json[$key])) {
            return $this->limits_json[$key];
        }
        return $default;
    }
}