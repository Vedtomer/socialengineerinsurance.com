<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Policy;
use Carbon\Carbon;

class PolicyExpirationTask extends Command
{
    protected $signature = 'app:policy-expiration-task';
    protected $description = 'Send WhatsApp notifications for policies expiring this month and in 7 days';
    private $maxLength = 900; // Safe limit for WhatsApp message length

    public function handle()
    {
        try {
            Log::info('Policy expiration task ran at: ' . now());

            $today = Carbon::now();
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();
            $expiryIn7Days = $today->copy()->addDays(7)->endOfDay();

            Log::info('Date Ranges for Policy Expiration:', [
                'This Month' => [
                    'Start' => $startOfMonth->format('Y-m-d'),
                    'End' => $endOfMonth->format('Y-m-d')
                ],
                'Expiring in 7 Days' => [
                    'End Date' => $expiryIn7Days->format('Y-m-d')
                ]
            ]);

            $expiringPolicies = [
                'thisMonth' => [],
                'next7Days' => []
            ];

            // Policies expiring this month
            $policiesThisMonth = Policy::with('customer') // Assuming you have 'customer' relationship
                ->whereMonth('policy_end_date', $today->month)
                ->whereYear('policy_end_date', $today->year)
                ->get();

            foreach ($policiesThisMonth as $policy) {
                $customerName = $policy->customer->name ?? 'Customer Name Not Found'; // Get customer name, handle null
                $expiringPolicies['thisMonth'][] = [
                    'policy_no' => $policy->policy_no,
                    'customer_name' => $customerName,
                    'end_date' => $policy->policy_end_date->format('Y-m-d')
                ];
            }

            // Policies expiring in next 7 days
            $policiesNext7Days = Policy::with('customer') // Assuming you have 'customer' relationship
                ->where('policy_end_date', '<=', $expiryIn7Days)
                ->where('policy_end_date', '>=', $today->copy()->startOfDay()) // To only include future dates from today onwards
                ->get();

            foreach ($policiesNext7Days as $policy) {
                $customerName = $policy->customer->name ?? 'Customer Name Not Found'; // Get customer name, handle null
                $expiringPolicies['next7Days'][] = [
                    'policy_no' => $policy->policy_no,
                    'customer_name' => $customerName,
                    'end_date' => $policy->policy_end_date->format('Y-m-d')
                ];
            }

            // Send grouped reports for policy expiration
            $this->sendExpirationReports($expiringPolicies);

            echo "Policy expiration task executed successfully!\n";
            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Error in policy expiration task: ' . $e->getMessage());
            echo "Error occurred while executing policy expiration task!\n";
            return Command::FAILURE;
        }
    }

    private function sendExpirationReports($expiringPolicies)
    {
        // Report for policies expiring this month
        if (!empty($expiringPolicies['thisMonth'])) {
            $thisMonthMessages = array_map(function ($policy) {
                return "Policy No: {$policy['policy_no']}, Customer: {$policy['customer_name']}, Expiry: {$policy['end_date']}";
            }, $expiringPolicies['thisMonth']);
            $this->sendChunkedExpirationMessages("Policies Expiring This Month: ", $thisMonthMessages, count($expiringPolicies['thisMonth']), 'policy_expiry_summary_month');
            sleep(5); // Delay between groups
        }


        // Report for policies expiring in 7 days
        if (!empty($expiringPolicies['next7Days'])) {
            $next7DaysMessages = array_map(function ($policy) {
                return "Policy No: {$policy['policy_no']}, Customer: {$policy['customer_name']}, Expiry: {$policy['end_date']}";
            }, $expiringPolicies['next7Days']);
            $this->sendChunkedExpirationMessages("Policies Expiring in 7 Days: ", $next7DaysMessages, count($expiringPolicies['next7Days']), 'policy_expiry_summary_7_days');
            sleep(5);
        }
    }


    private function sendChunkedExpirationMessages($prefix, $messages, $totalCount, $templateName)
    {
        $currentChunk = [];
        $currentLength = strlen($prefix);
        $chunkNumber = 1;
        $totalChunks = ceil(array_sum(array_map('strlen', $messages)) / $this->maxLength);


        foreach ($messages as $message) {
            $messageLength = strlen($message) + 2; // + 2 for separator " | "
            if (($currentLength + $messageLength) > $this->maxLength) {
                // Send current chunk
                $chunkText = $prefix . "(" . $chunkNumber . "/" . $totalChunks . "): " . implode(" | ", $currentChunk);
                $this->sendWhatsAppExpirationNotification($chunkText, $totalCount, $templateName);

                // Reset for next chunk
                $currentChunk = [$message];
                $currentLength = strlen($prefix) + $messageLength;
                $chunkNumber++;
                sleep(30); // Delay between chunks
            } else {
                $currentChunk[] = $message;
                $currentLength += $messageLength;
            }
        }

        // Send remaining messages if any
        if (!empty($currentChunk)) {
            $chunkText = $prefix . "(" . $chunkNumber . "/" . $totalChunks . "): " . implode(" | ", $currentChunk);
            $this->sendWhatsAppExpirationNotification($chunkText, $totalCount, $templateName);
        }
    }

    private function sendWhatsAppExpirationNotification($report, $policyCount, $templateName)
    {
        $url = 'https://graph.facebook.com/v20.0/491697427350235/messages';

        // Clean the report text
        $report = str_replace(["\n", "\r", "\t"], " ", $report);
        $report = preg_replace('/\s+/', ' ', $report);
        $report = trim($report);


        $data = [
            'messaging_product' => 'whatsapp',
            'to' => "+91" . env('Whatsapp_admin_phone'),
            'type' => 'template',
            'template' => [
                'name' => $templateName, // Use dynamic template name
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $report
                            ],
                            [
                                'type' => 'text',
                                'text' => (string)$policyCount
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
            Log::info('WhatsApp notification sent successfully for template: '.$templateName);
            Log::info('Response: ' . $response);
        } else {
            $error = curl_error($ch);
            Log::error('Failed to send WhatsApp notification for template: '.$templateName.'. HTTP Code: ' . $httpCode . '. Error: ' . $error);
            Log::error('Response: ' . $response);
        }

        curl_close($ch);
    }
}