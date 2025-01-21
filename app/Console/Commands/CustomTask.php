<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;

class CustomTask extends Command
{
    protected $signature = 'app:custom-task';
    protected $description = 'Execute the custom task';
    private $maxLength = 900; // Safe limit below WhatsApp's 1024 limit



    public function handle()
    {
        try {
            Log::info('Custom task ran at: ' . now());

            // Calculate date ranges
            $today = Carbon::now();
            $currentWeekStart = $today->copy()->startOfWeek()->startOfDay();
            $lastWeekStart = $currentWeekStart->copy()->subWeek();

            // Calculate end dates
            $currentWeekEnd = $today->copy()->endOfDay();
            $lastWeekEnd = $lastWeekStart->copy()->endOfWeek()->endOfDay();

            Log::info('Date Ranges:', [
                'Current Week' => [
                    'Start' => $currentWeekStart->format('Y-m-d'),
                    'End' => $currentWeekEnd->format('Y-m-d')
                ],
                'Last Week' => [
                    'Start' => $lastWeekStart->format('Y-m-d'),
                    'End' => $lastWeekEnd->format('Y-m-d')
                ]
            ]);

            $agents = User::role('agent')->get();

            $groupedAgents = [
                'changes' => [],
                'same' => [],
                'dead' => []
            ];

            foreach ($agents as $agent) {
                $thisWeekCount = Policy::where('agent_id', $agent->id)
                    ->whereDate('policy_start_date', '>=', $currentWeekStart->format('Y-m-d'))
                    ->whereDate('policy_start_date', '<=', $currentWeekEnd->format('Y-m-d'))
                    ->count();

                $lastWeekCount = Policy::where('agent_id', $agent->id)
                    ->whereDate('policy_start_date', '>=', $lastWeekStart->format('Y-m-d'))
                    ->whereDate('policy_start_date', '<=', $lastWeekEnd->format('Y-m-d'))
                    ->count();

                // Log for debugging
                Log::info("Agent Counts: {$agent->name}", [
                    'This Week' => $thisWeekCount,
                    'Last Week' => $lastWeekCount
                ]);

                if ($thisWeekCount == 0 && $lastWeekCount == 0) {
                    $groupedAgents['dead'][] = [
                        'name' => $agent->name,
                        'message' => "{$agent->name}"
                    ];
                } elseif ($thisWeekCount != $lastWeekCount) {
                    $trend = $thisWeekCount > $lastWeekCount ? "up" : "down";
                    $groupedAgents['changes'][] = [
                        'name' => $agent->name,
                        'message' => "{$agent->name}: {$lastWeekCount}->{$thisWeekCount} {$trend}"
                    ];
                } else {
                    $groupedAgents['same'][] = [
                        'name' => $agent->name,
                        'message' => "{$agent->name}: {$lastWeekCount}->{$thisWeekCount} same"
                    ];
                }
            }
            // Modified sendGroupReports method
            $this->sendModifiedGroupReports($groupedAgents);

            echo "Custom task executed successfully!\n";
            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Error in custom task: ' . $e->getMessage());
            echo "Error occurred while executing custom task!\n";
            return Command::FAILURE;
        }
    }

    private function sendModifiedGroupReports($groupedAgents)
    {
        // Process agents with changes
        // Log::info($groupedAgents);die;
        if (!empty($groupedAgents['changes'])) {
            $changeMessages = array_map(fn($agent) => $agent['message'], $groupedAgents['changes']);
            $this->sendChunkedMessages("Agent Performance Changes: ", $changeMessages, count($groupedAgents['changes']));
        }

        sleep(5); // Delay between groups

        // Process stable agents
        if (!empty($groupedAgents['same'])) {
            $sameMessages = array_map(fn($agent) => $agent['message'], $groupedAgents['same']);
            $this->sendChunkedMessages("Stable Agents: ", $sameMessages, count($groupedAgents['same']));
        }

        sleep(5); // Delay between groups

        // Process inactive agents
        if (!empty($groupedAgents['dead'])) {
            $deadMessages = array_map(fn($agent) => $agent['message'], $groupedAgents['dead']);
            $this->sendChunkedMessages("Inactive Agents: ", $deadMessages, count($groupedAgents['dead']));
        }
    }

    private function sendChunkedMessages($prefix, $messages, $totalCount)
    {
        $currentChunk = [];
        $currentLength = strlen($prefix);
        $chunkNumber = 1;
        $totalChunks = ceil(array_sum(array_map('strlen', $messages)) / $this->maxLength);

        foreach ($messages as $message) {
            $messageLength = strlen($message) + 2; // +2 for separator " | "

            if (($currentLength + $messageLength) > $this->maxLength) {
                // Send current chunk
                $chunkText = $prefix . "(" . $chunkNumber . "/" . $totalChunks . "): " . implode(" | ", $currentChunk);
                $this->sendWhatsAppNotification($chunkText, $totalCount, 0);

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
            $this->sendWhatsAppNotification($chunkText, $totalCount, 0);
        }
    }

    private function sendWhatsAppNotification($report, $upCount, $downCount)
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
                'name' => 'sales_performance_summary',
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
                                'text' => (string)$upCount
                            ],
                            [
                                'type' => 'text',
                                'text' => (string)$downCount
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
            Log::info('WhatsApp notification sent successfully');
            Log::info('Response: ' . $response);
        } else {
            $error = curl_error($ch);
            Log::error('Failed to send WhatsApp notification. HTTP Code: ' . $httpCode . '. Error: ' . $error);
            Log::error('Response: ' . $response);
        }

        curl_close($ch);
    }
}
