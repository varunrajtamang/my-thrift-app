<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\SellerSubscriptionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductConditionController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\OrderController;

Route::get('/stores', [StoreController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'getAUser']);

// Public auth routes
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});

// Public category read
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Any logged-in user can create a store.
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/my-store', [StoreController::class, 'myStore']);

    // Subscription status for create-flow gating on frontend.
    Route::get('/my-subscription-status', [SellerSubscriptionController::class, 'myStatus']);

    // role based authorization examples
    Route::middleware('role:user')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
    });

    // Resource routes
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);
    Route::apiResource('subscription-plans', SubscriptionPlanController::class);
    Route::apiResource('seller-subscription', SellerSubscriptionController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('product-condition', ProductConditionController::class);
    Route::apiResource('product-image', ProductImageController::class);
    Route::apiResource('store-details', StoreController::class);
});
