<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgentController extends Controller
{
    /**
     * Display a listing of agents and their statistics
     */
    public function index(Request $request)
    {
        $query = User::role('agent');
    
        // Apply agent filter
        if ($request->filled('agent_id')) {
            $query->where('id', $request->agent_id);
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Apply sorting
        $sortOrder = $request->get('sort', 'desc');
        $query->orderBy('name', $sortOrder);
        
        // Get the agents
        $agents = $query->get();
        $allAgents = User::role('agent')->get();
        
        // Get counts for cards
        $activeAgentsCount = User::role('agent')->where('status', 1)->count();
        $inactiveAgentsCount = User::role('agent')->where('status', 0)->count();
    
        // Get edit agent if requested
        $editAgent = $request->has('edit') ? User::find($request->edit) : null;
    
        return view('admin.agent.index', compact(
            'agents', 
            'allAgents', 
            'editAgent', 
            'activeAgentsCount', 
            'inactiveAgentsCount'
        ));
    }
    
    /**
     * Get agent data for AJAX
     */
    public function getAgent($id)
    {
        $agent = User::findOrFail($id);
        return response()->json($agent);
    }

    /**
     * Store or update an agent
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'mobile_number' => 'required|regex:/^[0-9]{10}$/',
        ]);

        DB::beginTransaction();

        try {
            if ($request->filled('id')) {
                $agent = User::findOrFail($request->id);
            } else {
                $agent = new User();
                $agent->assignRole('agent');
                $agent->password = bcrypt($request->mobile_number);
            }

            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->mobile_number = $request->mobile_number;
            $agent->state = $request->state;
            $agent->city = $request->city;
            $agent->address = $request->address;
            $agent->status = $request->active ?? 0;
            $agent->save();

            DB::commit();

            return redirect()
    ->route('agent.management')
    ->with('success', ($request->filled('id') ? 'Updated' : 'Added') . ' agent successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('agent.management')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update agent password (AJAX method)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $agent = User::findOrFail($request->agent_id);
            $agent->password = bcrypt($request->password);
            $agent->save();

            return redirect()->route('agent.management')
                ->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('agent.management')
                ->with('error', 'Error updating password: ' . $e->getMessage());
        }
    }
}