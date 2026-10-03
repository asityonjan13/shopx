<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['images', 'category', 'store'])
            ->whereIn('id', $request->user()->wishlists()->pluck('product_id'))
            ->latest()
            ->get();

        return response()->json([
            'data' => ProductResource::collection($products)->resolve(),
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $request->user()->wishlists()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return response()->json(['message' => 'Saved to your wishlist.'], 201);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $request->user()->wishlists()->where('product_id', $product->id)->delete();

        return response()->json(['message' => 'Removed from your wishlist.']);
    }
}
