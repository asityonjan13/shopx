<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Display the category management page
    public function index(): View
    {
        return view('admin.category.index');
    }

    // Create a new category
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean']
        ]);

        // Prevent circular reference and max depth
        if ($data['parent_id'] ?? null) {
            $parent = Category::find($data['parent_id']);
            $depth = 1;

            while ($parent && $parent->parent_id) {
                $depth++;
                $parent = $parent->parent;
                if ($depth >= 3) break;
            }

            if ($depth >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum category depth is 3 levels.'
                ], 422);
            }
        }

        // Set position for new category
        $data['position'] = Category::where('parent_id', $data['parent_id'] ?? null)
            ->max('position') + 1;

        $category = Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    // Get all categories in nested structure for tree view
    public function getNestedCategories(): JsonResponse
    {
        $categories = Category::getNested();
        return response()->json($categories);
    }

    // Get single category data for editing
    public function edit($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Update category order after drag and drop
    public function updateOrder(Request $request)
    {
        try {
            $order = json_decode($request->input('order'), true);

            if (!$order) {
                return response()->json(['message' => 'Invalid order data'], 400);
            }

            $this->updateOrderRecursive($order, null, 0);

            return response()->json(['message' => 'Categories reordered successfully']);
        } catch (\Exception $e) {
            \Log::error('Category reorder error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to reorder categories: ' . $e->getMessage()], 500);
        }
    }

    // Recursively update category positions and parent relationships
    private function updateOrderRecursive($items, $parentId = null, $position = 0)
    {
        foreach ($items as $index => $item) {
            Category::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'position' => $position + $index
            ]);

            if (isset($item['children']) && is_array($item['children']) && count($item['children']) > 0) {
                $this->updateOrderRecursive($item['children'], $item['id'], 0);
            }
        }
    }

    // Update an existing category
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $id],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean']
        ]);

        // Prevent self-reference
        if (($data['parent_id'] ?? null) == $id) {
            return response()->json([
                'success' => false,
                'message' => 'A category cannot be its own parent.'
            ], 422);
        }

        // Prevent circular reference and max depth
        if ($data['parent_id'] ?? null) {
            // Check if new parent is a descendant of this category
            if ($this->isDescendant($id, $data['parent_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot move category under its own descendant.'
                ], 422);
            }

            // Check depth - the parent can be at depth 2 maximum (so this category will be at depth 3)
            $parent = Category::find($data['parent_id']);
            $depth = 1;

            while ($parent && $parent->parent_id) {
                $depth++;
                $parent = $parent->parent;
                if ($depth >= 3) break; // Changed from 2 to 3
            }

            if ($depth >= 3) { // Changed from 2 to 3
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum category depth is 3 levels.'
                ], 422);
            }
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    // Delete a category
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);

        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with children. Delete child categories first.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    // Check if target category is a descendant of the given category
    private function isDescendant($categoryId, $targetId): bool
    {
        $target = Category::find($targetId);

        while ($target && $target->parent_id) {
            if ($target->parent_id == $categoryId) {
                return true;
            }
            $target = $target->parent;
        }

        return false;
    }
}
