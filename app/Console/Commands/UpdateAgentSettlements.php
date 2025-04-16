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
        
        // Get all policies for the target month
        $policies = Policy::whereMonth('policy_start_date', $targetMonth->month)
            ->whereYear('policy_start_date', $targetMonth->year)
            ->get();
        
        // Group policies by agent
        $policyDataByAgent = [];
        foreach ($policies as $policy) {
            $agentId = $policy->agent_id;
            
            if (!isset($policyDataByAgent[$agentId])) {
                $policyDataByAgent[$agentId] = [
                    'total_commission' => 0,
                    'total_premium_due' => 0,
                    'policies' => []
                ];
            }
            
            $policyDataByAgent[$agentId]['total_commission'] += $policy->agent_commission;
            
            // Calculate premium due based on payment type
            $premiumDue = 0;
            switch ($policy->payment_by) {
                case 'agent_full_payment':
                    $premiumDue = 0; // No premium due
                    break;
                case 'commission_deducted':
                    $premiumDue = 0; // No premium due
                    break;
                case 'pay_later_with_adjustment':
                    $premiumDue = $policy->premium - $policy->agent_commission; // Premium minus commission
                    break;
                case 'pay_later':
                    $premiumDue = $policy->premium; // Full premium due
                    break;
            }
            
            $policyDataByAgent[$agentId]['total_premium_due'] += $premiumDue;
            $policyDataByAgent[$agentId]['policies'][] = $policy;
        }
        
        // Process settlements for each agent
        $bar = $this->output->createProgressBar(count($policyDataByAgent));
        $bar->start();
        
        foreach ($policyDataByAgent as $agentId => $data) {
            DB::beginTransaction();
            try {
                // Get agent payments for this month
                $payments = Account::where('user_id', $agentId)
                    ->whereMonth('payment_date', $targetMonth->month)
                    ->whereYear('payment_date', $targetMonth->year)
                    ->sum('amount_paid');
                
                // Calculate pending amount for current month
                $pendingAmount = $data['total_premium_due'] - $payments;
                
                // Get previous month's settlement
                $previousSettlement = AgentMonthlySettlement::where('agent_id', $agentId)
                    ->where(function($query) use ($targetMonth) {
                        $query->where('year', $targetMonth->copy()->subMonth()->year)
                              ->where('month', $targetMonth->copy()->subMonth()->month);
                    })
                    ->first();
                
                $carryForwardDue = 0;
                if ($previousSettlement) {
                    $carryForwardDue = $previousSettlement->final_amount_due;
                }
                
                // Calculate final amount due
                $finalAmountDue = $pendingAmount + $carryForwardDue;
                
                // Only create/update settlement if there are significant amounts
                if (abs($pendingAmount) >= 0.01 || abs($carryForwardDue) >= 0.01 || abs($finalAmountDue) >= 0.01) {
                    // Create or update settlement record
                    $settlement = AgentMonthlySettlement::firstOrNew([
                        'agent_id' => $agentId,
                        'year' => $targetMonth->year,
                        'month' => $targetMonth->month,
                    ]);
                    
                    $settlement->total_commission = $data['total_commission'];
                    $settlement->total_premium_due = $data['total_premium_due'];
                    $settlement->amount_paid = $payments;
                    $settlement->pending_amount = $pendingAmount;
                    $settlement->carry_forward_due = $carryForwardDue;
                    $settlement->final_amount_due = $finalAmountDue;
                    
                    // Create detailed notes
                    $settlement->notes = "Month: {$targetMonth->format('F Y')} | " .
                        "Current Month Due: {$pendingAmount} | " .
                        "Previous Dues: {$carryForwardDue} | " .
                        "Total Due: {$finalAmountDue}";
                    
                    $settlement->save();
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error processing agent ID {$agentId}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Agent settlements updated successfully for ' . $targetMonth->format('F Y'));
        
        return 0;
    }
}