<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PolicyController;




// Route::get('/', function () {
//     return view('app');
// });

// Route::get('/slider', function () {
//     return view('admin.slider');
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

        #Slider Routes
        // Route::get('/sliders', [SliderController::class, 'slider'])->name('sliders.slider');
        
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::post('/sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggleStatus');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
        
        Route::match(['get', 'post'], '/policy-pdf-upload', [PolicyController::class, 'policyUpload'])->name('admin.policy_pdf_upload');
        Route::match(['get', 'post'], '/policy-list', [PolicyController::class, 'PolicyList'])->name('policy.list');
        Route::match(['get', 'post'], '/commission/{id}', [AgentController::class, 'commission'])->name('agent.commission');
        #agent list route
        Route::get('agent-list', [AgentController::class, 'AgentList'])->name('agent.list');
    });
});
