<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load(['kyc', 'store']);
        $recent = $user->orders()->with('items')->latest()->take(5)->get();

        return response()->json([
            'data' => [
                'orders_count' => $user->orders()->count(),
                'wishlist_count' => $user->wishlists()->count(),
                'addresses_count' => $user->addresses()->count(),
                'open_orders' => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
                'kyc_status' => $user->kyc?->status,
                'user_type' => $user->user_type,
                'recent_orders' => OrderResource::collection($recent)->resolve(),
            ],
        ]);
    }
}
