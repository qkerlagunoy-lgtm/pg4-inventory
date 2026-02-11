<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;


/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.landing');
});

/*
|--------------------------------------------------------------------------
| NOTIFICATION ROUTES (Common for both admin and users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (Common for both admin and users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Order Management Module
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'orderDashboard'])->name('index');
        Route::get('/pending', [AdminController::class, 'pendingOrders'])->name('pending');
        Route::get('/approved', [AdminController::class, 'approvedOrders'])->name('approved');
        Route::get('/rejected', [AdminController::class, 'rejectedOrders'])->name('rejected');
        Route::get('/review/{id}', [AdminController::class, 'reviewOrder'])->name('review');
        Route::post('/approve/{id}', [AdminController::class, 'approveOrder'])->name('approve');
        Route::post('/reject/{id}', [AdminController::class, 'rejectOrder'])->name('reject');
        Route::get('/create-issuance/{id}', [AdminController::class, 'createIssuance'])->name('create-issuance');
        Route::post('/process-issuance/{id}', [AdminController::class, 'processIssuance'])->name('process-issuance');
        Route::get('/issuances', [AdminController::class, 'issuances'])->name('issuances');
        Route::get('/issuances/{id}', [AdminController::class, 'viewIssuance'])->name('issuances.view');
        Route::get('/returns', [AdminController::class, 'returns'])->name('returns');
        Route::post('/process-return/{id}', [AdminController::class, 'processReturn'])->name('process-return');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
    });

    // Inventory Module
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{item}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{item}', [InventoryController::class, 'destroy'])->name('destroy');
        Route::get('/{item}', [InventoryController::class, 'show'])->name('show');
        Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        Route::post('/{item}/restock', [InventoryController::class, 'restock'])->name('restock');
    });

    //order item admin

    Route::resource('order-requests', controller: OrderRequestController::class);
    Route::get('order-requests/{id}/pdf', [OrderRequestController::class, 'pdf']);


        // User Management Routes
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/bulk-update', [UserController::class, 'bulkUpdate'])->name('users.bulk-update');


    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index'); // List users
        Route::get('/create', [AdminController::class, 'createUser'])->name('create'); // Show create form
        Route::post('/store', [AdminController::class, 'storeUser'])->name('store'); // Save new user
        Route::get('/{id}/edit', [AdminController::class, 'usersEdit'])->name('edit'); // Edit form
        Route::put('/{id}', [AdminController::class, 'usersUpdate'])->name('update'); // Update user
        Route::delete('/{id}', [AdminController::class, 'usersDestroy'])->name('destroy'); // Delete user
        Route::get('/pdf', [AdminController::class, 'usersPdf'])->name('pdf'); // Generate PDF
    });

    // Categories Module
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    });
    
    // Address Management Module
    /*
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [AdminController::class, 'addresses'])->name('index');
    });
    */

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
        Route::post('/cart/add', [ItemRequestController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update/{itemId}', [ItemRequestController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{itemId}', [ItemRequestController::class, 'removeFromCart'])->name('cart.remove');
        Route::delete('/cart/clear', [ItemRequestController::class, 'clearCart'])->name('cart.clear');
        Route::post('/submit', [ItemRequestController::class, 'submitRequest'])->name('submit');
        Route::get('/my-requests', [ItemRequestController::class, 'myRequests'])->name('my-requests');
        Route::get('/my-requests/{id}', [ItemRequestController::class, 'show'])->name('show');
        Route::post('/my-requests/{id}/cancel', [ItemRequestController::class, 'cancelRequest'])->name('cancel');
    });
});

require __DIR__.'/auth.php';
