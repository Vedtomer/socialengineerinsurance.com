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

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $dateRange = $this->getDateRange();
            
            // Get users with their policy data
            $users = User::where('status', 1)
                ->where('whatsapp_notification', 1)
                ->withCount([
                    'Policy as policy_count' => function ($query) use ($dateRange) {
                        $query->whereBetween('policy_start_date', [$dateRange['start'], $dateRange['end']]);
                    }
                ])
                ->withSum([
                    'Policy as total_commission' => function ($query) use ($dateRange) {
                        $query->whereBetween('policy_start_date', [$dateRange['start'], $dateRange['end']]);
                    }
                ], 'agent_commission')
                ->get();

            foreach ($users as $user) {
                try {
                    if ($user->policy_count === 0) {
                        $lastPolicyDays = $this->getLastPolicyDays($user);
                        $this->sendNoPolicyMessage($user, $lastPolicyDays);
                    } else {
                        $this->sendPolicyUpdateMessage($user, $dateRange['period_name']);
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
     * Get date range based on current day
     */
    private function getDateRange(): array
    {
        $today = Carbon::today();
        
        if ($today->isMonday()) {
            return [
                'start' => $today->copy()->subWeek()->startOfWeek(),
                'end' => $today->copy()->subDay()->endOfDay(),
                'period_name' => 'Weekend'
            ];
        }
        
        return [
            'start' => $today->copy()->subDay()->startOfDay(),
            'end' => $today->copy()->subDay()->endOfDay(),
            'period_name' => "Yesterday's"
        ];
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
        $message = $this->prepareNoPolicyTemplate($user, $days);
        $this->sendWhatsAppMessage($user, $message);
    }

    /**
     * Send WhatsApp message for user with policies
     */
    private function sendPolicyUpdateMessage($user, string $periodName): void
    {
        $message = $this->prepareDailyPolicyTemplate($user, $periodName);
        $this->sendWhatsAppMessage($user, $message);
    }

    /**
     * Prepare daily policy report template
     */
    private function prepareDailyPolicyTemplate($user, string $periodName): string
    {
        return "Dear {$user->name}! 🛺\n\n"
            . "{$periodName} Update:\n"
            . "✅ Policies Issued: {$user->policy_count}\n"
            . "💰 Points Earned: ₹" . number_format($user->total_commission, 2) . "\n"
            . "⭐ Keep up the great work!\n\n"
            . "Quick Tips:\n"
            . "📱 Every e-rickshaw inquiry = Our opportunity\n"
            . "💫 Each policy increases your rewards\n"
            . "⚡ Just 5 minutes per customer\n\n"
            . "Let's aim higher tomorrow! 🎯";
    }

    /**
     * Prepare no policy report template
     */
    private function prepareNoPolicyTemplate($user, int $days): string
    {
        return "Dear {$user->name}! 🛺\n\n"
            . "Policy Report Alert:\n"
            . "⚠️ No policies in {$days} days\n"
            . "💡 Your rewards are waiting!\n\n"
            . "Remember:\n"
            . "🎯 Every e-rickshaw owner needs protection\n"
            . "⚡ Only 5 minutes to secure each policy\n"
            . "💰 Daily policies = Regular income\n\n"
            . "Ready to restart? We're here to help!\n"
            . "Connect with Us for support.\n\n"
            . "Together we achieve more! 🎯";
    }

    /**
     * Send WhatsApp message
     */
    private function sendWhatsAppMessage($user, string $message): void
    {
        if (empty($user->mobile_number)) {
            Log::warning('No phone number found for user: ' . $user->id);
            return;
        }

        $response = Http::post(config('services.whatsapp.endpoint'), [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($user->mobile_number),
            'type' => 'text',
            'text' => [
                'body' => $message
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.whatsapp.token'),
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('WhatsApp API call failed: ' . $response->body());
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