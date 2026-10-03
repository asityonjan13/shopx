<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'children' => self::tree($this->children_nested ?? collect()),
        ];
    }

    public static function tree(Collection $categories): array
    {
        return $categories->map(fn ($category) => [
            'id' => $category->id,
            'parent_id' => $category->parent_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'position' => $category->position,
            'is_active' => (bool) $category->is_active,
            'children' => self::tree($category->children_nested ?? collect()),
        ])->values()->all();
    }
}
