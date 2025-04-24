<?php

namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\MonthlyCommission;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AgentMonthlySettlement;
use App\Models\InsuranceCompany;
use App\Models\Policy;
use App\Models\UserActivity;
use App\Models\WhatsappMessageLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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
        $user = User::where('mobile_number', $phoneNumber)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->first();
        if (!$user) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }


        // Continue with admin-only operations below this point

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
        $user = User::where('mobile_number', $phoneNumber)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Admin user not found with this mobile number'], 404);
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


    public function AppActivity(Request $request)
    {
        $dateRange = $request->input('date_range', 'last_30_days');

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        if ($dateRange == 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
        } elseif ($dateRange == 'yesterday') {
            $startDate = Carbon::yesterday();
            $endDate = Carbon::yesterday();
        } elseif ($dateRange == 'last_7_days') {
            $startDate = Carbon::now()->subDays(7);
        } elseif ($dateRange == 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
        } elseif ($dateRange == 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
        }

        $routeNameMap = [
            'home' => 'Home Page',
            'getPointsSummary' => 'Get Points Summary',
            'getPolicy' => 'Get Policy',
            'pending-premium-ledger' => 'Pending Premium Ledger',
            'points-ledger' => 'Points Ledger',
            'get-claim' => 'Get Claim',
            'slider' => 'Slider Data',
            'transaction' => 'Transaction Details',
            'login' => 'Agent Login',
            'signup' => 'Customer Signup',
            'logout' => 'Agent Logout',
            'delete_account' => 'Delete Account',
            'approve-points-redemption' => 'Approve Points Redemption',
            // Add more routes and their appropriate names here
        ];

        $activitySummary = UserActivity::select(
            'user_activities.user_type',
            DB::raw('COUNT(*) as activity_count'),
            'users.name as user_name',
            DB::raw('MAX(user_activities.created_at) as last_active') // Fetch last active timestamp
        )
            ->leftJoin('users', 'user_activities.user_id', '=', 'users.id')
            ->whereBetween('user_activities.created_at', [$startDate, $endDate])
            ->groupBy('user_activities.user_type', 'users.name')
            ->orderBy('user_activities.user_type', 'ASC')
            ->orderBy('users.name', 'ASC')
            ->get();



        // Calculate counts for top boxes - Active users within the selected date range
        $activeUsersCount = UserActivity::whereBetween('created_at', [$startDate, $endDate])->distinct('user_id')->count();
        $activeAgentsCount = UserActivity::whereBetween('created_at', [$startDate, $endDate])->where('user_type', 'agent')->distinct('user_id')->count();
        $activeCustomersCount = UserActivity::whereBetween('created_at', [$startDate, $endDate])->where('user_type', 'customer')->distinct('user_id')->count();
        $totalUsersCount = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['customer', 'agent']);
        })->count();

        $customerCount = User::role('customer')->count();
        $agentCount = User::role('agent')->count();


        return view('admin.app-activity.index', [
            'activitySummary' => $activitySummary,
            'selectedDateRange' => $dateRange,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'activeUsersCount' => $activeUsersCount,       // Pass total active users count
            'activeAgentsCount' => $activeAgentsCount,     // Pass total active agents count
            'activeCustomersCount' => $activeCustomersCount, // Pass total active customers count
            'totalUsersCount' => $totalUsersCount,
            'customerCount' => $customerCount,
            'agentCount' => $agentCount
        ]);
    }

    public function analytics(Request $request)
    {
        // Get filter parameters
        $start_date = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $agent_id = $request->input('agent_id');
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $filter_type = $request->input('filter_type', 'monthly'); // Options: monthly, yearly, custom

        // Determine date range based on filter type
        if ($filter_type === 'monthly') {
            $start_date = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $end_date = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        } elseif ($filter_type === 'yearly') {
            $start_date = Carbon::createFromDate($year, 1, 1)->startOfYear()->format('Y-m-d');
            $end_date = Carbon::createFromDate($year, 12, 31)->endOfYear()->format('Y-m-d');
        }
        // custom date range is already handled by the input fields

        $currentDate = Carbon::parse($end_date);

        // Get Policy Rates Data
        $policyRatesData = $this->getFilteredPolicyRates($currentDate, $start_date, $end_date, $agent_id);
        $policyRates = $policyRatesData['agentData'];
        $chartData = $policyRatesData['chartData'];

        // Get Dashboard Data
        $dashboardData = $this->getFilteredDashboardData($start_date, $end_date, $agent_id);

        // Combine all data
        $data = array_merge($dashboardData, [
            'policyRates' => $policyRates,
            'chartData' => $chartData,
            'filter_type' => $filter_type,
            'selected_year' => $year,
            'selected_month' => $month,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'selected_agent_id' => $agent_id
        ]);

        return view('admin.dashboard.index', compact('data'));
    }

    /**
     * Get filtered policy rates data
     */
    private function getFilteredPolicyRates($currentDate, $start_date, $end_date, $agent_id = null)
    {
        // Set start date to 12 months ago from current month for chart data
        $chartStartDate = Carbon::create($currentDate->year, $currentDate->month, 1)->subMonths(11)->startOfDay();
        $chartEndDate = Carbon::create($currentDate->year, $currentDate->month, $currentDate->daysInMonth)->endOfDay();

        // Query using the MonthlyCommission model instead of raw Policy data
        $query = MonthlyCommission::join('users', 'monthly_commissions.agent_id', '=', 'users.id')
            ->select(
                'users.name as agent_name',
                'monthly_commissions.agent_id',
                'monthly_commissions.year',
                'monthly_commissions.month',
                'monthly_commissions.policies_count as policy_count'
            )
            ->where('users.status', '=', 1);

        // Apply agent filter if provided
        if ($agent_id) {
            $query->where('monthly_commissions.agent_id', $agent_id);
        }

        // Apply date range filter for chart data
        $query->where(function ($q) use ($chartStartDate, $chartEndDate) {
            $startYear = $chartStartDate->year;
            $startMonth = $chartStartDate->month;
            $endYear = $chartEndDate->year;
            $endMonth = $chartEndDate->month;

            $q->where(function ($q) use ($startYear, $startMonth) {
                $q->where('monthly_commissions.year', '>', $startYear)
                    ->orWhere(function ($q) use ($startYear, $startMonth) {
                        $q->where('monthly_commissions.year', $startYear)
                            ->where('monthly_commissions.month', '>=', $startMonth);
                    });
            })->where(function ($q) use ($endYear, $endMonth) {
                $q->where('monthly_commissions.year', '<', $endYear)
                    ->orWhere(function ($q) use ($endYear, $endMonth) {
                        $q->where('monthly_commissions.year', $endYear)
                            ->where('monthly_commissions.month', '<=', $endMonth);
                    });
            });
        });

        $policyRates = $query->get();

        $formattedData = [];

        // Create array of month names from current month back 12 months for chart data
        $monthNames = [];
        $monthYears = []; // Store both month and year for accurate matching

        for ($i = 11; $i >= 0; $i--) {
            $monthDate = Carbon::create($currentDate->year, $currentDate->month, 1)->subMonths($i);
            $monthNames[] = $monthDate->format('Y M');
            $monthYears[] = [
                'year' => $monthDate->year,
                'month' => $monthDate->month
            ];
        }

        // Initialize policyCounts for all months
        $policyCounts = array_fill(0, 12, 0);

        // For agent data, we only want the last 6 months
        $lastSixMonthNames = array_slice($monthNames, 6, 6);
        $lastSixMonthYears = array_slice($monthYears, 6, 6);

        foreach ($policyRates as $rate) {
            if (!isset($formattedData[$rate->agent_id])) {
                $formattedData[$rate->agent_id] = [
                    'agent_name' => $rate->agent_name,
                    'labels' => $lastSixMonthNames, // Only last 6 months for agent data
                    'data' => array_fill(0, 6, 0)   // Only 6 data points
                ];
            }

            // For chart data - all 12 months
            for ($i = 0; $i < 12; $i++) {
                if ($monthYears[$i]['year'] == $rate->year && $monthYears[$i]['month'] == $rate->month) {
                    $policyCounts[$i] += $rate->policy_count;

                    // For agent data - only if it's in the last 6 months
                    if ($i >= 6) {
                        $formattedData[$rate->agent_id]['data'][$i - 6] = $rate->policy_count;
                    }
                    break;
                }
            }
        }

        // Calculate average policy count for each agent and add to formattedData
        foreach ($formattedData as $agentId => &$agentData) {
            $sum = array_sum($agentData['data']);
            $count = count(array_filter($agentData['data'], function ($value) {
                return $value > 0; // Only count months that have policies
            }));

            // Avoid division by zero
            $agentData['avg'] = $count > 0 ? round($sum / $count, 2) : 0;
        }

        // Sort agents by average policy count (highest to lowest)
        uasort($formattedData, function ($a, $b) {
            return $b['avg'] <=> $a['avg'];
        });

        // Prepare chart data
        $chartData = [
            'categories' => array_map(
                function ($month, $count) {
                    return "$month($count)";
                },
                $monthNames,
                $policyCounts
            ),
            'data' => $policyCounts,
            'series' => [
                [
                    'name' => 'Policy Count',
                    'data' => $policyCounts
                ]
            ]
        ];

        return [
            'agentData' => $this->addDaysSinceLastPolicy($formattedData),
            'chartData' => $chartData
        ];
    }

    /**
     * Get filtered dashboard data
     */
    private function getFilteredDashboardData($start_date, $end_date, $agent_id = null)
    {
        // Use the AgentMonthlySettlement model instead of raw transactions
        $agentSettlements = AgentMonthlySettlement::query();

        // Apply date range filter
        if (!empty($start_date) && !empty($end_date)) {
            $startDate = Carbon::parse($start_date);
            $endDate = Carbon::parse($end_date);
            $agentSettlements->dateRange($startDate, $endDate);
        }

        // Apply agent filter if provided
        if (!empty($agent_id)) {
            $agentSettlements->where('agent_id', $agent_id);
        }

        // Get montly commission data
        $monthlyCommissions = MonthlyCommission::query();

        // Apply date range filter
        if (!empty($start_date) && !empty($end_date)) {
            $startDate = Carbon::parse($start_date);
            $endDate = Carbon::parse($end_date);

            $monthlyCommissions->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($q) use ($startDate) {
                    $q->where('year', '>', $startDate->year)
                        ->orWhere(function ($q) use ($startDate) {
                            $q->where('year', $startDate->year)
                                ->where('month', '>=', $startDate->month);
                        });
                })->where(function ($q) use ($endDate) {
                    $q->where('year', '<', $endDate->year)
                        ->orWhere(function ($q) use ($endDate) {
                            $q->where('year', $endDate->year)
                                ->where('month', '<=', $endDate->month);
                        });
                });
            });
        }

        // Apply agent filter if provided
        if (!empty($agent_id)) {
            $monthlyCommissions->where('agent_id', $agent_id);
        }

        // Get the aggregated data
        $policyCount = $monthlyCommissions->sum('policies_count');
        $premiums = $monthlyCommissions->sum('total_premium');
        $payout = $monthlyCommissions->sum('total_payout');
        $final_amount_due = $agentSettlements->sum('final_amount_due');

        // Get insurance company data
        $companies = InsuranceCompany::where('status', 1)->get();
        $companyIds = $companies->pluck('id');

        // Get policy data by company
        $policyData = Policy::whereIn('company_id', $companyIds)
            ->where('policy_start_date', '>=', $start_date)
            ->where('policy_start_date', '<=', $end_date)
            ->when($agent_id, function ($query) use ($agent_id) {
                return $query->where('agent_id', $agent_id);
            })
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

        // Filter companies with amount greater than 0
        $companies = $companies->filter(function ($company) {
            return $company->total_premium > 0;
        });

        $agents = User::role('agent')->where('status', 1)->get();

        return [
            'agents' => $agents,
            'policyCount' => round($policyCount),
            'final_amount_due' => $final_amount_due,
            'premiums' => round($premiums),
            'payout' => round($payout),
            'companies' => $companies
        ];
    }

    /**
     * Add days since last policy for each agent
     */
    public function addDaysSinceLastPolicy($formattedData)
    {
        $today = strtotime(date('Y-m-d'));
        $latestPolicyDates = DB::table('policies')
            ->select('agent_id', DB::raw('MAX(DATE(policy_start_date)) as last_policy_date'))
            ->groupBy('agent_id')
            ->get()
            ->keyBy('agent_id');

        foreach ($formattedData as $agentId => &$agentData) {
            if (isset($latestPolicyDates[$agentId])) {
                $lastPolicyDate = strtotime($latestPolicyDates[$agentId]->last_policy_date);

                // Calculate the difference in days
                $daysDifference = ($today - $lastPolicyDate) / (60 * 60 * 24);

                // Ensure the difference is not negative
                $agentData['days_since_last_policy'] = max(0, (int)$daysDifference);
            } else {
                // If no policy found for the agent, set a high number
                $agentData['days_since_last_policy'] = 9999;
            }
        }

        uasort($formattedData, function ($a, $b) {
            return $b['days_since_last_policy'] <=> $a['days_since_last_policy'];
        });

        return $formattedData;
    }
}
