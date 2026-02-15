<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo_path',
        'cover_image_path',
        'store_location',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }




}
