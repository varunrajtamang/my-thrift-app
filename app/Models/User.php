<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Your existing relationships
    public function sellerSubscriptions()
    {
        return $this->hasMany(SellerSubscription::class);
    }
    
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
    
    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'seller_id');
    }
    
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }
}