<?php

namespace App\Http\Controllers;

use App\Models\AgentMonthlySettlement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgentSettlementController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $agentId = $request->input('agent_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $year = $request->input('year');
        $month = $request->input('month');
        $status = $request->input('status');

        // Base query
        $query = AgentMonthlySettlement::with('agent');

        // Apply filters
        if ($agentId) {
            $query->forAgent($agentId);
        }

        // Filter by specific year
        if ($year) {
            $query->where('year', $year);
        }

        // Filter by specific month
        if ($month) {
            $query->where('month', $month);
        }

        // Filter by date range
        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        } elseif ($request->has('current_month')) {
            $query->currentMonth();
        }

        // Sort by year and month (newest first by default)
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy('year', $sortDirection)
            ->orderBy('month', $sortDirection);

        // Get agents for filter dropdown
        $agents = User::whereHas('agentSettlements')->get();

        // Get summary statistics
        $totalDue = $query->sum('final_amount_due');
        $totalPaid = $query->sum('amount_paid');
        // $totalPending = $query->sum('pending_amount');

        // Get all settlements for calculation
        $allSettlements = $query->get();

        // Check if data spans more than one month
        $uniqueMonths = $allSettlements->unique(function ($item) {
            return $item->year . '-' . $item->month;
        })->count();

        // Get available years for filter
        $availableYears = AgentMonthlySettlement::distinct('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Group the settlements by month-year
        $groupedSettlements = $allSettlements->groupBy(function ($settlement) {
            // Create a readable month-year format (e.g., "January 2025")
            $date = Carbon::createFromDate($settlement->year, $settlement->month, 1);
            return $date->format('F Y'); // F gives full month name
        });

        // Maintain sort order based on the requested direction
        if ($sortDirection === 'desc') {
            $groupedSettlements = $groupedSettlements->sortKeysDesc();
        } else {
            $groupedSettlements = $groupedSettlements->sortKeys();
        }

        // Calculate totals for each month
        $monthlyTotals = [];
        foreach ($groupedSettlements as $monthYear => $settlements) {
            $monthlyTotals[$monthYear] = [
                'total_commission' => $settlements->sum('total_commission'),
                'total_premium_due' => $settlements->sum('total_premium_due'),
                'amount_paid' => $settlements->sum('amount_paid'),
                'pending_amount' => $settlements->sum('pending_amount'),
                'carry_forward_due' => $settlements->sum('carry_forward_due'),
                'final_amount_due' => $settlements->sum('final_amount_due'),
            ];
        }

        // If there's only one month's data or showAll is not specified, use pagination
        if ($uniqueMonths <= 1 || !$request->has('show_grouped')) {
            $settlements = $query->paginate(100);
            $isGrouped = false;
        } else {
            $settlements = $groupedSettlements;
            $isGrouped = true;
        }

        return view('admin.settlements.index', compact(
            'settlements',
            'agents',
            'agentId',
            'startDate',
            'endDate',
            'year',
            'month',
            'status',
            'sortDirection',
            'totalDue',
            'totalPaid',
            'totalPending',
            'uniqueMonths',
            'isGrouped',
            'groupedSettlements',
            'monthlyTotals',
            'availableYears'
        ));
    }
}
