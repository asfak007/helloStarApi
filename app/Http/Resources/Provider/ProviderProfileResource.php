<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class providerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'name'      => $this->name,
            'email'     => $this->email,
            'number'    => $this->number,
            'role'      => $this->role?->name,
            'image'     => $this->image_url,
            'verified_status'  => $this->verified_status,
        ];
    }
}
