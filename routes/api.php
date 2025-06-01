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
use App\Http\Controllers\Auth\AdminLoginController;

// Public routes 

Route::post('/login', [AuthController::class, 'login']); // Regular user login
Route::post('/admin/login', [AdminLoginController::class, 'apiLogin']); // Admin login

// Authenticated routes (both admin and regular users)
Route::middleware('auth:sanctum')->group(function() {
    // Common logout endpoint
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('albums', AlbumController::class);
    Route::apiResource('genres', GenreController::class)->only(['index']);
    Route::apiResource('users', UserController::class)->only(['index', 'destroy']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'store','show', 'update']);

    Route::apiResource('reviews', ReviewController::class);
    

    // Get current user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
   
    
