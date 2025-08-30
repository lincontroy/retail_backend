<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\RouteController;
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
    Route::resource('shops', ShopController::class);
    Route::resource('routes', RouteController::class);
    Route::get('checkins', [CheckinController::class,'index'])->name('checkins.index');
});
