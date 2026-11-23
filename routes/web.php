<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LaundryItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Halaman utama (Katalog Layanan Pelanggan) & Checkout
Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

// 2. Dashboard Utama Breeze (Diarahkan ke Admin Dashboard dengan proteksi Gate)
Route::get('/dashboard', [OrderController::class, 'adminDashboard'])
    ->middleware(['auth', 'can:manage-admin'])
    ->name('dashboard');

// 3. Group Rute Khusus Admin
Route::middleware(['auth', 'can:manage-admin'])->group(function () {
    // Alias route untuk Admin Dashboard
    Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // CRUD Master Data Layanan Laundry
    Route::resource('/admin/laundry_items', LaundryItemController::class);

    // CRUD Manajemen Akun User (Soal Bonus 2)
    Route::resource('/admin/users', UserController::class)
        ->names('admin.users')
        ->except(['show']);

    // Laporan Rekap Layanan & Omzet Harian (Soal Bonus 4)
    Route::get('/admin/reports/layanans', [OrderController::class, 'report'])->name('admin.reports.layanans');
});

// 4. Profil Pengguna Terautentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
