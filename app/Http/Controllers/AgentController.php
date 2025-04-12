<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\Policy;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\AgentExport;

class AgentController extends Controller
{
    public function Agent(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.agent');
        } elseif ($request->isMethod('post')) {
            $validate = $request->validate([
                'name' => 'required|string|max:100',
                'mobile_number' => 'required|regex:/^[0-9]{10}$/',
            ]);

            // Create a new agent instance
            $agent = new User();
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->password = $request->mobile_number;
            $agent->state = $request->state;
            $agent->city = $request->city;
            $agent->address = $request->address;
            $agent->mobile_number = $request->mobile_number;
            $agent->cut_and_pay = $request->cut_and_pay;
            $agent->save();

            // Assign the "agent" role to the newly created agent
            $agent->assignRole('agent');
            return redirect()->route('agent.commission', ['id' => $agent->id])->with('success', 'Agent created successfully.');
        }
    }

    public function AgentList(Request $request)
    {
        list($agent_id, $start_date, $end_date) = prepareDashboardData($request);

        // Ensure start_date and end_date are Carbon instances
       // $start_date = $start_date ? Carbon::parse($start_date)->startOfDay() : now()->startOfMonth()->startOfDay();
      // return    $end_date = $end_date ? Carbon::parse($end_date)->endOfDay() : now()->endOfDay();

        $query = User::role('agent')->withCount([
            'Policy as totalPolicies' => function ($query) use ($start_date, $end_date) {
                $query->whereDate('policy_start_date', '>=', $start_date)
                      ->whereDate('policy_start_date', '<', $end_date);
            }
        ])->withSum([
            'Policy as totalPremium' => function ($query) use ($start_date, $end_date) {
                $query->whereDate('policy_start_date', '>=', $start_date)
                      ->whereDate('policy_start_date', '<', $end_date);
            }
        ], 'premium')
        ->withSum([
            'Policy as totalEarnPoints' => function ($query) use ($start_date, $end_date) {
                $query->whereDate('policy_start_date', '>=', $start_date)
                      ->whereDate('policy_start_date', '<', $end_date);
            }
        ], 'payout')
        ->orderBy('created_at', 'desc');

        if (!empty($agent_id)) {
            $query->where('id', $agent_id);
        }

        $users = $query->get();

        $agent = User::role('agent')->get();

        return view('admin.user', [
            'data' => $users,
            'agent' => $agent
        ]);
    }

    #transaction
    // public function Transaction(Request $request, $id = null)
    // {

    //     $start_date = $request->input('start_date', now()->startOfMonth());
    //     $end_date = $request->input('end_date', now()->endOfDay());
    //     $payment_mode = ($request->input('payment_mode') === 'null') ? '' : $request->input('payment_mode');

    //     $agent_id = ($request->input('agent_id') === 'null') ? '' : $request->input('agent_id');

    //     if (empty($start_date) || $start_date == "null") {
    //         $start_date = now()->startOfMonth();
    //     } else {
    //         $start_date = Carbon::parse($start_date)->startOfDay();
    //     }

    //     if (empty($end_date) || $end_date == "null") {
    //         $end_date = now()->endOfDay();
    //     } else {
    //         $end_date = Carbon::parse($end_date)->endOfDay();
    //     }

    //     $query = Transaction::whereBetween('created_at', [$start_date, $end_date])->orderBy('payment_date', 'asc');

    //     if (!empty($payment_mode)) {

    //         if ($payment_mode === 'cash') {
    //             $query->where('payment_mode', 'cash');
    //         } else {
    //             $query->where('payment_mode', "!=", 'cash');
    //         }
    //     }

    //     if (!empty($agent_id)) {
    //         $query->where('agent_id', $agent_id);
    //     }

    //     $users = $query->get();
    //     $agents = Agent::all();

    //     return view('admin.transaction', ['data' => $users, 'agent' => $agents]);
    // }




    public function downloadExcel(Request $request)
    {
        // $data = Agent::with('Policy')->get();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $data = DB::table('agents')
            ->leftJoin('policies', 'agents.id', '=', 'policies.agent_id')
            ->leftJoin(DB::raw('(SELECT agent_id, SUM(amount) as total_amount FROM transactions GROUP BY agent_id) AS trans'), function ($join) {
                $join->on('agents.id', '=', 'trans.agent_id');
            })
            // ->where('payment_by', 'SELF')
            ->select(
                'agents.name',
                DB::raw('IFNULL(COUNT(policies.policy_no), 0) AS total_policy_no'),
                // DB::raw('COUNT(CASE WHEN policies.policy_no IS NOT NULL THEN 1 ELSE NULL END) AS total_policy_no'),
                DB::raw('COALESCE(SUM(premium), 0) AS totalPremium'),
                DB::raw('COALESCE(SUM(policies.agent_commission), 0) AS total_agent_commission'),
                'agents.email',
                'agents.city',
                'agents.mobile_number',
            )
            ->groupBy('agents.id', 'agents.name', 'agents.email', 'agents.city', 'agents.mobile_number')
            // ->havingRaw('balance > 0') // Filter only where balance is greater than zero
            ->get();
        return Excel::download(new AgentExport($data), 'agents.xlsx');

    }

   
    // public function commissionCode(Request $request)
    // {
    //     $agent_id = $request->input('agent_id', "") === "null" ? "" : $request->input('agent_id', "");
    //     $query = User::role('agent')->with('commissions')->orderBy('created_at', 'desc');
    //     if (!empty($agent_id)) {
    //         $query->where('id', $agent_id);
    //     }
    //     $users = $query->get();
    //     $agent = User::role('agent')->get();
    //     return view('admin.commissioncode', ['data' => $users, 'agent' => $agent]);
    // }

    public function filtereddata(Request $request)
    {
        // return "abs";
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (empty($start_date)) {
            $start_date = now()->startOfDay();
        } else {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (empty($end_date)) {
            $end_date = now()->endOfDay();
        } else {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }
        $data = Agent::whereBetween('created_at', [$start_date, $end_date])
            ->get();

        return view('admin.user', compact('data'));
    }

    // public function commission(Request $request, $id)
    // {

    //     $data = $id ? User::find($id) : new User();


    //     if ($data === null) {
    //         return redirect()->route('admin.user')->with('error', 'Agent not found');
    //     }

    //     $commissiondata = Commission::where('agent_id', $data->id)->get();

    //     if ($request->isMethod('post')) {

    //         $request->validate([
    //             'commission.*' => 'required',
    //             'commission_type.*' => 'required|in:fixed,percentage',
    //         ]);

    //         $commissions = $request->input('commission');
    //         $commissionTypes = $request->input('commission_type');
    //         $id = $request->input('id');

    //         foreach ($commissions as $key => $commissionValue) {
    //             $commission = !empty($id[$key])
    //                 ? Commission::firstOrNew(['id' => $id[$key]])
    //                 : new Commission();
    //             $commission->agent_id = $data->id;
    //             $commission->commission_type = $commissionTypes[$key];
    //             $commission->commission = $commissionValue;

    //             $commission->save();
    //             $commissionValue = intval($commissionValue);
    //             $commissionCode = strtoupper(substr($commissionTypes[$key], 0, 1)) . $commissionValue . "IN" . $commission->id;
    //             $commission->commission_code = $commissionCode;
    //             $commission->save();
    //         }
    //         return redirect()->back()->with('success', 'Commission added successfully');
    //     }

    //     return view('admin.commission', compact('data', 'commissiondata'));
    // }



    public function AgentEdit(Request $request, string $id)
    {
        if ($request->isMethod('post')) {

            $validate = $request->validate([
                'name' => 'required|string|max:100',
                'mobile_number' => 'required|regex:/^[0-9]{10}$/',
            ]);

            $agent = User::find($id);
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->state = $request->state;
            $agent->city = $request->city;
            $agent->address = $request->address;
            $agent->cut_and_pay = $request->cut_and_pay;
            $agent->status = $request->active;


            $agent->mobile_number = $request->mobile_number;
            $agent->save();
            return redirect()->route('agent.list')->with('success', 'Update successfully.');
        } else {
            $agent = User::find($id);
            return view('admin.useredit', ['data' => $agent]);
        }
    }

    public function ChangePassword(Request $request, string $id)
    {
        if ($request->isMethod('post')) {

            $validate = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $hashedPassword = bcrypt($request->password);

            $user = User::find($id);
            $user->password = $hashedPassword;
            $user->save();

            return redirect()->route('agent.list')->with('success', 'Password updated successfully.');
        } else {
            return view('admin.useredit', compact('id'));
        }
    }


    public function updateagentid(Request $request, $royalsundaram_id = null, $agent_id = null)
    {

        $royal = Policy::find($royalsundaram_id);

        if (!$royal) {
            return redirect()->back()->with('error', 'Royalsundaram record not found.');
        }

        if (!empty($agent_id)) {

            $agent = Agent::find($agent_id);
            $commissions = Commission::where('agent_id', $agent_id)->get();

            if ($commissions->isEmpty()) {
                return redirect()->route('agent.commission', $agent_id)->with('error', 'Update Commission of ' . $agent->name);
            }
            if ($request->isMethod('post')) {
                $commissionid = $request->commission_id;
                $commission = Commission::find($commissionid);

                if ($commission->commission_type == 'percentage') {
                    $commissionPercentage = $commission->commission;
                    $netAmount = $royal->net_amount;
                    $commissionAmount = $netAmount * ($commissionPercentage / 100);
                    $royal->agent_commission = $commissionAmount;
                } else {
                    $royal->agent_commission = $commission->commission;
                }
                $royal->agent_id = $agent_id;
            }


            if ($request->isMethod('get')) {
                if (count($commissions) == 1) {

                    if ($commissions[0]->commission_type == 'percentage') {
                        $commissionPercentage = $commissions[0]->commission;
                        $netAmount = $royal->net_amount;
                        $commissionAmount = $netAmount * ($commissionPercentage / 100);
                        $royal->agent_commission = $commissionAmount;
                    } else {
                        $royal->agent_commission = $commissions[0]->commission;
                    }
                    $royal->agent_id = $agent_id;
                } else {
                    return view('admin.selectcommission', compact('commissions', 'agent', 'royal'));
                }
            }

            // $transation = Transaction::where('policy_id', $royal->id)->first();
            // $transation->agent_id = $agent_id;
            // $transation->save();
        }
        if ($request->hasFile('policy_file')) {

            $file = $request->file('policy_file');
            $customFileName = $royal->policy . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/policy', $customFileName);
            $royal->policy_link = $customFileName;
        }
        $royal->save();


        return redirect()->route('policy.list')->with('success', 'Agent and Policy updated successfully!');
    }

    // public function destroy($id)
    // {
    //     $commission = Commission::findOrFail($id);
    //     $commission->delete();

    //     return redirect()->back()->with('success', 'Commission record deleted successfully');
    // }
}
