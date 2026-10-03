<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'subtotal' => (float) $this->subtotal,
            'shipping' => (float) $this->shipping,
            'total' => (float) $this->total,
            'shipping_address' => $this->shipping_address,
            'created_at' => $this->created_at,
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'line_total' => round((float) $item->price * $item->quantity, 2),
            ])->values()),
        ];
    }
}
