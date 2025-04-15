<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Policy;
use App\Models\Account;
use App\Models\AgentMonthlySettlement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateAgentSettlements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:update-settlements {--month= : Process for specific month (format: YYYY-MM)} {--debug : Show detailed debug information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update monthly settlement records for all agents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting agent settlement process...');
        $debug = $this->option('debug');
        
        // Determine which month to process
        $targetMonth = $this->option('month') 
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth() 
            : Carbon::now()->startOfMonth();
        
        $previousMonth = $targetMonth->copy()->subMonth();
        
        // Get all agents
        $agents = User::role('agent')->get();
        $processedAgents = 0;
        $skippedAgents = 0;
        $createdRecords = 0;
        $bar = $this->output->createProgressBar(count($agents));
        $bar->start();
        
        foreach ($agents as $agent) {
            DB::beginTransaction();
            try {
                // First check if this agent has any activity in the current month
                $hasCurrentMonthPolicies = Policy::where('agent_id', $agent->id)
                    ->whereMonth('policy_start_date', $targetMonth->month)
                    ->whereYear('policy_start_date', $targetMonth->year)
                    ->exists();
                
                $hasCurrentMonthPayments = Account::where('user_id', $agent->id)
                    ->whereMonth('payment_date', $targetMonth->month)
                    ->whereYear('payment_date', $targetMonth->year)
                    ->exists();
                    
                $previousSettlement = AgentMonthlySettlement::forAgent($agent->id)
                    ->previousMonth($targetMonth)
                    ->first();
                
                $hasPreviousDue = $previousSettlement && $previousSettlement->final_amount_due > 0;
                
                // Skip if no activity and no previous dues
                if (!$hasCurrentMonthPolicies && !$hasCurrentMonthPayments && !$hasPreviousDue) {
                    if ($debug) {
                        $this->info("\nSkipping agent ID {$agent->id}: No activity or previous dues");
                    }
                    $skippedAgents++;
                    $bar->advance();
                    continue;
                }
                
                // Calculate total commission and premiums for current month
                $policies = Policy::where('agent_id', $agent->id)
                    ->whereMonth('policy_start_date', $targetMonth->month)
                    ->whereYear('policy_start_date', $targetMonth->year)
                    ->get();
                
                // Reset calculated values
                $totalCommission = 0;
                $totalPremiumDue = 0;
                $payLaterAmount = 0;
                $payLaterWithAdjustmentAmount = 0;
                
                foreach ($policies as $policy) {
                    $totalCommission += $policy->agent_commission;
                    
                    if ($policy->payment_by == Policy::PAYMENT_BY_PAY_LATER) {
                        $payLaterAmount += $policy->premium;
                        $totalPremiumDue += $policy->premium;
                    }
                    
                    if ($policy->payment_by == Policy::PAYMENT_BY_PAY_LATER_ADJUSTED) {
                        $payLaterWithAdjustmentAmount += $policy->premium - $policy->agent_commission;
                        $totalPremiumDue += $policy->premium - $policy->agent_commission;
                    }
                }
                
                // Get payments made by agent during this month
                $payments = Account::where('user_id', $agent->id)
                    ->whereMonth('payment_date', $targetMonth->month)
                    ->whereYear('payment_date', $targetMonth->year)
                    ->sum('amount_paid');
                
                $previousMonthCommission = 0;
                $previousMonthDue = 0;
                $adjustedCommission = 0;
                $carryForwardDue = 0;
                
                // Calculate previous month's commission and accumulated dues
                if ($previousSettlement) {
                    // Get the pending amount from previous month (accumulated)
                    $previousMonthCommission = $previousSettlement->total_commission;
                    $previousMonthDue = $previousSettlement->final_amount_due;
                    $carryForwardDue = $previousMonthDue;
                    
                    // Check policies that need adjustment from previous month's commission
                    $adjustmentPolicies = Policy::where('agent_id', $agent->id)
                        ->where('settled_for_previous_month', 1)
                        ->whereMonth('policy_start_date', $targetMonth->month)
                        ->whereYear('policy_start_date', $targetMonth->year)
                        ->get();
                    
                    foreach ($adjustmentPolicies as $policy) {
                        // For policies marked for adjustment, deduct from previous month's commission
                        if ($previousMonthCommission > 0) {
                            // Adjust either the full premium or whatever commission is left, whichever is smaller
                            $adjustment = min($previousMonthCommission, $policy->premium);
                            $adjustedCommission += $adjustment;
                            $previousMonthCommission -= $adjustment;
                            $carryForwardDue -= $adjustment; // Reduce the carry forward due
                        }
                    }
                }
                
                // Calculate current month's pending amount
                $currentMonthPending = $totalPremiumDue - $payments;
                
                // Calculate final amount due (current month + carried forward - adjustments)
                $totalAmountDue = $currentMonthPending + $carryForwardDue;
                
                // Skip record creation if all values are zero or insignificant
                if (abs($totalCommission) < 0.01 && abs($totalPremiumDue) < 0.01 && 
                    abs($payments) < 0.01 && abs($carryForwardDue) < 0.01 && 
                    abs($totalAmountDue) < 0.01) {
                    if ($debug) {
                        $this->info("\nSkipping agent ID {$agent->id}: All values are zero");
                    }
                    // Delete any existing record for this month if it exists
                    // AgentMonthlySettlement::where('agent_id', $agent->id)
                    //     ->whereMonth('settlement_month', $targetMonth->month)
                    //     ->whereYear('settlement_month', $targetMonth->year)
                    //     ->delete();
                        
                    $skippedAgents++;
                    DB::commit();
                    $bar->advance();
                    continue;
                }
                
                // Check if a record already exists
                $existingSettlement = AgentMonthlySettlement::where('agent_id', $agent->id)
                    ->whereMonth('settlement_month', $targetMonth->month)
                    ->whereYear('settlement_month', $targetMonth->year)
                    ->first();
                
                if ($existingSettlement) {
                    // Update existing record
                    $settlement = $existingSettlement;
                } else {
                    // Create new record
                    $settlement = new AgentMonthlySettlement();
                    $settlement->agent_id = $agent->id;
                    $settlement->settlement_month = $targetMonth;
                    $createdRecords++;
                }
                
                // Update the settlement record with comprehensive tracking
                $settlement->total_commission = $totalCommission;
                $settlement->total_premium_due = $totalPremiumDue;
                $settlement->pay_later_amount = $payLaterAmount;
                $settlement->pay_later_with_adjustment_amount = $payLaterWithAdjustmentAmount;
                $settlement->amount_paid = $payments;
                $settlement->pending_amount = $currentMonthPending;
                $settlement->previous_month_commission = $previousMonthCommission;
                $settlement->adjusted_commission = $adjustedCommission;
                $settlement->carry_forward_due = $carryForwardDue;
                $settlement->final_amount_due = $totalAmountDue;
                
                // Add notes for clarity
                $settlement->notes = "Month: {$targetMonth->format('F Y')} | " .
                    "Current Month Due: {$currentMonthPending} | " .
                    "Previous Dues: {$carryForwardDue} | " .
                    "Adjusted Commission: {$adjustedCommission} | " .
                    "Total Due: {$totalAmountDue}";
                
                // Save the record and ensure it's actually saved
               
                $result = $settlement->save();
                Log::info($result);
                if (!$result) {
                    throw new \Exception("Failed to save settlement record for agent ID {$agent->id}");
                }
                
                if ($debug) {
                    $this->info("\nProcessed agent ID {$agent->id}: Final amount due: {$totalAmountDue}");
                }
                
                $processedAgents++;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("\nError processing agent ID {$agent->id}: " . $e->getMessage());
                Log::error("Agent settlement error: " . $e->getMessage(), [
                    'agent_id' => $agent->id,
                    'month' => $targetMonth->format('Y-m'),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("Agent settlements updated successfully for {$targetMonth->format('F Y')}");
        $this->info("Processed {$processedAgents} agents with activity");
        $this->info("Created {$createdRecords} new settlement records");
        $this->info("Skipped {$skippedAgents} agents with no activity");
        
        // Verify records in database
        $actualRecordCount = AgentMonthlySettlement::whereMonth('settlement_month', $targetMonth->month)
            ->whereYear('settlement_month', $targetMonth->year)
            ->count();
            
        $this->info("Total records in database for {$targetMonth->format('F Y')}: {$actualRecordCount}");
        
        return 0;
    }
}