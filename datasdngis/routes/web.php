<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SdnController;

// Route publik
Route::get('/', [SdnController::class, 'index'])->name('sdn.index');
Route::get('/sdn/{slug}', [SdnController::class, 'show'])->name('sdn.show');

// Route autentikasi
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login.page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/sdn/create', [SdnController::class, 'create'])->name('sdn.create');
    Route::post('/sdn', [SdnController::class, 'store'])->name('sdn.store');
    Route::get('/sdn/{slug}/edit', [SdnController::class, 'edit'])->name('sdn.edit');
    Route::put('/sdn/{slug}', [SdnController::class, 'update'])->name('sdn.update');
    Route::delete('/sdn/{slug}', [SdnController::class, 'destroy'])->name('sdn.destroy');
});
