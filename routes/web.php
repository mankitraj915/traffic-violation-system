<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('violations', ViolationController::class);
    Route::resource('payments', PaymentController::class)->except(['create', 'store']);
    Route::get('violations/{violation}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('violations/{violation}/pay', [PaymentController::class, 'store'])->name('payments.store');

    // Admin routes with explicit middleware class
    Route::prefix('admin')->middleware([AdminMiddleware::class])->group(function () {
        Route::get('/disputes', [AdminController::class, 'disputes'])->name('admin.disputes');
        Route::post('/disputes/{violation}/resolve', [AdminController::class, 'resolve'])->name('admin.disputes.resolve');
    });
});

require __DIR__.'/auth.php';