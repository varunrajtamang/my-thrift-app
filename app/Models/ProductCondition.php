<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCondition extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class,'condition_id');
    }
}
