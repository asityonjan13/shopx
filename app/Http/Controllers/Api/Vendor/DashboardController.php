<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load(['kyc', 'store']);
        $store = $user->store;

        return response()->json([
            'data' => [
                'kyc_status' => $user->kyc?->status,
                'store' => $store ? [
                    'id' => $store->id,
                    'name' => $store->name,
                    'slug' => $store->slug,
                ] : null,
                'products_count' => $store ? $store->products()->count() : 0,
                'active_products' => $store ? $store->products()->where('is_active', true)->count() : 0,
                'low_stock' => $store ? $store->products()->where('stock', '<', 5)->count() : 0,
            ],
        ]);
    }
}
