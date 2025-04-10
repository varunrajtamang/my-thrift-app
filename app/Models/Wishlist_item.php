<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist_item extends Model
{
    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
