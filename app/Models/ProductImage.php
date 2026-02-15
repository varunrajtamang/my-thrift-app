<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'is_primary',
        'image_url',
    ];

    use HasFactory;
    protected $table = 'product_image';
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
