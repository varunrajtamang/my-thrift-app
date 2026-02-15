<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{

    protected $fillable= [
        'name',
        'price',
        'duration_type',
        'duration_value',
        'max_listings',
        'max_images_per_listing',
        'featured_listings',
        'description',
        'is_active'
    ];
    public function sellerSubscriptions(){
        return $this->hasMany(SellerSubscription::class, 'plan_id');
    }
}
