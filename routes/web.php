<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('login')));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard/shift', [ShiftController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('shift.index');
Route::post('/dashboard/shift', [ShiftController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('shift.store');

require __DIR__ . '/auth.php';
