<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
            'pan_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->filled('id')) {
                $agent = User::findOrFail($request->id);
            } else {
                $agent = new User();
                $agent->assignRole('agent');
                $agent->password = $request->mobile_number;
            }

            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->mobile_number = $request->mobile_number;
            $agent->pan_number = $request->pan_number;
            $agent->state = $request->state;
            $agent->city = $request->city;
            $agent->address = $request->address;
            $agent->status = $request->active ?? 0;
            $agent->commission_settlement = $request->has('commission_settlement') ? 1 : 0;

            // Handle PAN Card image upload
            if ($request->hasFile('pan_image')) {
                // Delete old image if it exists
                if ($agent->pan_image && Storage::disk('public')->exists('pan_images/' . $agent->pan_image)) {
                    Storage::disk('public')->delete('pan_images/' . $agent->pan_image);
                }

                $panImage = $request->file('pan_image');
                $imageName = time() . '_' . $agent->id . '.' . $panImage->getClientOriginalExtension();

                // Store the file using Storage facade
                Storage::disk('public')->putFileAs('pan_images', $panImage, $imageName);
                $agent->pan_image = $imageName;
            }

            // Check if the remove flag is set to remove the image
            if ($request->remove_pan_image == 1 && $agent->pan_image) {
                if (Storage::disk('public')->exists('pan_images/' . $agent->pan_image)) {
                    Storage::disk('public')->delete('pan_images/' . $agent->pan_image);
                }
                $agent->pan_image = null;
            }

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
            $agent->password = $request->password;
            $agent->save();

            return redirect()->route('agent.management')
                ->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('agent.management')
                ->with('error', 'Error updating password: ' . $e->getMessage());
        }
    }
}
