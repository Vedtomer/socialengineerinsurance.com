<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


// Route::get('/', function () {
//     return view('app');
// });

// Route::get('/admin', function () {
//     return view('admin.admin');
// });

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});


// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('admin.login');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::match(['get', 'post'], '/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    });
});
