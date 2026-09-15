<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\RedirectShortLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(auth()->check()){
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ShortLinkController::class, 'index'])->name('dashboard');

    Route::get('/links/{shortLink}/edit', [ShortLinkController::class, 'edit'])->name('links.edit');
    Route::post('/links', [ShortLinkController::class, 'store'])->name('links.store');
    Route::delete('/links/{shortLink}', [ShortLinkController::class, 'destroy'])->name('links.destroy');
    Route::put('/links/{shortLink}', [ShortLinkController::class, 'update'])->name('links.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/{code}', RedirectShortLinkController::class)->name('links.redirect');

require __DIR__.'/auth.php';
