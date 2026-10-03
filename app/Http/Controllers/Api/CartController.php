<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function show(Request $request): CartResource
    {
        $cart = $request->user()->cart()->firstOrCreate();

        return new CartResource($cart->load('items.product.images', 'items.product.category', 'items.product.store'));
    }

    public function store(CartItemRequest $request): JsonResponse
    {
        $product = Product::where('is_active', true)->findOrFail($request->integer('product_id'));
        $quantity = $request->integer('quantity');

        if ($product->stock < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'This product is out of stock.',
            ]);
        }

        $cart = $request->user()->cart()->firstOrCreate();
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $nextQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

        if ($nextQuantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Only '.$product->stock.' left in stock.',
            ]);
        }

        $item->quantity = $nextQuantity;
        $item->save();

        return response()->json([
            'message' => 'Added to cart.',
            'data' => new CartResource($cart->load('items.product.images', 'items.product.category', 'items.product.store')),
        ]);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        $this->assertOwnsItem($request, $item);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = $item->product;
        if ($data['quantity'] > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Only '.$product->stock.' left in stock.',
            ]);
        }

        $item->update(['quantity' => $data['quantity']]);
        $cart = $item->cart()->first();

        return response()->json([
            'message' => 'Cart updated.',
            'data' => new CartResource($cart->load('items.product.images', 'items.product.category', 'items.product.store')),
        ]);
    }

    public function destroy(Request $request, CartItem $item): JsonResponse
    {
        $this->assertOwnsItem($request, $item);
        $cart = $item->cart()->first();
        $item->delete();

        return response()->json([
            'message' => 'Removed from cart.',
            'data' => new CartResource($cart->load('items.product.images', 'items.product.category', 'items.product.store')),
        ]);
    }

    private function assertOwnsItem(Request $request, CartItem $item): void
    {
        $cart = $request->user()->cart;
        abort_unless($cart && $item->cart_id === $cart->id, 404);
    }
}
