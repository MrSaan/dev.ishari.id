<?php

use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->prefix('pimpinan-shalawat')->name('figure.')->group(function () {
   Route::get('/', fn() => Inertia::render('Figure/Index'))->name('index');

   Route::get('/get', [PersonController::class, 'getData']);
   Route::delete('/{id}', [PersonController::class, 'destroy']);
   Route::patch('/{person}', [PersonController::class, 'update'])->name('update');
   Route::post('/', [PersonController::class, 'store'])->name('store');

});
