<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function plan(){
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    
    }
    public function transaction(){
        return $this->hasMany(Transaction::class, 'subscription_id');
    
    }

}
