<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiCustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



Route::prefix('agent')->group(function () {
    Route::post('login', [LoginController::class, 'agentLogin']);


    Route::post('signup', [LoginController::class, 'customerSignUp']);

    // Logout route accessible to any authenticated user
    Route::middleware(['auth:api'])->post('logout', [LoginController::class, 'agentLogout']);
    Route::match(['get', 'post'], '/approve-points-redemption/{id}', [ApiController::class, 'approvePointsRedemption']);
    // Routes restricted to users with the 'agent' role
    Route::middleware(['auth:api', 'role:agent|customer'])->group(function () {
        Route::match(['get', 'post'], '/home', [ApiController::class, 'index']);
        Route::match(['get', 'post'], '/get-claim', [ApiController::class, 'getClaim']);
        Route::match(['get', 'post'], '/slider', [ApiController::class, 'getActiveSliders']);
        Route::match(['get', 'post'], '/getPolicy', [ApiController::class, 'getPolicy']);
        Route::match(['get', 'post'], '/getPointsSummary', [ApiController::class, 'getPointsSummary']);
        Route::match(['get', 'post'], '/pointsRedemption', [ApiController::class, 'pointsRedemption']);
        Route::match(['get', 'post'], '/points-ledger', [ApiController::class, 'PointsLedger']);
        Route::match(['get', 'post'], '/pending-premium-ledger', [ApiController::class, 'PendingPremiumLedger']);
        Route::match(['get', 'post'], '/transaction/{id?}', [ApiController::class, 'Transaction']);
        Route::post('delete_account', [LoginController::class, 'DeleteAccount']);
    });



   
});

Route::post('/webhook', function (Request $request) {
    // Verify webhook signature
    $signature = $request->header('X-Webhook-Signature');
    $payload = $request->getContent();
    $calculatedSignature = hash_hmac('sha256', $payload, config('app.webhook_secret'));

    if (!hash_equals($calculatedSignature, $signature)) {
        Log::warning('Invalid webhook signature');
        return response('Invalid signature', 401);
    }

    // Parse and log the event
    $event = $request->json()->all();

    switch ($event['type'] ?? '') {
        case 'message.received':
            Log::info('New message received', ['data' => $event['data'] ?? null]);
            break;
        case 'message.status_update':
            Log::info('Message status updated', ['data' => $event['data'] ?? null]);
            break;
        default:
            Log::info('Unhandled event type', ['type' => $event['type'] ?? 'unknown']);
    }

    return response('Webhook received', 200);
});

// In app/Http/Middleware/VerifyCsrfToken.php
// protected $except = [
//     'api/webhook',
// ];

