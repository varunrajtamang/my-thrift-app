<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model


{
    protected $fillable = [
        'buyer_id',
        'order_date',
        'total_amount',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'order_status',
        'tracking_number',
        'notes',
    ];
    public function buyer()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);

    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
