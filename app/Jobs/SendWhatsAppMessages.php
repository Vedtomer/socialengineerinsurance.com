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
            $date = $this->getDate();
            
            // Get users with their policy data
            $users = User::where('status', 1)
                ->where('whatsapp_notification', 1)
                ->withCount([
                    'Policy as policy_count' => function ($query) use ($date) {
                        $query->whereBetween('created_at', $date);
                    }
                ])
                ->withSum([
                    'Policy as total_commission' => function ($query) use ($date) {
                        $query->whereBetween('created_at', $date);
                    }
                ], 'agent_commission')
                ->get();
                dd($users);
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
    private function getDate(): string
    {
        return Carbon::today()->toDateString(); 
    }

    // private function getDateRange(): array
    // {
    //     $today = Carbon::today();
        
    //     if ($today->isMonday()) {
    //         return [
    //             'start' => $today->copy()->subWeek()->startOfWeek(),
    //             'end' => $today->copy()->subDay()->endOfDay(),
    //             'period_name' => 'Weekend'
    //         ];
    //     }
        
    //     return [
    //         'start' => $today->copy()->subDay()->startOfDay(),
    //         'end' => $today->copy()->subDay()->endOfDay(),
    //         'period_name' => "Yesterday's"
    //     ];
    // }

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
            . "Thank you for sharing {$user->policy_count}  with us on 📅: {$periodName}\n"
            . ". For these policies, you have earned 🎯" . number_format($user->total_commission, 2) . "points\n"
            . "We appreciate your efforts! 🙏\n\n"
            . "For any queries, contact us at\n"
            . " 📞 97287 86086.";
    }

    /**
     * Prepare no policy report template
     */
    private function prepareNoPolicyTemplate($user, int $days): string
    {
        return "Dear {$user->name}! 🛺\n\n"
            . "We noticed you haven’t shared any e-rickshaw insurance policies for the last {$days} days. 🚨\n"
            . "Please send them at the earliest to ensure your customers remain covered and avoid any delays. ⏳\n"
            . "Thanks for choosing Social Engineer Insurance! 🙏\n\n"
            . "Looking forward to receiving Policy soon. 🤝\n\n"
            . "Best regards, \n"
            . "Contact: 97287 86086 📞\n";
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