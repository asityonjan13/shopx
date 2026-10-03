<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $store = $request->user()->store;
        if (! $store) {
            return response()->json(['data' => []]);
        }

        $products = $store->products()->with(['images', 'category', 'store'])->latest()->get();

        return response()->json([
            'data' => ProductResource::collection($products)->resolve(),
        ]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $this->assertVendorCanSell($request);
        $store = $request->user()->store;
        abort_unless($store, 422, 'Create your store profile before adding products.');

        $product = $store->products()->create($this->attributes($request));
        $this->storeImage($request, $product);

        return response()->json([
            'message' => 'Product published.',
            'data' => new ProductResource($product->load(['images', 'category', 'store'])),
        ], 201);
    }

    public function show(Request $request, Product $product): ProductResource
    {
        $this->assertOwns($request, $product);

        return new ProductResource($product->load(['images', 'category', 'store']));
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $this->assertOwns($request, $product);
        $product->update($this->attributes($request, $product));
        $this->storeImage($request, $product);

        return response()->json([
            'message' => 'Product updated.',
            'data' => new ProductResource($product->load(['images', 'category', 'store'])),
        ]);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->assertOwns($request, $product);
        $product->load('images');
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        $product->delete();

        return response()->json(['message' => 'Product removed.']);
    }

    private function assertVendorCanSell(Request $request): void
    {
        $status = $request->user()->kyc?->status;
        if ($status !== 'approved') {
            throw ValidationException::withMessages([
                'kyc' => 'Your KYC must be approved before you can sell products.',
            ]);
        }
    }

    private function assertOwns(Request $request, Product $product): void
    {
        $storeId = $request->user()->store?->id;
        abort_unless($storeId && $product->store_id === $storeId, 404);
    }

    private function attributes(ProductRequest $request, ?Product $product = null): array
    {
        $name = $request->string('name')->toString();
        $slugSource = $request->filled('slug') ? $request->string('slug')->toString() : $name;

        return [
            'category_id' => $request->input('category_id'),
            'name' => $name,
            'slug' => Slug::unique(Product::class, $slugSource, $product?->id),
            'sku' => $request->input('sku'),
            'price' => $request->input('price'),
            'compare_price' => $request->input('compare_price'),
            'stock' => $request->integer('stock'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
            'is_flash_sale' => $request->boolean('is_flash_sale'),
            'flash_price' => $request->input('flash_price'),
        ];
    }

    private function storeImage(ProductRequest $request, Product $product): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $path = $request->file('image')->store('products', 'public');
        $existing = $product->images()->where('is_primary', true)->first();
        if ($existing && Storage::disk('public')->exists($existing->path)) {
            Storage::disk('public')->delete($existing->path);
            $existing->delete();
        }

        $product->images()->create([
            'path' => $path,
            'position' => 0,
            'is_primary' => true,
        ]);
    }
}
