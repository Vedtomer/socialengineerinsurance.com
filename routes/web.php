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
use App\Http\Controllers\CustomerPolicyController;
use App\Http\Controllers\InsuranceProductController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ContactController;

use App\Console\Commands\CustomTask;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Artisan;



Route::get('/cron', function () {
    Artisan::call('schedule:run');
    return response()->json(['message' => 'Cron job executed successfully.']);
})->name('cron');


Route::get('/', [WebsiteController::class, 'home'])->name('homepage');
Route::get('/e-rickshaw-insurance', [WebsiteController::class, 'eRickshawInsurance'])->name('e_rickshaw_insurance');
Route::get('/insurance', [WebsiteController::class, 'insurance'])->name('insurance');
Route::get('/health-insurance', [WebsiteController::class, 'healthInsurance'])->name('health_insurance');
Route::get('/two-wheeler-insurance', [WebsiteController::class, 'twoWheelerInsurance'])->name('two_wheeler_insurance');
Route::get('/home-insurance', [WebsiteController::class, 'homeInsurance'])->name('home_insurance');
Route::get('/private-car-insurance', [WebsiteController::class, 'privateCarInsurance'])->name('private_car_insurance');
Route::get('/about', [WebsiteController::class, 'about'])->name('about-us');
Route::get('/contact-us', [WebsiteController::class, 'contact'])->name('contact-us');



Route::post('/contact', [ContactController::class, 'submit']);







Route::prefix('policies')->group(function () {
    Route::get('/privacy-policy', function () {
        return view('pages.website.privacy_policy');
    })->name('privacy-policy');
    Route::get('/terms-of-service', function () {
        return view('pages.website.terms_of_service');
    })->name('terms-of-service');
});


Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('login');
    Route::post('/send-otp', [AdminController::class, 'SendOtp'])->name('admin.SendOtp');
    Route::post('/verify-otp', [AdminController::class, 'verifyOtp'])->name('admin.verifyOtp');
    Route::middleware(['role:admin', 'auth'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/profile/edit', [AdminController::class, 'ProfileEdit'])->name('edit.profile');
        Route::post('/profile/update', [AdminController::class, 'ProfileUpdate'])->name('admin.update');
        Route::match(['get', 'post'], '/dashboard', [AdminController::class, 'dashboard'])->name('admin.analytics');

        #account
        // Transaction Routes
        Route::get('/admin/transaction', [App\Http\Controllers\TransactionController::class, 'index'])->name('admin.transaction');
        Route::get('/admin/transaction/create', [App\Http\Controllers\TransactionController::class, 'create'])->name('transaction.create');
        Route::post('/admin/transaction', [App\Http\Controllers\TransactionController::class, 'store'])->name('add.transaction');
        Route::get('/admin/transaction/edit/{transaction}', [App\Http\Controllers\TransactionController::class, 'edit'])->name('transaction.edit');
        Route::put('/admin/transaction/{transaction}', [App\Http\Controllers\TransactionController::class, 'update'])->name('update.transaction');
        Route::delete('/admin/transaction/{transaction}', [App\Http\Controllers\TransactionController::class, 'destroy'])->name('transaction.delete');

        // API routes for dynamic data
        Route::get('/admin/agent/policies', [App\Http\Controllers\TransactionController::class, 'getAgentPolicies'])->name('get.agent.policies');



        Route::match(['get', 'post'], 'agent-edit/{id}', [AgentController::class, 'AgentEdit'])->name('agent.edit');
        Route::match(['get', 'post'], 'change-password/{id}', [AgentController::class, 'ChangePassword'])->name('agent.change.password');

        Route::match(['get', 'post'], '/agent-pandding-balance', [PolicyController::class, 'panddingblance'])->name('agentpandding.blance');

        #manage policy
        Route::match(['get', 'post'], '/upload-policy', [PolicyController::class, 'upload'])->name('admin.upload');
        Route::match(['get', 'post'], '/updateagentid/{royalsundaram_id?}/{agent_id?}', [AgentController::class, 'updateagentid'])->name('updateagentid');
        Route::match(['get', 'post'], '/policy-list', [PolicyController::class, 'PolicyList'])->name('admin.policy_list');
        Route::get('/royalsundaram/{id?}', [AdminController::class, 'royalsundaram'])->name('royalsundaram');
        Route::match(['get', 'post'], '/policy-pdf-upload', [PolicyController::class, 'policyUpload'])->name('admin.policy_pdf_upload');
        Route::post('/policy-list/delete/{id}', [PolicyController::class, 'policyDelete']);



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
        Route::resource('customer-policies', CustomerPolicyController::class);
        Route::resource('insurance-products', InsuranceProductController::class);


        Route::get('/policy-rates', [PolicyController::class, 'showPolicyRates'])->name('admin.dashboard');



        Route::get('/logs', [AdminController::class, 'WhatsappMessageLog'])->name('WhatsappMessageLog');
        Route::get('/app-activity', [AdminController::class, 'AppActivity'])->name('admin.app-activity');
    });
});
