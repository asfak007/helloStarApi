<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'conditions'      => $this->conditions,
             'reason'         => $this->reason,
             'reason_image'   => $this->reason_image_url,
            'amount'          => $this->amount,
            'formatted_amount'=> $this->formatted_amount,
            'provider_amount' => $this->provider_amount,
            'partial_payment' => $this->partial_payment,
            'partial_payment_percentage' => $this->partial_payment_percentage,
            'provider_percentage' => $this->provider_percentage,
            'image'           => $this->image_url,
             'total_booking' => $this->total_bookings,
             'ratings' => $this->rating,
            // RELATIONS

            'processes'      => ServiceProcessResource::collection($this->whenLoaded('processes')),
            'requirements'   => ServiceRequirementResource::collection($this->whenLoaded('requirements')),
            'faqs'           => ServiceFaqResource::collection($this->whenLoaded('faqs')),
        ];
    }
}
