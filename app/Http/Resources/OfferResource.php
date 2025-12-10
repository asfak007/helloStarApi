<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
          return [
            'id'                     => $this->id,
            'title'                  => $this->title,
            'description'            => $this->description,
            'discount_amount'        => $this->discount_amount,
            'discount_percentage'    => $this->discount_percentage,
            'start_date'             => $this->start_date,
            'end_date'               => $this->end_date,
            'is_active'              => $this->is_active,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'coupons' => CouponResource::collection($this->whenLoaded('coupons')),
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}
