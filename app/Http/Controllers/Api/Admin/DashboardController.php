<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'customers' => User::where('user_type', 'user')->count(),
                'vendors' => User::where('user_type', 'vendor')->count(),
                'products' => Product::count(),
                'orders' => Order::count(),
                'pending_kyc' => Kyc::where('status', 'pending')->count(),
                'revenue' => (float) Order::where('status', '!=', 'cancelled')->sum('total'),
            ],
        ]);
    }
}
