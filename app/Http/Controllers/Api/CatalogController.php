<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): JsonResponse
    {
        $with = ['images', 'category', 'store'];

        return response()->json([
            'data' => [
                'settings' => Setting::pluck('value', 'key'),
                'categories' => CategoryResource::tree(Category::publicTree()),
                'featured' => ProductResource::collection(
                    Product::query()->with($with)->where('is_active', true)->where('is_featured', true)->latest()->take(8)->get()
                )->resolve(),
                'flash_sale' => ProductResource::collection(
                    Product::query()->with($with)->where('is_active', true)->where('is_flash_sale', true)->latest()->take(8)->get()
                )->resolve(),
                'new_arrivals' => ProductResource::collection(
                    Product::query()->with($with)->where('is_active', true)->latest()->take(8)->get()
                )->resolve(),
            ],
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json([
            'data' => CategoryResource::tree(Category::publicTree()),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with(['images', 'category', 'store'])
            ->where('is_active', true);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->string('category')->toString())->first();
            if ($category) {
                $ids = $this->categoryIds($category);
                $query->whereIn('category_id', $ids);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('flash')) {
            $query->where('is_flash_sale', true);
        }

        $sort = $request->string('sort')->toString();
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate($request->integer('per_page', 12))->withQueryString();

        return ProductResource::collection($products)->response();
    }

    public function show(Product $product): ProductResource
    {
        abort_unless($product->is_active, 404);

        return new ProductResource($product->load(['images', 'category', 'store']));
    }

    public function storefront(Store $store): JsonResponse
    {
        $store->loadCount('products');

        $products = Product::query()
            ->with(['images', 'category', 'store'])
            ->where('store_id', $store->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => [
                'store' => new StoreResource($store),
                'products' => ProductResource::collection($products)->resolve(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                ],
            ],
        ]);
    }

    private function categoryIds(Category $category): array
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->categoryIds($child));
        }

        return $ids;
    }
}
