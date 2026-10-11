<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Mini Project 3: Inventory Management System (InventPro)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. Rute Tamu / Guest (Autentikasi)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==========================================
// 2. Rute Terotentikasi (Admin & Staff)
// ==========================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Beranda
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Mutasi Stok (Staff & Admin berhak mencatat mutasi fisik)
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
    });

    // Modul Produk: Pembacaan Katalog (Semua Pengguna Terotentikasi)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Modul Produk: Operasi Modifikasi Data (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Modul Produk: Detail Produk (Diletakkan setelah /create untuk mencegah konflik route parameter)
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});
