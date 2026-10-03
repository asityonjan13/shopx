<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => Media::url($this->avatar),
            'user_type' => $this->user_type,
            'email_verified_at' => $this->email_verified_at,
            'kyc_status' => $this->relationLoaded('kyc') ? $this->kyc?->status : $this->kyc?->status,
            'store' => StoreResource::make($this->whenLoaded('store')),
        ];
    }
}
