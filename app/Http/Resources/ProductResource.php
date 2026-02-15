<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'seller_id' => $this->seller_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'condition_id' => $this->condition_id,
            'audience' => $this->audience,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'brand' => $this->brand,
            'size_id' => $this->size_id,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
