<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// For admin
Route::middleware('auth:sanctum')->group(function () {
    // Album and Genre routes
    Route::apiResource('albums', AlbumController::class);
    Route::apiResource('genres', GenreController::class)->only(['index','store']);
    
    // Order, Payment, and Shipment routes
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show', 'update']);
    Route::apiResource('payments', PaymentController::class)->only(['index', 'store', 'show']);
    Route::apiResource('shipments', ShipmentController::class)->only(['index', 'store', 'show']);

    // User routes
    Route::apiResource('users', UserController::class)->only(['index', 'show', 'update']);

    // Route::apiResource('reviews', ReviewController::class);
    // Route::apiResource('reviews', ReviewController::class);
});

// For Users
Route::get('albums', [AlbumController::class, 'index']);
Route::apiResource('genres', GenreController::class)->only(['index','store']);

Route::apiResource('orders', OrderController::class)->except(['destroy']);
Route::apiResource('payments', PaymentController::class)->only(['index', 'store', 'show']);
Route::apiResource('shipments', ShipmentController::class)->only(['index', 'store', 'show']);
// Route::apiResource('reviews', ReviewController::class);

// Route::prefix('reviews')->group(function() {
//     Route::get('/', [\App\Http\Controllers\ReviewController::class, 'index']);
//     Route::post('/', [\App\Http\Controllers\ReviewController::class, 'store']);
//     Route::get('/{id}', [\App\Http\Controllers\ReviewController::class, 'show']);
// });

Route::prefix('reviews')->group(function() {
    Route::get('/', [ReviewController::class, 'index']);
    Route::post('/', [ReviewController::class, 'store']);
    Route::get('/{id}', [ReviewController::class, 'show']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
});