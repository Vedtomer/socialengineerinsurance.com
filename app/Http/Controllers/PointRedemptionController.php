<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\PointRedemption;
use Illuminate\Http\Request;

class PointRedemptionController extends Controller
{
    public function index(Request $request)
    {
        $inProgressPoints = PointRedemption::with('agent')
            ->whereIn('status', ['completed', 'rejected'])
            ->orderByDesc('created_at') // Order by the latest data
            ->get();
    
        $agents = Agent::get();
    
        return view('admin.reward.index', ['points' => $inProgressPoints, 'agents' => $agents]);
    }

    public function ReedemRequest(Request $request)
    {
        $inProgressPoints = PointRedemption::with('agent')->where('status', 'in_progress')->get();
        $agents = Agent::get();
        return view('admin.reward.request', ['points' => $inProgressPoints, 'agents' => $agents]);
    }

    public function redeemSuccess(Request $request, $pointId)
    {
        $pointRedemption = PointRedemption::findOrFail($pointId);
        $pointRedemption->status = 'completed';
        $pointRedemption->save();
        return response()->json(['message' => 'Point redemption marked as successful.']);
    }

    public function cancelRedemption($pointId)
    {
        try {
            $pointRedemption = PointRedemption::findOrFail($pointId);
            $pointRedemption->status = 'rejected';
            $pointRedemption->save();

            return response()->json(['message' => 'Redemption request canceled successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to cancel redemption request'], 500);
        }
    }
}
