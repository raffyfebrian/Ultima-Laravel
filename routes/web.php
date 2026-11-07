<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\RoomOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'dashboard.index')
        ->name('dashboard');

    Route::prefix('admin/inventory')
        ->name('admin.inventory.')
        ->middleware('role:superadmin|admin')
        ->controller(InventoryController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/stock-in', 'receive')->name('stock-in');
        });

    Route::prefix('admin/users')
        ->name('admin.users.')
        ->middleware('role:superadmin')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{user}/edit', 'edit')->name('edit');
            Route::patch('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'destroy')->name('destroy');
        });

    Route::prefix('admin/orders')
        ->name('admin.orders.')
        ->middleware('role:superadmin|admin|cashier|kitchen')
        ->controller(OrderController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
            Route::patch('/{order}/status', 'updateStatus')->name('update-status');
        });

    Route::prefix('admin/cashier')
        ->name('admin.cashier.')
        ->middleware('role:superadmin|admin|cashier')
        ->controller(CashierController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{order}/confirm', 'confirmPayment')->name('confirm');
            Route::post('/{order}/refund', 'refund')->name('refund');
        });

    Route::prefix('admin/kitchen')
        ->name('admin.kitchen.')
        ->middleware('role:superadmin|admin|kitchen')
        ->controller(KitchenController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/{order}/advance', 'advance')->name('advance');
        });
});

Route::get('/order/{token}', [RoomOrderController::class, 'show'])
    ->where('token', '[A-Za-z0-9]{64}')
    ->name('guest.order.show');

Route::get('/order/confirmation/{orderNumber}', [
    RoomOrderController::class,
    'confirmation',
])
    ->where('orderNumber', '[A-Za-z0-9-]+')
    ->name('guest.order.confirmation');

  
