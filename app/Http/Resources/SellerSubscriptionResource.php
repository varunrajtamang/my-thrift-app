<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerSubscriptionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'plan_name' => $this->plan?->name,
            'plan' => $this->plan ? [
                'id' => $this->plan->id,
                'name' => $this->plan->name,
                'price' => $this->plan->price,
                'duration_type' => $this->plan->duration_type,
                'duration_value' => $this->plan->duration_value,
            ] : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'max_listings' => $this->max_listings,
            'remaining_listings' => $this->remaining_listings,
            'listings_used' => $this->max_listings !== null && $this->remaining_listings !== null
                ? max($this->max_listings - $this->remaining_listings, 0)
                : null,
            'is_auto_renew' => $this->is_auto_renew,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
