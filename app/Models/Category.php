<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Category extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'position',
        'is_active'
    ];

    // Attribute type casting
    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
        'parent_id' => 'integer',
    ];

    // Relationship: Get the parent category
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship: Get all child categories ordered by position
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('position');
    }

    // Get categories in nested structure with recursive children up to specified depth
    public static function getNested($parentId = null, $depth = 0, int $maxDepth = 4): Collection
    {
        // Stop recursion at max depth to prevent infinite loops
        if ($depth >= $maxDepth) {
            return collect([]);
        }

        // Get categories at current level
        $categories = self::where('parent_id', $parentId)
            ->orderBy('position')
            ->get();

        // Recursively load nested children for each category
        foreach ($categories as $cat) {
            $cat->children_nested = self::getNested($cat->id, $depth + 1, $maxDepth);
        }

        return $categories;
    }
}
