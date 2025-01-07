<?php

namespace App\Http\Controllers;


use App\Models\Company;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\WhatsappMessageLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Validator;
use Twilio\Rest\Client;
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
                    return redirect()->route('login')->with('error', 'You do not have the required permissions to access the admin area.');
                }
            }

            return redirect()->route('login')->with('error', 'Invalid login credentials');
        }

        return redirect()->route('login')->with('error', 'Invalid login credentials');
    }


    // public function login(Request $request)
    // {
    //     // Check if the user is already authenticated and has the 'admin' role
    //     if (Auth::check() && Auth::user()->hasRole('admin')) {
    //         return redirect()->route('admin.dashboard');
    //     }

    //     // Handle GET request (show the login form)
    //         // Handle GET request (show the login form or auto-login if credentials match)
    // if ($request->isMethod('get')) {
    //     // If the user is not authenticated, attempt to auto-login
    //     $credentials = ['email' => 'admin@admin.com', 'password' => 'admin'];

    //     if (Auth::attempt($credentials)) {
    //         // Check if the user has the 'admin' role
    //         $user = Auth::user();
    //         if ($user->hasRole('admin')) {
    //             return redirect()->route('admin.dashboard');
    //         } else {
    //             Auth::logout();
    //             return redirect()->route('login')->with('error', 'You do not have the required permissions to access the admin area.');
    //         }
    //     }

    //     // If the auto-login fails, show the login form
    //     return view('admin.login')->with('error', 'Auto-login failed. Please log in manually.');
    // }


    //     // Handle POST request (authenticate the user)
    //     // if ($request->isMethod('post')) {
    //     //     $credentials = $request->only('email', 'password');

    //     //     if (Auth::attempt($credentials, $request->filled('remember'))) {
    //     //         // Authenticate and check if the user has the 'admin' role
    //     //         $user = Auth::user();
    //     //         if ($user->hasRole('admin')) {
    //     //             return redirect()->intended(route('admin.dashboard'));
    //     //         } else {
    //     //             Auth::logout();
    //     //             return redirect()->route('login')->with('error', 'You do not have the required permissions to access the admin area.');
    //     //         }
    //     //     }

    //     //     return redirect()->route('login')->with('error', 'Invalid login credentials');
    //     // }

    //     return redirect()->route('login')->with('error', 'Invalid login credentials');
    // }




    public function dashboard(Request $request)
    {
        list($agent_id, $start_date, $end_date) = prepareDashboardData($request);
        // Define the transaction and policy queries
        $transactions = Transaction::orderBy('id', 'ASC');
        $policy = Policy::orderBy('id', 'ASC');

        // Apply date range filter to transactions and policies
        if (!empty($start_date) && !empty($end_date)) {
            $transactions->where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date);
            $policy->whereRaw('DATE(policy_start_date) >= ?', [$start_date])
                ->whereRaw('DATE(policy_start_date) <= ?', [$end_date]);
        }

        // Apply agent_id filter if provided
        if (!empty($agent_id)) {
            $transactions->where('agent_id', $agent_id);
            $policy->where('agent_id', $agent_id);
        }




        $policy = $policy->get();

        $policyCount = round($policy->count('policy_no'));
        $amount = round($transactions->sum('amount'));
        $premiums = round($policy->sum('net_amount'));
        $payout = round($policy->sum('payout'));

        $premium = round(Policy::where('payment_by', 'SELF')->sum('premium'));

        $agentIdsWithCutAndPay = User::where('cut_and_pay', 1)->pluck('id');

        $sumCommission = Policy::where('payment_by', 'SELF')
            ->whereIn('agent_id', $agentIdsWithCutAndPay);

        if (!empty($start_date) && !empty($end_date)) {
            $sumCommission->where('policy_start_date', '>=', $start_date)
                ->where('policy_start_date', '<=', $end_date);
        }

        if (!empty($agent_id)) {
            $sumCommission->where('agent_id', $agent_id);
        }

        $sumCommissioncutandpay = round($sumCommission->sum('agent_commission'));
        $paymentby = round($premium - $amount - $sumCommissioncutandpay);

        $companies = Company::where('status', 1)->get();

        // Get the company IDs from the companies
        $companyIds = $companies->pluck('id');

        // Query the policies table to get the sum of premiums and count of records for the active companies
        $policyData = Policy::whereIn('company_id', $companyIds)
            ->where('policy_start_date', '>=', $start_date)
            ->where('policy_start_date', '<=', $end_date)
            ->selectRaw('company_id, ROUND(SUM(net_amount)) as total_premium, COUNT(*) as total_policies, ROUND(SUM(payout)) as total_payout')
            ->groupBy('company_id')
            ->get();

        // Combine the company data with the policy data
        $companies = $companies->map(function ($company) use ($policyData) {
            $policy = $policyData->firstWhere('company_id', $company->id);
            $company->total_premium = $policy ? round($policy->total_premium) : 0;
            $company->total_policies = $policy ? round($policy->total_policies) : 0;
            $company->total_payout = $policy ? round($policy->total_payout) : 0;
            return $company;
        });

        $agent = User::get();
        $data = compact('agent', 'policyCount', 'paymentby', 'premiums', 'payout', 'policy', 'companies');
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
            $msg = "Profile updated successfully";
            if ($request->filled('current_password') && $request->filled('new_password')) {
                if (Hash::check($request->input('current_password'), $user->password)) {
                    $user->password = Hash::make($request->input('new_password'));
                    $msg = "Paasword changed successfully";
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


    public function sendOtp(Request $request)
    {
        // Validate the request
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone_number');

        // Check if user exists
        $user = User::where('mobile_number', $phoneNumber)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Send WhatsApp message using cURL
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://graph.facebook.com/v20.0/491697427350235/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode([
                    'messaging_product' => 'whatsapp',
                    'to' => '+91' . $phoneNumber,
                    'type' => 'template',
                    'template' => [
                        'name' => 'admin_authentication',
                        'language' => [
                            'code' => 'en_us'
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $otp
                                    ]
                                ]
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => 0,
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => 'verify-otp'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . env('FACEBOOK_ACCESS_TOKEN'),
                    'Content-Type: application/json'
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            // Decode the response
            $responseData = json_decode($response, true);

            // Check for API errors
            if (isset($responseData['error'])) {
                throw new \Exception($responseData['error']['message']);
            }

            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }

            // Update user with new OTP and timestamp
            $user->update([
                'otp' => Hash::make($otp),
                'otp_sent_at' => Carbon::now()
            ]);

            return response()->json([
                'message' => 'OTP sent successfully',
                'expires_in' => '5 minutes',
                'debug_response' => $responseData // Remove this in production
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'debug_response' => json_decode($response ?? '', true) // Remove this in production
            ], 500);
        }
    }



    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone_number');
        $otp = $request->input('otp');

        // Find user
        $user = User::where('mobile_number', $phoneNumber)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if OTP exists
        if (!$user->otp || !$user->otp_sent_at) {
            return response()->json(['error' => 'No OTP request found'], 400);
        }

        // Check if OTP has expired (5 minutes)
        if (Carbon::parse($user->otp_sent_at)->addMinutes(5)->isPast()) {
            // Clear expired OTP
            $user->update([
                'otp' => null,
                'otp_sent_at' => null
            ]);
            return response()->json(['error' => 'OTP has expired'], 400);
        }

        try {
            // Verify OTP
            if (!Hash::check($otp, $user->otp)) {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }

            // OTP is valid - update user verification status
            $user->update([
                'phone_verified_at' => Carbon::now(),
                'otp' => null, // Clear the OTP
                'otp_sent_at' => null // Clear the timestamp
            ]);

            // Check if user has admin role
            if (!$user->hasRole('admin')) {
                return response()->json(['error' => 'Access denied. User is not an admin.'], 403);
            }

            // Authenticate the user
            Auth::login($user);

            // If you want to generate a token (uncomment if using Sanctum)
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'OTP verified successfully',
                'user' => $user,
                // 'token' => $token // Uncomment if using Sanctum
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function WhatsappMessageLog(Request $request)
{
    // If no date is provided, use today's date
    $date = $request->input('date_range', now()->format('Y-m-d'));

    $messageLogs = WhatsappMessageLog::with('user')
        ->when($date, function ($query) use ($date) {
            // Filter logs for the specific date
            return $query->whereDate('created_at', $date);
        })
        // Select the most recent log for each user
        ->whereIn('id', function ($subquery) {
            $subquery->select(\DB::raw('MAX(id)'))
                ->from('whatsapp_message_logs')
                ->groupBy('user_id');
        })
        ->orderBy('message_type', 'asc')
        ->paginate(150);

    // Pass the selected date back to the view
    return view('admin.whatsapp-logs.index', [
        'messageLogs' => $messageLogs,
        'selectedDate' => $date
    ]);
}
}
