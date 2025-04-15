<?php

namespace App\Console\Commands;

use App\Models\Policy;
use App\Models\User;
use App\Models\MonthlyCommission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:generate 
                            {--agent_id= : Specific agent ID to generate commissions for} 
                            {--month= : Month number (1-12)} 
                            {--year= : Year (e.g. 2024)} 
                            {--from_date= : Start date in format Y-m-d} 
                            {--to_date= : End date in format Y-m-d}
                            {--current : Generate for current month}
                            {--last : Generate for last month}
                            {--force : Force update existing records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate or update monthly commission records for agents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting monthly commission calculation...');
        
        // Get options
        $agentId = $this->option('agent_id');
        $month = $this->option('month');
        $year = $this->option('year');
        $fromDate = $this->option('from_date');
        $toDate = $this->option('to_date');
        $current = $this->option('current');
        $last = $this->option('last');
        $force = $this->option('force');
        
        // Set default behavior for no specific options
        if (!$month && !$year && !$fromDate && !$toDate && !$current && !$last) {
            $current = true;
            $last = true;
        }
        
        $periods = [];
        
        // Handle date periods based on options
        if ($current) {
            $periods[] = [
                'month' => now()->month,
                'year' => now()->year,
                'start_date' => now()->startOfMonth()->format('Y-m-d'),
                'end_date' => now()->endOfMonth()->format('Y-m-d'),
            ];
        }
        
        if ($last) {
            $lastMonth = now()->subMonth();
            $periods[] = [
                'month' => $lastMonth->month,
                'year' => $lastMonth->year,
                'start_date' => $lastMonth->startOfMonth()->format('Y-m-d'),
                'end_date' => $lastMonth->endOfMonth()->format('Y-m-d'),
            ];
        }
        
        if ($month && $year) {
            $date = Carbon::createFromDate($year, $month, 1);
            $periods[] = [
                'month' => $month,
                'year' => $year,
                'start_date' => $date->startOfMonth()->format('Y-m-d'),
                'end_date' => $date->endOfMonth()->format('Y-m-d'),
            ];
        } elseif ($fromDate && $toDate) {
            // Custom date range
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);
            
            // Handle date ranges spanning multiple months
            while ($startDate->lessThanOrEqualTo($endDate)) {
                $monthStart = $startDate->copy()->startOfMonth();
                $monthEnd = min($startDate->copy()->endOfMonth(), $endDate);
                
                $periods[] = [
                    'month' => $monthStart->month,
                    'year' => $monthStart->year,
                    'start_date' => $monthStart->format('Y-m-d'),
                    'end_date' => $monthEnd->format('Y-m-d'),
                ];
                
                $startDate = $monthEnd->copy()->addDay();
                
                // If we've moved to the next month, break the loop
                if ($startDate->greaterThan($endDate)) {
                    break;
                }
            }
        }
        
        // Process each period
        foreach ($periods as $period) {
            $this->processPeriod($period, $agentId, $force);
        }
        
        $this->info('Monthly commission generation completed successfully!');
        
        return 0;
    }
    
    /**
     * Process a specific time period for commission calculation
     *
     * @param array $period
     * @param int|null $agentId
     * @param bool $force
     * @return void
     */
    protected function processPeriod(array $period, ?int $agentId = null, bool $force = false): void
    {
        $month = $period['month'];
        $year = $period['year'];
        $startDate = $period['start_date'];
        $endDate = $period['end_date'];
        
        $this->info("Processing commissions for period: {$startDate} to {$endDate}");
        
        // Query to get agents with policies in the given period
        $query = User::query()
            ->whereHas('policies', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('policy_start_date', [$startDate, $endDate]);
            });
        
        // Filter by specific agent if provided
        if ($agentId) {
            $query->where('id', $agentId);
        }
        
        $agents = $query->get();
        
        $bar = $this->output->createProgressBar($agents->count());
        $bar->start();
        
        foreach ($agents as $agent) {
            $this->calculateAgentCommission($agent, $month, $year, $startDate, $endDate, $force);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
    }
    
    /**
     * Calculate and store commission for a specific agent and period
     *
     * @param User $agent
     * @param int $month
     * @param int $year
     * @param string $startDate
     * @param string $endDate
     * @param bool $force
     * @return void
     */
    protected function calculateAgentCommission(User $agent, int $month, int $year, string $startDate, string $endDate, bool $force): void
    {
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Get policies for this agent in the given period
            $policies = Policy::where('agent_id', $agent->id)
                ->whereBetween('policy_start_date', [$startDate, $endDate])
                ->get();
            
            // If no policies, skip this agent for this period
            if ($policies->isEmpty()) {
                DB::rollBack();
                return;
            }
            
            // Calculate totals
            $totalPremium = $policies->sum('premium');
            $totalCommission = $policies->sum('agent_commission');
            $totalGst = $policies->sum('gst');
            $totalNetAmount = $policies->sum('net_amount');
            $totalAgentAmountDue = $policies->sum('agent_amount_due');
            $policiesCount = $policies->count();
            
            // Find or create monthly commission record
            $monthlyCommission = MonthlyCommission::firstOrNew([
                'agent_id' => $agent->id,
                'month' => $month,
                'year' => $year,
            ]);
            
            // If record exists and force not set, skip
            if ($monthlyCommission->exists && !$force) {
                DB::rollBack();
                return;
            }
            
            // Update or create record
            $monthlyCommission->fill([
                'total_premium' => $totalPremium,
                'total_commission' => $totalCommission,
                'total_gst' => $totalGst,
                'total_net_amount' => $totalNetAmount,
                'total_agent_amount_due' => $totalAgentAmountDue,
                'policies_count' => $policiesCount,
            ]);
            
            $monthlyCommission->save();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error calculating commission for agent ID {$agent->id}: " . $e->getMessage());
            $this->error("Error processing agent ID {$agent->id}: " . $e->getMessage());
        }
    }
}