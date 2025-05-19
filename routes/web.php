<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PreferencesController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/home/month', function () {
        return view('frontend.page.month');
    })->name('home.month');
    Route::get('/home/week', function () {
        return view('frontend.page.week');
    })->name('home.week');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/register/preferences', [PreferencesController::class, 'create'])->name('preferences.create');
    Route::post('/register/preferences', [PreferencesController::class, 'store'])->name('preferences.store');
});

require __DIR__ . '/auth.php';
