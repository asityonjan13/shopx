<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KycRequestController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('login.submit');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

Route::middleware('auth:admin')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        //Profile Routes
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');

        //KYC Routes
        Route::get('/kyc-requests', [KycRequestController::class, 'index'])->name('kyc.index');
        Route::get('/kyc-requests/pending', [KycRequestController::class, 'pending'])->name('kyc.pending');
        Route::get('/kyc-requests/rejected', [KycRequestController::class, 'rejected'])->name('kyc.rejected');
        Route::get('/kyc-requests/approved', [KycRequestController::class, 'approved'])->name('kyc.approved');
        Route::get('/kyc-requests/{kyc_request}', [KycRequestController::class, 'show'])->name('kyc.show');
        Route::get('/kyc-requests/download/{kyc_request}', [KycRequestController::class, 'download'])->name('kyc.download');
        Route::put('/kyc-requests/{kyc_request}/update', [KycRequestController::class, 'update'])->name('kyc.update');

        //Role routes
        Route::resource('/role', RoleController::class);
        Route::resource('/role-users', UserRoleController::class);

        //Categories route
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/nested', [CategoryController::class, 'getNestedCategories'])->name('categories.nested');
        Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order');
        //GET request - Load category data for editing
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        //PUT request - Save the updated category data
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        //Settings Route
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/general-settings', [SettingController::class, 'generalSettings'])->name('settings.general');
    });

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth:admin', 'verified'])->name('admin.dashboard');
