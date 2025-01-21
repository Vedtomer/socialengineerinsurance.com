<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CustomTask extends Command
{
    protected $signature = 'app:custom-task';

    protected $description = 'Execute the custom task';

    public function handle()
    {
        // Add your task logic here
        Log::info('Custom task ran at: ' . now());

        // Using direct string output instead of info() method
        echo "Custom task executed successfully!\n";

        return Command::SUCCESS;
    }
}
