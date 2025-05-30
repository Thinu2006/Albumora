<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\CustomerRouteController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});



Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/albums', [AdminController::class, 'albums'])->name('admin.albums');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/orders/{id}', [AdminController::class, 'orderDetail'])->name('admin.orders.detail');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store']);
    Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');
});


Route::get('/user/albums', [CustomerRouteController::class, 'albumsearch'])->name('user.album-search');
// Route::get('/admin/albums', \App\Livewire\AlbumSearch::class);
Route::get('/user/cart', [CustomerRouteController::class, 'cart'])->name('user.cart');
Route::get('/user/checkout', [CustomerRouteController::class, 'checkout'])->name('user.checkout');
Route::get('/user/orders/{id}', [CustomerRouteController::class, 'ordershow'])->name('user.orders.show');
// Route::get('/user/orders', [CustomerController::class, 'orderlist'])->name('user.orders.list');
Route::get('/user/orders', [CustomerRouteController::class, 'orderlist'])->name('user.orders.list');
Route::get('/user/review', [CustomerRouteController::class, 'review'])->name('user.review');

