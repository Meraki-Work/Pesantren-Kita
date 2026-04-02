<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $table = 'subscription_histories';

    protected $fillable = [
        'subscription_id',
        'action',
        'from_plan_id',
        'to_plan_id',
        'note',
        'created_at'
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime'
    ];

    // Action constants
    const ACTION_CREATED = 'created';
    const ACTION_RENEWED = 'renewed';
    const ACTION_UPGRADED = 'upgraded';
    const ACTION_DOWNGRADED = 'downgraded';
    const ACTION_CANCELED = 'canceled';
    const ACTION_EXPIRED = 'expired';

    /**
     * Get the subscription that owns the history
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Get the from plan
     */
    public function fromPlan()
    {
        return $this->belongsTo(Plan::class, 'from_plan_id');
    }

    /**
     * Get the to plan
     */
    public function toPlan()
    {
        return $this->belongsTo(Plan::class, 'to_plan_id');
    }
}