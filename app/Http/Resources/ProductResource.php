<?php

namespace App\Http\Resources;

use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $primary = $this->relationLoaded('images')
            ? ($this->images->firstWhere('is_primary', true) ?? $this->images->first())
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'compare_price' => $this->compare_price !== null ? (float) $this->compare_price : null,
            'flash_price' => $this->flash_price !== null ? (float) $this->flash_price : null,
            'selling_price' => $this->sellingPrice(),
            'stock' => $this->stock,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_flash_sale' => $this->is_flash_sale,
            'image_url' => $primary ? Media::url($primary->path) : null,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => [
                'id' => $image->id,
                'url' => Media::url($image->path),
                'is_primary' => $image->is_primary,
                'position' => $image->position,
            ])->values()),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'store' => StoreResource::make($this->whenLoaded('store')),
        ];
    }
}
