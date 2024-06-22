<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PointRedemptionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CustomerController;


Route::get('/', function () {
    return view('pages.website.home');
})->name("homepage");

Route::get('/about', function () {
    return view('pages.website.about');
});
Route::get('/policies/privacy-policy', function () {
    return view('pages.website.privacy_policy');
});

// [PagesController::class, 'PrivacyPolicy'])->name('privacy.policy');


Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('login');
    Route::middleware(['role:admin', 'auth'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/profile/edit', [AdminController::class, 'ProfileEdit'])->name('edit.profile');
        Route::post('/profile/update', [AdminController::class, 'ProfileUpdate'])->name('admin.update');
        Route::match(['get', 'post'], '/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        #transaction
        route::get('transaction/{id?}', [AdminController::class, 'Transaction'])->name('admin.transaction');
        Route::match(['get', 'post'], '/add-transaction', [AdminController::class, 'AddTransaction'])->name('add.transaction');
        Route::match(['get', 'post'], 'agent-edit/{id}', [AgentController::class, 'AgentEdit'])->name('agent.edit');
        Route::match(['get', 'post'], 'change-password/{id}', [AgentController::class, 'ChangePassword'])->name('agent.change.password');

        Route::match(['get', 'post'], '/agent-pandding-balance', [PolicyController::class, 'panddingblance'])->name('agentpandding.blance');

        #manage policy
        Route::match(['get', 'post'], '/upload-policy', [PolicyController::class, 'upload'])->name('admin.upload');
        Route::match(['get', 'post'], '/updateagentid/{royalsundaram_id?}/{agent_id?}', [AgentController::class, 'updateagentid'])->name('updateagentid');
        Route::match(['get', 'post'], '/policy-list', [PolicyController::class, 'PolicyList'])->name('admin.policy_list');
        Route::get('/royalsundaram/{id?}', [AdminController::class, 'royalsundaram'])->name('royalsundaram');
        Route::match(['get', 'post'], '/policy-pdf-upload', [PolicyController::class, 'policyUpload'])->name('admin.policy_pdf_upload');


        #Slider Routes
        Route::get('/sliders', [SliderController::class, 'slider'])->name('sliders.slider');

        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::post('/sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggleStatus');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');


        #commission
        Route::match(['get', 'post'], '/commission/{id}', [AgentController::class, 'commission'])->name('agent.commission');
        Route::match(['get', 'post'], '/commission-code', [AgentController::class, 'commissionCode'])->name('commission.code');
        Route::get('/delete-commission/{id}', [AgentController::class, 'destroy'])->name('delete.commission');


        #agent list route
        Route::get('agent-list', [AgentController::class, 'AgentList'])->name('agent.list');
        Route::match(['get', 'post'], '/agent', [AgentController::class, 'Agent'])->name('agent');
        Route::match(['get', 'post'], 'agent-edit/{id}', [AgentController::class, 'AgentEdit'])->name('agent.edit');
        Route::match(['get', 'post'], 'change-password/{id}', [AgentController::class, 'ChangePassword'])->name('agent.change.password');
        Route::get('/download-excel', [AgentController::class, 'downloadExcel'])->name('download.excel');
        Route::get('/import-excel', [AgentController::class, 'importExcel'])->name('import.excel');



        #reward
        Route::get('/points-redemption', [PointRedemptionController::class, 'index'])->name('admin.reward.index');
        Route::get('/points-redemRequest', [PointRedemptionController::class, 'ReedemRequest'])->name('admin.reward.request');
        Route::post('/redeem/success/{pointId?}', [PointRedemptionController::class, 'redeemSuccess'])->name('redeem.success');
        Route::post('/redeem/cancel/{pointId}', [PointRedemptionController::class, 'cancelRedemption'])->name('redeem.cancel');
        Route::resource('companies', CompanyController::class);


        #claim
        Route::resource('claims', ClaimController::class);
        Route::resource('customers', CustomerController::class);
        Route::match(['get', 'post'], 'customers/{customer}/change-password', [CustomerController::class, 'changePassword'])->name('customers.changePassword');

    });
});
