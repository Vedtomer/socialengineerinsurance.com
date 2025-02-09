<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        try {
            // Get the fields from the request
            $fields = $request->input('fields', []);
            Log::info('fields data:', $fields);

            // Correct string concatenation
            $emailContent = "First Name: " . ($fields['first_name'] ?? 'Not provided') . "\n";
            $emailContent .= "Last Name: " . ($fields['last_name'] ?? 'Not provided') . "\n";
            $emailContent .= "Email: " . ($fields['email'] ?? 'Not provided') . "\n";
            $emailContent .= "Message: " . ($fields['teaxtarea'] ?? 'Not provided') . "\n";

            Log::info($emailContent);
            return;
            // Uncomment to actually send the email
            Mail::raw($emailContent, function ($message) {
                $message->to(config('mail.admin_email', 'your@email.com'))
                    ->subject('New Contact Form Submission');
            });

            // Return success response
            return response()->json([
                'msg' => 'ok',
                'code' => 200
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());

            return response()->json([
                'msg' => 'error',
                'code' => 500,
                'debug' => $e->getMessage()  // Only for development
            ], 500);
        }
    }
}
