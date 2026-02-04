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
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [ItemRequestController::class, 'index'])->name('index');
        Route::get('/cart', [ItemRequestController::class, 'cart'])->name('cart');
        // Cart operations
        Route::post('/cart/add', [ItemRequestController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update/{itemId}', [ItemRequestController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{itemId}', [ItemRequestController::class, 'removeFromCart'])->name('cart.remove');
        Route::delete('/cart/clear', [ItemRequestController::class, 'clearCart'])->name('cart.clear');
        // Request submission and management
        Route::post('/submit', [ItemRequestController::class, 'submitRequest'])->name('submit');
        Route::get('/my-requests', [ItemRequestController::class, 'myRequests'])->name('my-requests'); // Correct name
        Route::get('/my-requests/{id}', [ItemRequestController::class, 'show'])->name('show');
        Route::post('/my-requests/{id}/cancel', [ItemRequestController::class, 'cancelRequest'])->name('cancel');
    });
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';