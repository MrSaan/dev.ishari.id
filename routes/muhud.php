<?php

use App\Http\Controllers\MuhudController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->prefix('muhud')->name('muhud.')->group(function () {
    Route::get('/', fn() => Inertia::render('Muhud/Index'))->name('index');
    Route::post('/', [MuhudController::class, 'store'])->name('store');
    Route::delete('/{id}', [MuhudController::class, 'destroy']);
    Route::patch('/{muhud}', [MuhudController::class, 'update'])->name('update');
    Route::get('/get', [MuhudController::class, 'getData']);
});
