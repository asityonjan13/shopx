<?php

use App\Http\Controllers\Frontend\KycController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\StoreController;
use App\Http\Controllers\Frontend\VendorDashboardController;
use Illuminate\Support\Facades\Route;


// FRONTEND ROUTES
Route::get('/', function () {
    return view('frontend.home.index');
})->name('home');

// USER ROUTES (Authenticated)
Route::middleware(['auth:web', 'verified'])
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        //Profile Routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');

        //KYC Routes
        Route::get('/kyc-verification', [KycController::class, 'index'])->name('kyc.index');
        Route::post('/kyc-verification', [KycController::class, 'store'])->name('kyc.store');

    });

//VENDOR ROUTES
Route::prefix('vendor')
    ->as('vendor.')
    ->middleware(['auth:web', 'verified', 'user_role:vendor'])
    ->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])
            ->name('dashboard');
        // Shop Profile Routes
        Route::resource('store-profile', StoreController::class);
    });


// AUTH ROUTES
require __DIR__ . '/auth.php';
