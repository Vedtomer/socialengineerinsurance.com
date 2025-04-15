<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MonthlyCommission;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyCommissionController extends Controller
{
    /**
     * Handle all monthly commission operations
     *
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function handle(Request $request, $id = null)
    {
        // If ID is provided, show the detailed view for the specific commission
        if ($id) {
            return $this->showCommissionDetails($id);
        }
        
        // Otherwise, show the index/dashboard view
        return $this->showDashboard($request);
    }

    /**
     * Shows the commission details for a specific record
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    private function showCommissionDetails($id)
    {
        $commission = MonthlyCommission::with('agent')->findOrFail($id);
        
        // Get all policies for this agent in this month/year
        $startDate = Carbon::createFromDate($commission->year, $commission->month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($commission->year, $commission->month, 1)->endOfMonth();
        
        $policies = Policy::where('agent_id', $commission->agent_id)
            ->whereBetween('policy_start_date', [$startDate, $endDate])
            ->orderBy('policy_start_date')
            ->get();
            
        return view('commissions.show', compact('commission', 'policies'));
    }

    /**
     * Shows the monthly commissions dashboard
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    private function showDashboard(Request $request)
    {
        // Get available years (start from 2024)
        $years = range(2024, now()->year);
        
        // Get filters from request
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $agentId = $request->input('agent_id');
        
        // Get all agents for filter dropdown
        $agents = User::whereHas('policies')->orderBy('name')->get();
        
        // Base query for monthly commissions
        $query = MonthlyCommission::with('agent')
            ->where('year', $year)
            ->when($month, function($q) use ($month) {
                if ($month != 'all') {
                    $q->where('month', $month);
                }
            })
            ->when($agentId, function($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            });
        
        // Get monthly commissions
        $monthlyCommissions = $query->orderBy('year')
            ->orderBy('month')
            ->orderBy('agent_id')
            ->get();
            
        // Calculate totals for current filter
        $totals = MonthlyCommission::where('year', $year)
            ->when($month != 'all', function($q) use ($month) {
                $q->where('month', $month);
            })
            ->when($agentId, function($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            })
            ->select(
                DB::raw('SUM(total_premium) as total_premium'),
                DB::raw('SUM(total_commission) as total_commission'),
                DB::raw('SUM(total_gst) as total_gst'),
                DB::raw('SUM(total_net_amount) as total_net_amount'),
                DB::raw('SUM(total_agent_amount_due) as total_agent_amount_due'),
                DB::raw('SUM(total_payout) as total_payout'),
                DB::raw('SUM(policies_count) as policies_count'),
                DB::raw('COUNT(*) as agent_count')
            )
            ->first();
            
        // Get monthly stats for charts
        $monthlyStats = [];
        if ($month == 'all') {
            $monthlyStats = MonthlyCommission::where('year', $year)
                ->when($agentId, function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->select(
                    'month',
                    DB::raw('SUM(total_premium) as total_premium'),
                    DB::raw('SUM(total_commission) as total_commission'),
                    DB::raw('SUM(total_payout) as total_payout'),
                    DB::raw('SUM(policies_count) as policies_count')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    $item->month_name = date('F', mktime(0, 0, 0, $item->month, 10));
                    return $item;
                });
        }
        
        // Top 5 agents by commission
        $topAgents = MonthlyCommission::with('agent')
            ->where('year', $year)
            ->when($month != 'all', function($q) use ($month) {
                $q->where('month', $month);
            })
            ->select(
                'agent_id',
                DB::raw('SUM(total_commission) as total_commission'),
                DB::raw('SUM(policies_count) as policies_count')
            )
            ->groupBy('agent_id')
            ->orderByDesc('total_commission')
            ->limit(5)
            ->get();
            
        // Current month vs previous month comparison
        $currentMonthData = null;
        $previousMonthData = null;
        
        if ($month != 'all') {
            $currentMonthData = MonthlyCommission::where('year', $year)
                ->where('month', $month)
                ->when($agentId, function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->select(
                    DB::raw('SUM(total_premium) as total_premium'),
                    DB::raw('SUM(total_commission) as total_commission'),
                    DB::raw('SUM(total_payout) as total_payout'),
                    DB::raw('SUM(policies_count) as policies_count')
                )
                ->first();
                
            // Calculate previous month
            $previousDate = Carbon::createFromDate($year, $month, 1)->subMonth();
            $previousMonth = $previousDate->month;
            $previousYear = $previousDate->year;
            
            $previousMonthData = MonthlyCommission::where('year', $previousYear)
                ->where('month', $previousMonth)
                ->when($agentId, function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->select(
                    DB::raw('SUM(total_premium) as total_premium'),
                    DB::raw('SUM(total_commission) as total_commission'),
                    DB::raw('SUM(total_payout) as total_payout'),
                    DB::raw('SUM(policies_count) as policies_count')
                )
                ->first();
        }
        
        return view('admin.commissions.monthly', compact(
            'monthlyCommissions',
            'agents',
            'years',
            'year',
            'month',
            'agentId',
            'totals',
            'monthlyStats',
            'topAgents',
            'currentMonthData',
            'previousMonthData'
        ));
    }
}

