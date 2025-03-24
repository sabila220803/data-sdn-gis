<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SdnController;

Route::get('/', [SdnController::class, 'index'])->name('sdn.index');
