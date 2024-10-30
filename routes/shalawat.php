<?php

use App\Http\Controllers\ShalawatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->prefix('shalawat')->name('shalawat.')->group(function () {
    Route::get('/', fn() => Inertia::render('Shalawat/Index'))->name('index');
    Route::post('/', [ShalawatController::class, 'store'])->name('store');
    Route::delete('/{id}', [ShalawatController::class, 'destroy']);
    Route::patch('/{shalawat}', [ShalawatController::class, 'update'])->name('update');
    Route::get('/get', [ShalawatController::class, 'getData']);
    Route::get('/{name}/get', [ShalawatController::class, 'getShalawatMuhud']);
});
