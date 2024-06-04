<?php
namespace App\Http\Controllers;


use App\Models\Company;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{


    public function login(Request $request)
    {
        // Check if the user is already authenticated and has the 'admin' role
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // Handle GET request (show the login form)
        if ($request->isMethod('get')) {
            return view('admin.login');
        }

        // Handle POST request (authenticate the user)
        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                // Authenticate and check if the user has the 'admin' role
                $user = Auth::user();
                if ($user->hasRole('admin')) {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    Auth::logout();
                    return redirect()->route('admin.login')->with('error', 'You do not have the required permissions to access the admin area.');
                }
            }

            return redirect()->route('admin.login')->with('error', 'Invalid login credentials');
        }

        return redirect()->route('admin.login')->with('error', 'Invalid login credentials');
    }
    public function dashboard(Request $request)
    {

        $start_date = $request->input('start_date', "") === "null" ? date('Y-m-01') : $request->input('start_date');
        $end_date = $request->input('end_date', "") === "null" ? date('Y-m-t') : $request->input('end_date');
        $agent_id = $request->input('agent_id', "") === "null" ? "" : $request->input('agent_id', "");

        if (!isset($agent_id)) {

            if (!isset($start_date) || !$start_date instanceof Carbon) {
                $start_date = now()->startOfMonth();
            }

            if (!isset($end_date) || !$end_date instanceof Carbon) {
                $end_date = now()->endOfDay();
            }
        }

        if (isset($agent_id)) {


            if ($start_date !== null) {
                $start_date = Carbon::parse($start_date);
            } else {
                $start_date = now()->startOfMonth();
            }

            if ($end_date !== null) {
                $end_date = Carbon::parse($end_date);
            } else {
                $end_date = now()->endOfDay();
            }
        }

        $transactions = Transaction::orderBy('id', 'ASC');

        $policy = Policy::orderBy('id', 'ASC');


        if (!empty($start_date) && !empty($end_date)) {
            $policy->whereBetween('policy_start_date', [$start_date, $end_date]);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $transactions->whereBetween('payment_date', [$start_date, $end_date]);
        }

        if (!empty($agent_id)) {
            $policy->where('agent_id', $agent_id);
        }
        if (!empty($agent_id)) {
            $transactions->where('agent_id', $agent_id);
        }

        $query = User::withCount([
            'Policy as policy_count' => function (Builder $query) use ($start_date, $end_date) {
                $query->whereBetween('policy_start_date', [$start_date, $end_date]);
            }
        ])
            ->having('policy_count', '<', 10)
            ->orderBy('policy_count', 'asc');
        if (!empty($agent_id)) {
            $query->where('id', $agent_id);
        }

        $datausers = $query->get();

        $counts = Policy::whereBetween('policy_start_date', [$start_date, $end_date])
            ->where(function ($query) {
                $query->where('insurance_company', 'LIKE', '%ROYAL%')
                    ->orWhere('insurance_company', 'LIKE', '%FUTURE%')
                    ->orWhere('insurance_company', 'LIKE', '%TATA%')
                    ->orWhere('insurance_company', 'LIKE', '%tata%');
            })
            ->when(!empty($agent_id), function ($query) use ($agent_id) {
                return $query->where('agent_id', $agent_id);
            })
            ->selectRaw('insurance_company, COUNT(*) as count')
            ->groupBy('insurance_company')
            ->pluck('count', 'insurance_company');

        $royalCount = $counts->get('ROYAL', 0);
        $tataCount = $counts->get('TATA', 0) + $counts->get('tata', 0);
        $futureCount = $counts->get('FUTURE', 0);


        $transaction = $transactions->get();

        $policy = $policy->get();

        $policyCount = $policy->count('policy_no');
        $amount = $transactions->sum('amount');
        $status = $policy->pluck('payment_by');
        $premiums = $policy->sum('premium');

        $premium = Policy::where('payment_by', 'SELF')->sum('premium');


        $agentIdsWithCutAndPay = User::where('cut_and_pay', 1)->pluck('id');

        $sumCommission = Policy::where('payment_by', 'SELF')
            ->whereIn('agent_id', $agentIdsWithCutAndPay);

        if (!empty($start_date) && !empty($end_date)) {
            $sumCommission->whereBetween('policy_start_date', [$start_date, $end_date]);
        }

        if (!empty($agent_id)) {
            $sumCommission->where('agent_id', $agent_id);
        }

        $sumCommissioncutandpay = $sumCommission->sum('agent_commission');
        $paymentby = $premium - $amount - $sumCommissioncutandpay;


        $companies = Company::where('status', 1)->get();

        // Get the company IDs from the companies
        $companyIds = $companies->pluck('id');

        // Query the policies table to get the sum of premiums and count of records for the active companies
        $policyData = Policy::whereIn('company_id', $companyIds)
            ->whereBetween('policy_start_date', [$start_date, $end_date])
            ->selectRaw('company_id, SUM(premium) as total_premium, COUNT(*) as total_policies')
            ->groupBy('company_id')
            ->get();

        // Combine the company data with the policy data
        $companies = $companies->map(function ($company) use ($policyData) {
            $policy = $policyData->firstWhere('company_id', $company->id);
            $company->total_premium = $policy ? $policy->total_premium : 0;
            $company->total_policies = $policy ? $policy->total_policies : 0;
            return $company;
        });


        $agent = User::get();
        $data = compact('agent', 'policyCount', 'paymentby', 'premiums', 'datausers', 'policy', 'companies');
        return view('admin.dashboard', ['data' => $data]);
    }


    public function Transaction(Request $request, $id = null)
    {

        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfDay());
        $payment_mode = ($request->input('payment_mode') === 'null') ? '' : $request->input('payment_mode');

        $agent_id = ($request->input('agent_id') === 'null') ? '' : $request->input('agent_id');

        if (empty($start_date) || $start_date == "null") {
            $start_date = now()->startOfMonth();
        } else {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }

        if (empty($end_date) || $end_date == "null") {
            $end_date = now()->endOfDay();
        } else {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        $query = Transaction::whereBetween('created_at', [$start_date, $end_date])->orderBy('payment_date', 'asc');

        if (!empty($payment_mode)) {

            if ($payment_mode === 'cash') {
                $query->where('payment_mode', 'cash');
            } else {
                $query->where('payment_mode', "!=", 'cash');
            }
        }

        if (!empty($agent_id)) {
            $query->where('agent_id', $agent_id);
        }

        $users = $query->get();
        $agents = User::role('agent')->get();

        return view('admin.transaction', ['data' => $users, 'agent' => $agents]);
    }

    public function AddTransaction(Request $request)
    {
        if ($request->isMethod('get')) {
            $agents = User::role('agent')->get();
            return view('admin.transactionadd', ['data' => $agents]);
        }

        if ($request->isMethod('post')) {
            $transaction = new Transaction();
            $transaction->agent_id = $request->agent_id;
            $transaction->payment_mode = $request->payment_mode;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->amount = $request->amount;
            $transaction->payment_date = $request->payment_date;
            $transaction->save();
            return redirect()->route('admin.transaction')->with('success', 'Transaction Add Successfully.');
        }
    }

    public function profile(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Pass the user data to the view
        return view('admin.user.profile', compact('user'));
    }

    public function ProfileEdit(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Pass the user data to the view
        return view('admin.user.edit', compact('user'));
    }

    public function ProfileUpdate(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Get the first error message
            $firstError = $validator->errors()->first();
            return redirect()->back()->with('error', $firstError);
        }

            // Update user details
            if ($request->filled('name')) {
                $user->name = $request->input('name');
            }

            if ($request->filled('mobile_number')) {
                $user->mobile_number = $request->input('mobile_number');
            }

            if ($request->filled('email')) {
                $user->email = $request->input('email');
            }

            // Handle image upload if a new image is provided
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '.' . $image->extension();
                $image->storeAs('public/profile', $imageName);
                $user->profile_image = $imageName;
            }

            // Handle password change
            $msg="Profile updated successfully";
            if ($request->filled('current_password') && $request->filled('new_password')) {
                if (Hash::check($request->input('current_password'), $user->password)) {
                    $user->password = Hash::make($request->input('new_password'));
                    $msg="Paasword changed successfully";
                } else {
                    return redirect()->back()->with(['error', 'Current password is incorrect']);
                }
            }

            // Save the user
            $user->save();

            return redirect()->route('admin.profile')->with('success', $msg);
        } catch (\Exception $e) {
            return $e;
            return redirect()->back()->with(['error', 'An error occurred while updating the profile. Please try again.']);

        }
    }


    public function logout()
    {
        // Log the user out
        Auth::logout();
    
        // Redirect to the login page
        return redirect()->route('login');
    }

}