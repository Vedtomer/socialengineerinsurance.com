<?php

namespace App\Http\Controllers;

use App\Models\AgentMonthlySettlement;
use App\Models\User;
use Illuminate\Http\Request;

class AgentSettlementController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $agentId = $request->input('agent_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        // Base query
        $query = AgentMonthlySettlement::with('agent');

        // Apply filters
        if ($agentId) {
            $query->forAgent($agentId);
        }

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        } elseif ($request->has('current_month')) {
            $query->currentMonth();
        }

        if ($status === 'outstanding') {
            $query->outstanding();
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
        $totalPending = $query->sum('pending_amount');

        // Paginate results
        $settlements = $query->paginate(10);

        return view('admin.settlements.index', compact(
            'settlements', 
            'agents', 
            'agentId', 
            'startDate', 
            'endDate', 
            'status',
            'sortDirection',
            'totalDue',
            'totalPaid',
            'totalPending'
        ));
    }
}
