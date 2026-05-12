<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MenuManagerController;
use App\Http\Controllers\HistoryController;

// Customer Routes (Public)
Route::redirect('/pos', '/');
Route::redirect('/pos/{any}', '/{any}')->where('any', '.*');
Route::redirect('/poss', '/');
Route::redirect('/poss/{any}', '/{any}')->where('any', '.*');
Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/order', [CustomerController::class, 'index'])->name('customer.order');
Route::get('/menus/category', [CustomerController::class, 'getMenusByCategory'])->name('menus.by-category');

// Auth alias for default Laravel login redirect
Route::get('/login', [KasirController::class, 'loginForm'])->name('login');
Route::redirect('/kasir', '/kasir/login');
Route::redirect('/admin', '/kasir/login');

// Order Routes (Public)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/orders/check-expired', [OrderController::class, 'checkExpired'])->name('orders.check-expired');
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

// Payment Routes (Public)
Route::post('/payments/upload', [PaymentController::class, 'upload'])->name('payments.upload');

// Kasir Routes
Route::prefix('kasir')->group(function () {
    Route::get('/login', [KasirController::class, 'loginForm'])->name('kasir.login');
    Route::post('/login', [KasirController::class, 'login'])->name('kasir.login.submit');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('kasir.dashboard');
        Route::get('/dashboard/poll', [KasirController::class, 'pollDashboard'])->name('kasir.dashboard.poll');
        Route::post('/orders/{orderId}/approve', [KasirController::class, 'approvePayment'])->name('kasir.approve');
        Route::post('/orders/{orderId}/flag', [KasirController::class, 'flagPayment'])->name('kasir.flag');
        Route::post('/orders/{orderId}/reject', [KasirController::class, 'rejectPayment'])->name('kasir.reject');
        Route::post('/orders/{orderId}/update-status', [KasirController::class, 'updateStatus'])->name('kasir.update-status');
        Route::post('/logout', [KasirController::class, 'logout'])->name('kasir.logout');

        // Menu Management
        Route::get('/menu', [MenuManagerController::class, 'index'])->name('kasir.menu.index');
        Route::get('/menu/create', [MenuManagerController::class, 'createMenu'])->name('kasir.menu.create');
        Route::post('/menu', [MenuManagerController::class, 'storeMenu'])->name('kasir.menu.store');
        Route::get('/menu/{menu}/edit', [MenuManagerController::class, 'editMenu'])->name('kasir.menu.edit');
        Route::put('/menu/{menu}', [MenuManagerController::class, 'updateMenu'])->name('kasir.menu.update');
        Route::delete('/menu/{menu}', [MenuManagerController::class, 'destroyMenu'])->name('kasir.menu.destroy');
        Route::patch('/menu/{menu}/toggle', [MenuManagerController::class, 'toggleAvailable'])->name('kasir.menu.toggle');

        // Walk-in Order
        Route::get('/walkin', [KasirController::class, 'walkinForm'])->name('kasir.walkin');
        Route::post('/walkin', [KasirController::class, 'walkinStore'])->name('kasir.walkin.store');

        // Invoice
        Route::get('/invoice/{orderNumber}', [KasirController::class, 'invoice'])->name('kasir.invoice');

        // History
        Route::get('/history', [HistoryController::class, 'index'])->name('kasir.history');

        // Category Management
        Route::get('/category/create', [MenuManagerController::class, 'createCategory'])->name('kasir.category.create');
        Route::post('/category', [MenuManagerController::class, 'storeCategory'])->name('kasir.category.store');
        Route::get('/category/{category}/edit', [MenuManagerController::class, 'editCategory'])->name('kasir.category.edit');
        Route::put('/category/{category}', [MenuManagerController::class, 'updateCategory'])->name('kasir.category.update');
        Route::delete('/category/{category}', [MenuManagerController::class, 'destroyCategory'])->name('kasir.category.destroy');
    });
});
