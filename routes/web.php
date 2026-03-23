<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeviceRegistryController;
use App\Http\Controllers\IpAddressRangeController;
use App\Http\Controllers\OffenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProfileController;


/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.landing');
})->name('home'); // Added name for consistency

// REMOVED the /register/pending route since registration is removed
// If you still need a pending page for admin-created users, keep it, otherwise remove

// Keep this if users are still created by admins and need a pending status page
Route::get('/register/pending', function () {
    return view('auth.pending');
})->name('register.pending');

// Updated login route - redirects to landing page with login form
Route::get('/login', function () {
    return view('auth.landing');
})->name('login');


/*
|--------------------------------------------------------------------------
| NOTIFICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
     Route::get('/profile',              [AdminProfileController::class, 'edit'])         ->name('profile.edit');
    Route::patch('/profile',            [AdminProfileController::class, 'update'])       ->name('profile.update');
    Route::post('/profile/avatar',      [AdminProfileController::class, 'updateAvatar']) ->name('profile.avatar');


    // Orders
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

  Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('/create', [InventoryController::class, 'create'])->name('create');
    Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
    Route::get('/export-csv', [InventoryController::class, 'exportCsv'])->name('export-csv'); // ← moved up
    Route::post('/', [InventoryController::class, 'store'])->name('store');
    Route::get('/{item}', [InventoryController::class, 'show'])->name('show');
    Route::get('/{item}/edit', [InventoryController::class, 'edit'])->name('edit');
    Route::put('/{item}', [InventoryController::class, 'update'])->name('update');
    Route::delete('/{item}', [InventoryController::class, 'destroy'])->name('destroy');
    Route::post('/{item}/restock', [InventoryController::class, 'restock'])->name('restock');
});
    // Users - RESTRICTED for PG4 admins
    Route::prefix('users')->name('users.')->middleware('superadmin.only')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create'); // Keep this for admin creation
        Route::get('/export', [UserManagementController::class, 'exportCsv'])->name('export');
        Route::post('/', [UserManagementController::class, 'store'])->name('store'); // Keep this for admin creation
        Route::get('/{id}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/activate', [UserManagementController::class, 'activate'])->name('activate'); 
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::post('/bulk-status', [CategoryController::class, 'bulkUpdateStatus'])->name('bulk-status');
        Route::post('/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::get('/export', [CategoryController::class, 'exportCsv'])->name('export');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

Route::middleware('superadmin.only')->group(function () {

    // ── Put specific routes BEFORE resource routes ──
    Route::post('addresses/device/register', [IpAddressRangeController::class, 'registerDevice'])
         ->name('addresses.device.register');
    Route::get('addresses/device/{device}/profile', [IpAddressRangeController::class, 'deviceProfile'])
         ->name('addresses.device-profile');
    Route::put('addresses/device/{device}/update', [IpAddressRangeController::class, 'updateDevice'])
         ->name('addresses.device.update');
    Route::delete('addresses/device/{device}/delete', [IpAddressRangeController::class, 'deleteDevice'])
         ->name('addresses.device.delete');
    Route::post('addresses/device/{device}/offense', [IpAddressRangeController::class, 'addOffense'])
         ->name('addresses.device.offense.store');
    Route::delete('addresses/offense/{offense}/delete', [IpAddressRangeController::class, 'deleteOffense'])
         ->name('addresses.device.offense.delete');

    // ── Resource routes AFTER ──
    Route::resource('device-registry', DeviceRegistryController::class);
    Route::get('device-registry/user/{user}', [DeviceRegistryController::class, 'userProfile'])
         ->name('device-registry.user-profile');
    Route::resource('addresses', IpAddressRangeController::class);

    // Offenses
    Route::post('offense', [OffenseController::class, 'store'])->name('offenses.store');
    Route::put('offense/{offense}', [OffenseController::class, 'update'])->name('offenses.update');
    Route::delete('offense/{offense}', [OffenseController::class, 'destroy'])->name('offenses.destroy');
    Route::put('addresses/offense/{offense}/update', [IpAddressRangeController::class, 'updateOffense'])
     ->name('addresses.offense.update');

});


});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

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