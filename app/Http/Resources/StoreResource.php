<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo_path' => $this->logo_path,
            'cover_image_path' => $this->cover_image_path,
            'store_location' => $this->store_location,
            'is_active' => $this->is_active,
            'user_id' => $this->user_id,
            'user_type' => $this->user ? $this->user->user_type : null,
            'seller_name' => $this->user ? $this->user->name : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
