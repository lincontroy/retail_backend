<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShopProductController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CheckinController;

Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Check-in routes
    Route::post('/checkin', [CheckinController::class, 'store']);
    Route::get('/user/routes/today', [CheckinController::class, 'getTodayRoutes']);
    Route::get('/user/routes', [CheckinController::class, 'getUserRoutes']);
    Route::get('/checkins', [CheckinController::class, 'index']);
    
    // Product routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/statistics', [ProductController::class, 'statistics']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/checkins/{checkinId}/products', [ProductController::class, 'getCheckinProducts']);
    Route::post('/checkins/{checkinId}/products', [ProductController::class, 'updateCheckinProducts']);

    Route::prefix('shop-products')->group(function () {
        Route::get('shop/{shopId}/product/{productId}', [ShopProductController::class, 'getShopProduct']);
        Route::put('shop/{shopId}/product/{productId}', [ShopProductController::class, 'updateShopProduct']);
        Route::get('shop/{shopId}', [ShopProductController::class, 'getShopProducts']);
    });
});
