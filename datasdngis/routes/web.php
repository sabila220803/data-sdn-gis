<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SdnController;

// Route autentikasi
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login.page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [SdnController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD SDN
    Route::get('/admin/sdn/create', [SdnController::class, 'create'])->name('sdn.create');
    Route::post('/admin/sdn', [SdnController::class, 'store'])->name('sdn.store');
    Route::get('/admin/sdn/{slug}/edit', [SdnController::class, 'edit'])->name('sdn.edit');
    Route::put('/admin/sdn/{slug}', [SdnController::class, 'update'])->name('sdn.update');
    Route::delete('/admin/sdn/{slug}', [SdnController::class, 'destroy'])->name('sdn.destroy');
});

// Route publik
Route::get('/', [SdnController::class, 'index'])->name('sdn.index');
