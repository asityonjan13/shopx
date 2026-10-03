<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->relationLoaded('items') ? $this->items : collect();

        $subtotal = $items->sum(function ($item) {
            if (! $item->relationLoaded('product') || ! $item->product) {
                return 0;
            }

            return $item->product->sellingPrice() * $item->quantity;
        });

        return [
            'id' => $this->id,
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'line_total' => $item->product ? round($item->product->sellingPrice() * $item->quantity, 2) : 0,
                    'product' => $item->product ? ProductResource::make($item->product) : null,
                ];
            })->values(),
            'count' => (int) $items->sum('quantity'),
            'subtotal' => round($subtotal, 2),
        ];
    }
}
