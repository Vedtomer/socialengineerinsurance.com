<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\CustomerPolicy;
use Carbon\Carbon;

class PolicyExpirationTask extends Command
{
    protected $signature = 'app:policy-expiration-task';
    protected $description = 'Send individual WhatsApp notifications for policies expiring in the next 7 days to users with customer role';

    public function handle()
    {
        try {
            Log::info('Policy expiration task ran at: ' . now());

            $today = Carbon::now();
            $expiryIn7Days = $today->copy()->addDays(7)->endOfDay();

            Log::info('Date Range for Policy Expiration:', [
                'Expiring in 7 Days' => [
                    'Start Date' => $today->format('Y-m-d'),
                    'End Date' => $expiryIn7Days->format('Y-m-d')
                ]
            ]);

            // Get all users with 'customer' role
            $customers = User::role('customer')->get();
            
            // For each customer, get their policies expiring in 7 days
            foreach ($customers as $customer) {
                // Get policies for this customer expiring in next 7 days
                $expiringPolicies = CustomerPolicy::where('user_id', $customer->id)
                    ->where('policy_end_date', '<=', $expiryIn7Days)
                    ->where('policy_end_date', '>=', $today->copy()->startOfDay())
                    ->where('status', 'active') // Only consider active policies
                    ->get();
                
                // Send notification for each policy
                foreach ($expiringPolicies as $policy) {
                    $this->sendPolicyExpirationNotification(
                        $customer->name,
                        $policy->policy_holder_name ?? $customer->name, // Use policy holder name or fallback to customer name
                        $policy->policy_number,
                        Carbon::parse($policy->policy_end_date)->format('Y-m-d'),
                        $customer->phone_number ?? $customer->mobile // Try both fields in case one is used over the other
                    );
                    
                    // Add small delay between messages
                    sleep(2);
                }
            }

            echo "Policy expiration notifications sent successfully!\n";
            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Error in policy expiration task: ' . $e->getMessage());
            echo "Error occurred while executing policy expiration task!\n";
            return Command::FAILURE;
        }
    }

    private function sendPolicyExpirationNotification($userName, $policyHolderName, $policyNumber, $expiryDate, $phoneNumber)
    {
        if (empty($phoneNumber)) {
            Log::warning("Cannot send notification to user $userName - no phone number available");
            return;
        }

        $url = 'https://graph.facebook.com/v20.0/491697427350235/messages';

        // Format the message with the required information
        $message = "Dear $userName, policy for $policyHolderName with policy number $policyNumber will expire on $expiryDate.";
        
        // Clean the message text
        $message = str_replace(["\n", "\r", "\t"], " ", $message);
        $message = preg_replace('/\s+/', ' ', $message);
        $message = trim($message);
        Log::info($message);

        $data = [
            'messaging_product' => 'whatsapp',
            //'to' => "+91" . $phoneNumber,
            'to' => "+91" . env('Whatsapp_admin_phone'),
            'type' => 'template',
            'template' => [
                'name' => 'policy_expiry_notification', // Create this template in WhatsApp Business API
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $userName
                            ],
                            [
                                'type' => 'text',
                                'text' => $policyHolderName
                            ],
                            [
                                'type' => 'text',
                                'text' => $policyNumber
                            ],
                            [
                                'type' => 'text',
                                'text' => $expiryDate
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . env('FACEBOOK_ACCESS_TOKEN'),
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            Log::info("WhatsApp notification sent successfully to $userName for policy $policyNumber");
            Log::info('Response: ' . $response);
        } else {
            $error = curl_error($ch);
            Log::error("Failed to send WhatsApp notification to $userName for policy $policyNumber. HTTP Code: $httpCode. Error: $error");
            Log::error('Response: ' . $response);
        }

        curl_close($ch);
    }
}