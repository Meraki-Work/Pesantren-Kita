<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Plan;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    
    protected $fillable = [
        'ponpes_id',
        'plan_id',
        'status',
        'billing_cycle',
        'start_date',
        'current_period_end',
        'auto_renew',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'current_period_end' => 'date',
        'auto_renew' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_TRIAL = 'trial';
    const STATUS_ACTIVE = 'active';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_CANCELED = 'canceled';
    const STATUS_EXPIRED = 'expired';

    // Billing cycle constants
    const CYCLE_MONTHLY = 'monthly';
    const CYCLE_YEARLY = 'yearly';

    /**
     * Get the pondok pesantren that owns the subscription
     */
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id', 'id_ponpes');
    }

    /**
     * Get the plan associated with the subscription
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Get the history logs for the subscription
     */
    public function histories()
    {
        return $this->hasMany(SubscriptionHistory::class, 'subscription_id');
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->current_period_end >= Carbon::today();
    }

    /**
     * Check if subscription is on trial
     */
    public function isTrial()
    {
        return $this->status === self::STATUS_TRIAL;
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired()
    {
        return $this->current_period_end < Carbon::today() || 
               $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Get days remaining in current period
     */
    public function daysRemaining()
    {
        if ($this->current_period_end < Carbon::today()) {
            return 0;
        }
        return Carbon::today()->diffInDays($this->current_period_end);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('current_period_end', '>=', Carbon::today());
    }

    /**
     * Scope for expiring soon (within 7 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('current_period_end', '<=', Carbon::today()->addDays(7))
                     ->where('current_period_end', '>=', Carbon::today());
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED)
                     ->orWhere('current_period_end', '<', Carbon::today());
    }
}