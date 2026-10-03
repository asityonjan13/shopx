<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders)->response();
    }

    public function show(Request $request, Order $order): OrderResource
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return new OrderResource($order->load('items'));
    }

    public function track(string $number): OrderResource
    {
        $order = Order::where('number', $number)->firstOrFail();

        return new OrderResource($order->load('items'));
    }

    public function store(CheckoutRequest $request): JsonResponse
    {
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $address = $user->addresses()->find($request->integer('address_id'));
        if (! $address) {
            throw ValidationException::withMessages([
                'address_id' => 'Choose a saved address.',
            ]);
        }

        $order = DB::transaction(function () use ($user, $cart, $address) {
            $subtotal = 0;
            $lines = [];

            foreach ($cart->items as $item) {
                $product = $item->product;
                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'A product in your cart is no longer available.',
                    ]);
                }
                if ($item->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => $product->name.' does not have enough stock.',
                    ]);
                }

                $price = $product->sellingPrice();
                $subtotal += $price * $item->quantity;
                $lines[] = [$product, $item->quantity, $price];
            }

            $shipping = $subtotal >= 75 ? 0 : 8;
            $order = $user->orders()->create([
                'number' => 'SX-TMP',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
                'shipping_address' => $address->only([
                    'label', 'full_name', 'phone', 'line1', 'line2', 'city', 'state', 'postal_code', 'country',
                ]),
            ]);

            $order->update(['number' => 'SX-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT)]);

            foreach ($lines as [$product, $quantity, $price]) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        return response()->json([
            'message' => 'Order placed.',
            'data' => new OrderResource($order->load('items')),
        ], 201);
    }
}
