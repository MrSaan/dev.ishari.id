<?php

use App\Http\Controllers\AudioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->prefix('audio')->name('audio.')->group(function () {
    Route::get('/', fn() => Inertia::render('Audio/Index'))->name('index');
    Route::post('/', [AudioController::class, 'store'])->name('store');
    Route::delete('/{id}', [AudioController::class, 'destroy']);
    Route::get('/{muhudId}/get', [AudioController::class, 'getData']);
});
