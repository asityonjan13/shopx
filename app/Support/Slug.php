<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Slug
{
    /**
     * @param  class-string<Model>  $model
     */
    public static function unique(string $model, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base !== '' ? $base : Str::lower(Str::random(8));
        $suffix = 1;

        while ($model::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
