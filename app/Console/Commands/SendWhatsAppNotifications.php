<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WhatsappMessageLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendWhatsAppNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-notifications {date? : The date for which to send notifications (format: Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp notifications to all users about their policies';

    // Add constants from the job
    private const WHATSAPP_API_VERSION = 'v20.0';
    private const PHONE_NUMBER_ID = '491697427350235';
    private const MESSAGE_DELAY_SECONDS = 5;
    private const MAX_MESSAGES_PER_MINUTE = 20;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Get date from argument or use today's date
            $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))
            : Carbon::yesterday();
            
            $this->info("Sending WhatsApp notifications for date: " . $date->format('Y-m-d'));

            // Reset or initialize the message send tracking
            $this->initializeMessageRateLimiting($date);

            // Get users with their policy data
            $users = User::role('agent')->where('status', 1)
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

            $this->info("Found " . $users->count() . " users to notify");

            $bar = $this->output->createProgressBar($users->count());
            $bar->start();

            $successCount = 0;
            $failedCount = 0;

            foreach ($users as $user) {
                // Check if message has already been sent today
                if ($this->hasMessageSentToday($user)) {
                    $this->line("\nWhatsApp message already sent today for user: " . $user->id);
                    $bar->advance();
                    continue;
                }

                // Check and respect rate limiting
                $this->checkAndWaitForRateLimit($date);

                try {
                    $messageLog = $this->prepareMessageLog($user);

                    if ($user->policy_count === 0) {
                        $lastPolicyDays = $this->getLastPolicyDays($user);
                        $payload = $this->prepareNoPolicyPayload($user, $lastPolicyDays);
                        $messageLog->message_type = 'no_policy';
                        $messageLog->days_since_last_policy = $lastPolicyDays;
                    } else {
                        $payload = $this->preparePolicyUpdatePayload($user, $date);
                        $messageLog->message_type = 'daily_report';
                        $messageLog->policy_count = $user->policy_count;
                        $messageLog->total_commission = $user->total_commission;
                    }

                    $messageLog->request_payload = json_encode($payload);
                    $messageLog->save();

                    // Send WhatsApp message
                    $response = $this->sendWhatsAppMessage($payload);

                    // Update log with response
                    $messageLog->update([
                        'is_successful' => true,
                        'response_body' => $response->json(),
                        'sent_at' => now()
                    ]);

                    // Increment message count
                    $this->incrementMessageCount($date);

                    $successCount++;

                } catch (\Exception $e) {
                    // Update log with error details
                    if (isset($messageLog)) {
                        $messageLog->update([
                            'is_successful' => false,
                            'error_message' => $e->getMessage()
                        ]);
                    }

                    $this->error("\nWhatsApp message failed for user: " . $user->id . " Error: " . $e->getMessage());
                    $failedCount++;
                    continue;
                }

                // Standard delay between messages
                sleep(self::MESSAGE_DELAY_SECONDS);
                $bar->advance();
            }

            $bar->finish();
            
            $this->info("\nNotifications completed: $successCount successful, $failedCount failed");
            return 0;

        } catch (\Exception $e) {
            $this->error('Command failed: ' . $e->getMessage());
            Log::error('WhatsApp notification command failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Initialize rate limiting tracking
     */
    private function initializeMessageRateLimiting($date): void
    {
        $cacheKey = 'whatsapp_messages_count_' . $date->format('Y-m-d');
        
        // Reset or initialize the message count for the day
        Cache::put($cacheKey, 0, now()->addDay());
    }

    /**
     * Check and wait if rate limit is approaching
     */
    private function checkAndWaitForRateLimit($date): void
    {
        $cacheKey = 'whatsapp_messages_count_' . $date->format('Y-m-d');
        $messageCount = Cache::get($cacheKey, 0);

        // If messages are approaching the limit, wait
        if ($messageCount >= self::MAX_MESSAGES_PER_MINUTE) {
            $this->info("\nWaiting due to rate limit: " . $messageCount . " messages sent");
            sleep(60); // Wait for a minute
            
            // Reset the count after waiting
            $this->initializeMessageRateLimiting($date);
        }
    }

    /**
     * Increment message count
     */
    private function incrementMessageCount($date): void
    {
        $cacheKey = 'whatsapp_messages_count_' . $date->format('Y-m-d');
        
        // Increment the message count
        Cache::increment($cacheKey);
    }

    /**
     * Prepare message log entry
     */
    private function prepareMessageLog($user): WhatsappMessageLog
    {
        return WhatsappMessageLog::create([
            'user_id' => $user->id,
            'mobile_number' => $user->mobile_number
        ]);
    }

    /**
     * Prepare payload for no policy message
     */
    private function prepareNoPolicyPayload($user, int $days): array
    {
        return [
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
    }

    /**
     * Prepare payload for policy update message
     */
    private function preparePolicyUpdatePayload($user, $date): array
    {
        return [
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
                                'text' => Carbon::parse($date)->format('d M Y')
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
    }

    /**
     * Send WhatsApp message using HTTP client with improved error handling
     */
    private function sendWhatsAppMessage(array $payload)
    {
        $apiEndpoint = sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            self::WHATSAPP_API_VERSION,
            self::PHONE_NUMBER_ID
        );

        try {
            $response = Http::withToken(config('services.facebook.access_token'))
                ->timeout(30) // Set a specific timeout for this request
                ->connectTimeout(10) // Set connection timeout
                ->post($apiEndpoint, $payload);

            if (!$response->successful()) {
                throw new \Exception('WhatsApp API request failed: ' . $response->body());
            }

            return $response;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection timeout specifically
            Log::error('WhatsApp API connection timeout: ' . $e->getMessage());
            throw $e;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle other request-related errors
            Log::error('WhatsApp API request error: ' . $e->getMessage());
            throw $e;
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

    private function hasMessageSentToday($user): bool
    {
        return WhatsappMessageLog::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->where('is_successful', 1)
            ->exists();
    }
}