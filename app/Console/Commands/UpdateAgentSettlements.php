<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Policy;
use App\Models\Account;
use App\Models\AgentMonthlySettlement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAgentSettlements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:update-settlements {--month= : Process for specific month (format: YYYY-MM)}';

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
        
        // Determine which month to process
        $targetMonth = $this->option('month') 
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth() 
            : Carbon::now()->startOfMonth();
        
        $previousMonth = $targetMonth->copy()->subMonth();
        
        // Get all agents
        $agents = User::role('agent')->get();
        $bar = $this->output->createProgressBar(count($agents));
        $bar->start();
        
        foreach ($agents as $agent) {
            DB::beginTransaction();
            try {
                // Get or create settlement record for current month
                $settlement = AgentMonthlySettlement::firstOrNew([
                    'agent_id' => $agent->id,
                    'settlement_month' => $targetMonth,
                ]);
                
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
                
                // Get all previous months' accumulated settlements (not just the last month)
                // This helps us track carried forward dues from multiple months
                $previousSettlement = AgentMonthlySettlement::forAgent($agent->id)
                    ->previousMonth($targetMonth)
                    ->first();
                
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
                  if ( abs($totalPremiumDue) < 0.01 && 
                  abs($payments) < 0.01 && abs($carryForwardDue) < 0.01 && 
                  abs($totalAmountDue) < 0.01) {
                  DB::commit();
                  $bar->advance();
                  continue;
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
                


                $settlement->save();
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error processing agent ID {$agent->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Agent settlements updated successfully for ' . $targetMonth->format('F Y'));
        
        return 0;
    }
}