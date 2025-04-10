<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function seller()
    {
        return $this->belongsTo(User::class,'seller_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);

    }
    public function condition()
    {
        return $this->belongsTo(ProductCondition::class ,'condition_id');

    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function images()
    {
        return $this->belongsTo(ProductImage::class);
    }
    public function orderItems()
    {
        return $this->belongsTo(OrderItem::class);
    }
    public function cartItems()
    {
        return $this->belongsTo(cartItem::class);
    }
    public function reviews()
    {
        return $this->belongsTo(Review::class);
    }
    public function Message()
    {
        return $this->belongsTo(Message::class);
    }
    public function wishlist()
    {
        return $this->belongsTo(Wishlist_item::class);
    }
}
