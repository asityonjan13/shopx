<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'logo_url' => Media::url($this->logo),
            'banner_url' => Media::url($this->banner),
            'products_count' => $this->whenCounted('products'),
        ];
    }
}
