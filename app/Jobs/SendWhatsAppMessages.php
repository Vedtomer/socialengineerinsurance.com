<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    private const WHATSAPP_API_VERSION = 'v20.0';
    private const PHONE_NUMBER_ID = '491697427350235';

    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $date = $this->date;

            // Get users with their policy data
            $users = User::where('status', 1)
                ->where('whatsapp_notification', 1)
                ->withCount([
                    'Policy as policy_count' => function ($query) use ($date) {
                        $query->whereDate('policy_start_date', '=', $date);
                    }
                ])
                ->withSum([
                    'Policy as total_commission' => function ($query) use ($date) {
                        $query->whereDate('policy_start_date', '=', $date);
                    }
                ], 'agent_commission')
                ->get();

            foreach ($users as $user) {
                try {
                    if ($user->policy_count === 0) {
                        $lastPolicyDays = $this->getLastPolicyDays($user);
                        $this->sendNoPolicyMessage($user, $lastPolicyDays);
                    } else {
                        $this->sendPolicyUpdateMessage($user);
                    }

                    // Add small delay to prevent API rate limiting
                    sleep(1);

                    Log::info('WhatsApp message sent successfully for user: ' . $user->id);
                } catch (\Exception $e) {
                    Log::error('WhatsApp message failed for user: ' . $user->id . ' Error: ' . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error('Batch WhatsApp message job failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get days since last policy
     */
    private function getLastPolicyDays($user): int
    {
        $lastPolicy = $user->Policy()
            ->latest('policy_start_date')
            ->first();

        if (!$lastPolicy) {
            return 30; // If no policy found, return maximum days
        }

        return Carbon::parse($lastPolicy->policy_start_date)->diffInDays(Carbon::today());
    }

    /**
     * Send WhatsApp message for user with no policies
     */
    private function sendNoPolicyMessage($user, int $days): void
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($user->mobile_number),
            'type' => 'template',
            'template' => [
                'name' => 'no_policy_reminder',
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $user->name
                            ],
                            [
                                'type' => 'text',
                                'text' => (string)$days
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->sendWhatsAppMessage($payload);
    }

    /**
     * Send WhatsApp message for user with policies
     */
    private function sendPolicyUpdateMessage($user): void
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($user->mobile_number),
            'type' => 'template',
            'template' => [
                'name' => 'daily_report',
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $user->name
                            ],
                            [
                                'type' => 'text',
                                'text' => $user->policy_count . ' policies'
                            ],
                            [
                                'type' => 'text',
                                'text' => Carbon::parse($this->date)->format('d M Y')
                            ],
                            [
                                'type' => 'text',
                                'text' =>  number_format($user->total_commission, 2)
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $this->sendWhatsAppMessage($payload);
    }

    /**
     * Send WhatsApp message using HTTP client
     */
    private function sendWhatsAppMessage(array $payload): void
    {
        $apiEndpoint = sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            self::WHATSAPP_API_VERSION,
            self::PHONE_NUMBER_ID
        );

        $response = Http::withToken(config('services.facebook.access_token'))
            ->post($apiEndpoint, $payload);

        if (!$response->successful()) {
            throw new \Exception('WhatsApp API request failed: ' . $response->body());
        }
    }

    /**
     * Format phone number for WhatsApp API
     */
    private function formatPhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            throw new \Exception('Phone number is required');
        }

        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if needed (for India)
        if (strlen($phone) === 10) {
            return '91' . $phone;
        }

        return $phone;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('WhatsApp message job failed: ' . $exception->getMessage());
    }
}
