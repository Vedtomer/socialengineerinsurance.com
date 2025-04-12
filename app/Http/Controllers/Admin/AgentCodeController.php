<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentCode;
use App\Models\InsuranceCompany;
use App\Models\InsuranceProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentCodeController extends Controller
{
    public function index(Request $request)
    {

        

        // Get agents with their commissions
        $query = User::role('agent')
            ->whereHas('agentCodes') // Only agents having agent codes
            ->withCount('agentCodes')
            ->with(['agentCodes.insuranceProduct', 'agentCodes.insuranceCompany']);

        // Apply sorting
        $sort = $request->get('sort', 'asc');
        $query->orderBy('name', $sort);

        // Apply agent filter
        if ($request->filled('agent_id')) {
            $query->where('id', $request->agent_id);
        }

        $agentsWithCommissions = $query->paginate(10);

        // Other data needed for forms
        $agents = User::role('agent')->get();
        $insuranceProducts = InsuranceProduct::all();
        $insuranceCompanies = InsuranceCompany::all();
        $editAgentCode = $request->has('edit') ? AgentCode::find($request->edit) : null;

        return view('admin.agent.agentcode', compact(
            'agentsWithCommissions',
            'agents',
            'insuranceProducts',
            'insuranceCompanies',
            'editAgentCode',
            'sort'
        ));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'insurance_product_id' => 'required|exists:insurance_products,id',
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'commission_type' => 'required|in:fixed,percentage',
            'commission' => 'required|numeric|min:0',
            'payment_type' => 'required|in:agent_full_payment,commission_deducted,pay_later_with_adjustment,pay_later',
            'gst' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payout' => 'nullable|numeric|min:0',
        ]);

        // Find or create agent code record
        if ($request->filled('id')) {
            $agentCode = AgentCode::findOrFail($request->id);
        } else {
            $agentCode = new AgentCode();
        }

        // Fill agent code data
        $agentCode->user_id = $request->agent_id;
        $agentCode->insurance_product_id = $request->insurance_product_id;
        $agentCode->insurance_company_id = $request->insurance_company_id;
        $agentCode->commission_type = $request->commission_type;
        $agentCode->commission = $request->commission;
        $agentCode->payment_type = $request->payment_type;
        $agentCode->gst = $request->gst;
        $agentCode->discount = $request->discount ?? 0;
        $agentCode->payout = $request->payout ?? 0;
        $agentCode->commission_settlement = $request->has('commission_settlement') ? 1 : 0;

        // Save to get ID if new record
        $agentCode->save();

        // Generate agent code if new record or if data changed
        if (!$request->filled('id') || $agentCode->isDirty(['commission_type', 'commission'])) {
            // Get first character of commission type (F/P)
            $typePrefix = Str::upper(substr($agentCode->commission_type, 0, 1));

            // Create a random code (5 chars to make total length 6 with prefix)
            $randomStr = Str::upper(Str::random(5));

            // Combine to create a 6-character code
            $candidateCode = $typePrefix . $randomStr;

            // Ensure uniqueness
            $isUnique = false;
            $attempts = 0;
            $maxAttempts = 10; // Prevent infinite loop

            while (!$isUnique && $attempts < $maxAttempts) {
                // Check if code exists
                $exists = AgentCode::where('code', $candidateCode)
                    ->where('id', '!=', $agentCode->id)
                    ->exists();

                if (!$exists) {
                    $isUnique = true;
                } else {
                    // Generate new random string and try again
                    $randomStr = Str::upper(Str::random(5));
                    $candidateCode = $typePrefix . $randomStr;
                    $attempts++;
                }
            }

            $agentCode->code = $candidateCode;
            $agentCode->save();
        }

        return redirect()
            ->route('commission.management')
            ->with('success', ($request->filled('id') ? 'Updated' : 'Added') . ' commission successfully');
    }

    public function delete($id)
    {
        $agentCode = AgentCode::findOrFail($id);
        $agentCode->delete();

        return redirect()
            ->route('commission.management')
            ->with('success', 'Commission deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        AgentCode::whereIn('id', $ids)->delete();

        return redirect()
            ->route('commission.management')
            ->with('success', count($ids) . ' commissions deleted successfully');
    }
}