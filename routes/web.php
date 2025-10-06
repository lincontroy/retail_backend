<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ShopProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('shops', ShopController::class);
});
Route::middleware(['auth','role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('shops', ShopController::class);
    Route::get('shops/{shop}/manage-routes', [ShopController::class, 'manageRoutes'])->name('shops.manage-routes');
    Route::post('shops/{shop}/update-routes', [ShopController::class, 'updateRoutes'])->name('shops.update-routes');
    Route::resource('routes', RouteController::class);
    Route::get('checkins', [CheckinController::class,'index'])->name('checkins.index');

    Route::resource('shop-products', ShopProductController::class);
    Route::get('shop-products/shop/{shop}', [ShopProductController::class, 'byShop'])->name('shop-products.by-shop');
    Route::get('shop-products/product/{product}', [ShopProductController::class, 'byProduct'])->name('shop-products.by-product');

     // User management
     Route::get('shops/{shop}/manage-users', [ShopController::class, 'manageUsers'])->name('shops.manage-users');
     Route::post('shops/{shop}/update-users', [ShopController::class, 'updateUsers'])->name('shops.update-users');
     Route::get('shops-users', [ShopController::class, 'shopUsers'])->name('shops.users-index');
     Route::get('shops-users', [ShopController::class, 'shopUsers'])->name('shops.users-index');
     Route::delete('shops/{shop}/users/{user}', [ShopController::class, 'removeUser'])->name('shops.remove-user');
});
