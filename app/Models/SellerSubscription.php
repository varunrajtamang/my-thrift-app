<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'max_listings',
        'remaining_listings',
        'is_auto_renew',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'subscription_id');
    }
}
