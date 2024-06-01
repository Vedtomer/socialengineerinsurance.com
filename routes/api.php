<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/signup', 'signup')->name('signup');
    Route::post('/mobile/send-otp', 'sendMobileOtp')->name('mobile.send.otp');
    Route::post('/mobile/verify-otp', 'verifyMobileOtp')->name('mobile.verify.otp');
    
});


Route::middleware('auth:api')->controller(AuthController::class)->group(function () {
    Route::post('/pin/change', 'changePin')->name('change.pin');
    Route::post('/set/new/pin', 'setNewPin')->name('set.new.pin');
    Route::post('/verify/pin', 'verifyPin')->name('verify.pin');
});
