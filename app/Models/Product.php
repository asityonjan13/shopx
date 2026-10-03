<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'price',
        'compare_price',
        'stock',
        'short_description',
        'description',
        'is_active',
        'is_featured',
        'is_flash_sale',
        'flash_price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'flash_price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_flash_sale' => 'boolean',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function sellingPrice(): float
    {
        if ($this->is_flash_sale && $this->flash_price !== null) {
            return (float) $this->flash_price;
        }

        return (float) $this->price;
    }
}
