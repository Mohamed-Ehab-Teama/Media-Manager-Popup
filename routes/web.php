<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});


// ==============================================================   Laravel Breeze Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// ==============================================================   Laravel Breeze Routes End


Route::middleware(['auth'])
    ->group(function () {

        // =============== Media Routes
        Route::resource('media', MediaController::class)
            ->only(['index', 'store']);
    });



require __DIR__ . '/auth.php';
