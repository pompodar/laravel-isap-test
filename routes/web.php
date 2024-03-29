<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/handle', [ProfileController::class, 'handle'])->name('profile.handle');

});

require __DIR__.'/auth.php';

Route::group(['prefix' => 'products'], function () {
    // Fetch all products
    Route::get('/', [ProductController::class, 'index']);

    // Update a product
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');

});

Route::group(['prefix' => 'categories'], function () {
    // Fetch all categories
    Route::get('/', [CategoryController::class, 'index']);
});

