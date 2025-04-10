<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public function sellerSubscriptions(){
        return $this->hasMany(SellerSubscription::class, 'plan_id');
    }
}
