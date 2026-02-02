<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;


Route::get('/', function () {
    return view('auth.landing');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (SEPARATE & PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    Route::get('/admin/orders', [AdminController::class, 'orders'])
        ->name('admin.orders');
    
    Route::get('/admin/inventory', [AdminController::class, 'inventory'])
        ->name('admin.inventory');
    
    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');
    
    Route::get('/admin/categories', [AdminController::class, 'categories'])
        ->name('admin.categories');

});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Default user dashboard
    Route::get('/dashboard', [UserController::class, 'Dashboard'])->name('dashboard');

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

    ///order item admin

Route::resource('order-requests', controller: OrderRequestController::class);
Route::get('order-requests/{id}/pdf', [OrderRequestController::class, 'pdf']);



// Add these routes to your web.php file inside the admin middleware group

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-update', [UserController::class, 'bulkUpdate'])->name('users.bulk-update');
    
});






});



require __DIR__.'/auth.php';
