<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => CategoryResource::tree(Category::getNested()),
        ]);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $this->assertDepth($request->input('parent_id'));

        $category = Category::create([
            'name' => $request->string('name')->toString(),
            'slug' => Slug::unique(Category::class, $request->input('slug') ?: $request->string('name')->toString()),
            'parent_id' => $request->input('parent_id'),
            'is_active' => $request->boolean('is_active', true),
            'position' => (int) Category::where('parent_id', $request->input('parent_id'))->max('position') + 1,
        ]);

        return response()->json([
            'message' => 'Category created.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $parentId = $request->input('parent_id');

        if ($parentId && (int) $parentId === $category->id) {
            return response()->json(['message' => 'A category cannot be its own parent.'], 422);
        }

        if ($parentId && $this->isDescendant($category->id, (int) $parentId)) {
            return response()->json(['message' => 'Cannot move a category under its own child.'], 422);
        }

        $this->assertDepth($parentId);

        $category->update([
            'name' => $request->string('name')->toString(),
            'slug' => Slug::unique(Category::class, $request->input('slug') ?: $request->string('name')->toString(), $category->id),
            'parent_id' => $parentId,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message' => 'Category updated.',
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'Delete the child categories first.',
            ], 422);
        }

        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'This category still has products.',
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }

    public function reorder(Request $request): JsonResponse
    {
        $order = $request->input('order');
        if (is_string($order)) {
            $order = json_decode($order, true);
        }

        if (! is_array($order)) {
            return response()->json(['message' => 'Invalid order data.'], 422);
        }

        $this->updateOrder($order, null);

        return response()->json(['message' => 'Categories reordered.']);
    }

    private function updateOrder(array $items, ?int $parentId): void
    {
        foreach (array_values($items) as $index => $item) {
            if (! isset($item['id'])) {
                continue;
            }

            Category::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'position' => $index,
            ]);

            if (! empty($item['children']) && is_array($item['children'])) {
                $this->updateOrder($item['children'], (int) $item['id']);
            }
        }
    }

    private function assertDepth(mixed $parentId): void
    {
        if (! $parentId) {
            return;
        }

        $parent = Category::find($parentId);
        $depth = 1;

        while ($parent && $parent->parent_id) {
            $depth++;
            $parent = $parent->parent;
            if ($depth >= 3) {
                break;
            }
        }

        if ($depth >= 3) {
            abort(422, 'Categories can only be nested three levels deep.');
        }
    }

    private function isDescendant(int $categoryId, int $targetId): bool
    {
        $target = Category::find($targetId);

        while ($target && $target->parent_id) {
            if ((int) $target->parent_id === $categoryId) {
                return true;
            }
            $target = $target->parent;
        }

        return false;
    }
}
