<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\KycController as AdminKycController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\StaffController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\KycController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Api\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Api\Vendor\StoreController as VendorStoreController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('home', [CatalogController::class, 'home']);
Route::get('categories', [CatalogController::class, 'categories']);
Route::get('products', [CatalogController::class, 'products']);
Route::get('products/{product:slug}', [CatalogController::class, 'show']);
Route::get('stores/{store:slug}', [CatalogController::class, 'storefront']);
Route::get('orders/track/{number}', [OrderController::class, 'track']);

Route::middleware(['auth:sanctum', 'account:user'])->group(function () {
    Route::get('dashboard', DashboardController::class);
    Route::match(['put', 'post'], 'profile', [ProfileController::class, 'update']);
    Route::put('profile/password', [ProfileController::class, 'updatePassword']);

    Route::get('kyc', [KycController::class, 'show']);
    Route::post('kyc', [KycController::class, 'store']);

    Route::apiResource('addresses', AddressController::class)->except(['show']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);

    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/{product}', [WishlistController::class, 'store']);
    Route::delete('wishlist/{product}', [WishlistController::class, 'destroy']);

    Route::get('cart', [CartController::class, 'show']);
    Route::post('cart', [CartController::class, 'store']);
    Route::put('cart/items/{item}', [CartController::class, 'update']);
    Route::delete('cart/items/{item}', [CartController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'account:vendor'])->prefix('vendor')->group(function () {
    Route::get('dashboard', VendorDashboardController::class);
    Route::get('store', [VendorStoreController::class, 'show']);
    Route::match(['put', 'post'], 'store', [VendorStoreController::class, 'update']);
    Route::get('products', [VendorProductController::class, 'index']);
    Route::post('products', [VendorProductController::class, 'store']);
    Route::get('products/{product}', [VendorProductController::class, 'show']);
    Route::match(['put', 'post'], 'products/{product}', [VendorProductController::class, 'update']);
    Route::delete('products/{product}', [VendorProductController::class, 'destroy']);
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('forgot-password', [AdminAuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AdminAuthController::class, 'resetPassword']);

    Route::middleware(['auth:sanctum', 'account:admin'])->group(function () {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('dashboard', AdminDashboardController::class);
        Route::match(['put', 'post'], 'profile', [ProfileController::class, 'update']);
        Route::put('profile/password', [ProfileController::class, 'updatePassword']);

        Route::middleware('admin.permission:Category Management')->group(function () {
            Route::get('categories', [AdminCategoryController::class, 'index']);
            Route::post('categories/reorder', [AdminCategoryController::class, 'reorder']);
            Route::post('categories', [AdminCategoryController::class, 'store']);
            Route::put('categories/{category}', [AdminCategoryController::class, 'update']);
            Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy']);
        });

        Route::middleware('admin.permission:KYC Management')->group(function () {
            Route::get('kyc', [AdminKycController::class, 'index']);
            Route::get('kyc/{kyc}', [AdminKycController::class, 'show']);
            Route::put('kyc/{kyc}', [AdminKycController::class, 'update']);
            Route::get('kyc/{kyc}/document', [AdminKycController::class, 'download']);
        });

        Route::middleware('admin.permission:Settings Management')->group(function () {
            Route::get('settings', [SettingController::class, 'show']);
            Route::put('settings', [SettingController::class, 'update']);
        });

        Route::middleware('admin.permission:Role Management')->group(function () {
            Route::get('permissions', [RoleController::class, 'permissions']);
            Route::get('roles', [RoleController::class, 'index']);
            Route::post('roles', [RoleController::class, 'store']);
            Route::get('roles/{role}', [RoleController::class, 'show']);
            Route::put('roles/{role}', [RoleController::class, 'update']);
            Route::delete('roles/{role}', [RoleController::class, 'destroy']);
        });

        Route::middleware('admin.permission:Role User Management')->group(function () {
            Route::get('staff', [StaffController::class, 'index']);
            Route::post('staff', [StaffController::class, 'store']);
            Route::put('staff/{staff}', [StaffController::class, 'update']);
            Route::delete('staff/{staff}', [StaffController::class, 'destroy']);
        });
    });
});
