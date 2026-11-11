<?php

use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\RoomOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

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

    Route::prefix('admin/products')
        ->name('admin.products.')
        ->middleware('role:superadmin|admin')
        ->controller(ProductController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{product}/edit', 'edit')->name('edit');
            Route::patch('/{product}', 'update')->name('update');
            Route::patch('/{product}/toggle', 'toggleAvailability')->name('toggle');
            Route::delete('/{product}', 'destroy')->name('destroy');
        });

    Route::prefix('admin/categories')
        ->name('admin.categories.')
        ->middleware('role:superadmin|admin')
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{category}/edit', 'edit')->name('edit');
            Route::patch('/{category}', 'update')->name('update');
            Route::patch('/{category}/toggle', 'toggleActive')->name('toggle');
            Route::delete('/{category}', 'destroy')->name('destroy');
        });

    Route::prefix('admin/reports')
        ->name('admin.reports.')
        ->middleware('role:superadmin|admin')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
        });

    Route::prefix('admin/orders')
        ->name('admin.orders.')
        ->middleware('role:superadmin|admin|cashier|kitchen')
        ->controller(OrderController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
            Route::get('/{order}/receipt', 'receipt')->name('receipt');
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

    Route::prefix('admin/rooms')
        ->name('admin.rooms.')
        ->middleware('role:superadmin|admin')
        ->controller(RoomController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/print', 'print')->name('print');
            Route::get('/{room}/qr', 'qr')->name('qr');
            Route::get('/{room}/qr/download', 'download')->name('download');
            Route::patch('/{room}/toggle', 'toggle')->name('toggle');
            Route::patch('/{room}/regenerate', 'regenerate')->name('regenerate');
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

  
