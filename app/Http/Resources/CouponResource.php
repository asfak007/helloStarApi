<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'code'        => $this->code,
            'max_uses'    => $this->max_uses,
            'used_count'  => $this->used_count,
            'valid_from'  => $this->valid_from,
            'valid_until' => $this->valid_until,
            'is_active'   => $this->is_active,

            'offer' => new OfferResource($this->whenLoaded('offer')),

            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
