<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.landing');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES 
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    // Item Request Routes
    Route::get('/request-items', [ItemRequestController::class, 'index'])->name('requests.index');
    Route::get('/request-items/cart', [ItemRequestController::class, 'cart'])->name('requests.cart');
    Route::post('/request-items/add-to-cart', [ItemRequestController::class, 'addToCart'])->name('requests.addToCart');
    Route::delete('/request-items/remove/{itemId}', [ItemRequestController::class, 'removeFromCart'])->name('requests.removeFromCart');
    Route::post('/request-items/submit', [ItemRequestController::class, 'submitRequest'])->name('requests.submit');
    Route::get('/my-requests', [ItemRequestController::class, 'myRequests'])->name('requests.myRequests');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';