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

// In routes/api.php
Route::match(['get', 'post'], '/webhook', function (Request $request) {
    // WhatsApp verification
    Log::info('Webhook received');
    if ($request->isMethod('get')) {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('app.webhook_verify_token')) {
            Log::info('WhatsApp webhook verified');
            return response($challenge, 200);
        }

        return response('Verification failed', 403);
    }

    // Handle incoming webhook
    if ($request->isMethod('post')) {
        // Verify webhook signature
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, config('app.webhook_secret'));

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid webhook signature');
            return response('Invalid signature', 401);
        }

        // Process the webhook payload
        $data = $request->json()->all();
        Log::info('Webhook received', ['data' => $data]);

        // Handle different types of messages or events here
        // For example:
        if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
            Log::info('New message received', ['message' => $message]);
            // Process the message
        }

        return response('Webhook processed', 200);
    }

    return response('Invalid request', 400);
});

// In app/Http/Middleware/VerifyCsrfToken.php
// protected $except = [
//     'api/webhook',
// ];

