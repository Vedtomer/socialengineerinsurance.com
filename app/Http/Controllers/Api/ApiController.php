<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Slider;
use App\Models\User;
use App\Models\Policy;
use Carbon\Carbon;
use App\Models\PointRedemption;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerPolicy;
use App\Models\InsuranceProduct;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;


class ApiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $startDate = !empty($startDate) ? Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay() : Carbon::now()->firstOfMonth();
        $endDate = !empty($endDate) ? Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay() : Carbon::now();

        $user = auth()->guard('api')->user();
        $agent_id = $user->id;
        if ($user->hasRole('agent')) {

            $cutAndPayTrue = $user->cut_and_pay;

            $totalCommission = Policy::whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agent_id)
                ->sum('agent_commission');

            $totalPolicy = Policy::whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agent_id)
                ->count();

            $totalPremiumPaid = Policy::whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agent_id)
                ->sum('premium');

            $transaction = Transaction::where('agent_id', $agent_id)
                ->sum('amount');

            $pendingPremium = Policy::where('payment_by', 'self')
                ->where('agent_id', $agent_id)
                ->sum('premium');

            $totalCommissionpendingPremium = Policy::where('agent_id', $agent_id)
                ->sum('agent_commission');

            if ($cutAndPayTrue) {
                $pendingPremium = $pendingPremium - ($transaction + $totalCommissionpendingPremium);
            } else {
                $pendingPremium = $pendingPremium - $transaction;
            }

            $dummyData = [
                'total_commission' => round($totalCommission),
                'total_policy' => $totalPolicy,
                'total_premium_paid' => round($totalPremiumPaid),
                'pending_premium' => round($pendingPremium),
                'sliders' => Slider::where('status', 1)->pluck('image')->toArray(),
                'claim' => Claim::where('users_id', $user->id)
                    ->whereBetween('claim_date', [$startDate, $endDate])
                    ->get(),
            ];
        } elseif ($user->hasRole('customer')) {

            $data = InsuranceProduct::whereHas('customer_policies', function ($query) use ($agent_id) {
                $query->where('user_id', $agent_id);
            })->with('customer_policies')->get();



            $dummyData = [
                'insurance' => $data,
                'claim' => Claim::where('users_id', $user->id)
                    ->whereBetween('claim_date', [$startDate, $endDate])
                    ->get(),
                'sliders' => Slider::where('status', 1)->pluck('image')->toArray(),
            ];
        } else {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false,
            ], 403);
        }

        return response()->json([
            'message' => 'Success',
            'status' => true,
            'data' => $dummyData
        ]);
    }




    public function Transaction($id = null)
    {

        if ($id !== null) {

            $transactions = Transaction::where('policy_no', $id)->first();
        } else {

            $user_id = auth()->guard('api')->user()->id;

            // $transaction = Transaction::where('user_id', $user->id)->get();

            $agentId = auth()->guard('api')->user()->id;

            $transactions = Transaction::join('royalsundaram', 'transactions.policy_no', '=', 'royalsundaram.policy')
                ->where('royalsundaram.agent_id', $agentId)->select('transactions.id', 'transactions.net_amount', 'transactions.gst', 'transactions.total_amount', 'transactions.is_payment_done', 'transactions.payment_by', 'transactions.is_agent_paid_premium_amount')
                ->get();
        }


        if ($transactions) {
            return response()->json(['message' => 'Success', 'status' => true, 'data' => $transactions]);
        } else {
            return response()->json(['message' => 'Transaction not found', 'status' => false, 'data' => null]);
        }
    }


    public function getActiveSliders()
    {
        $sliders = Slider::where('status', 1)->pluck('image')->toArray();
        return response()->json(['message' => 'Success', 'status' => true, 'data' => $sliders]);
    }

    public function getPolicy(Request $request)
    {
        try {
            $agent = new User();
            $result = $agent->getPoliciesCount($request);
            return $result;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPointsSummary(Request $request)
    {
        try {
            $rules = [
                'points' => 'required|numeric|min:0',
                'start_date' => 'required|date_format:d-m-Y',
                'end_date' => 'required|date_format:d-m-Y',
            ];

            $data = $this->points($request);


            return response([
                'status' => true,
                'data' => $data,
                'cut_and_pay' => auth()->guard('api')->user()->cut_and_pay,
                'message' => 'Points History'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pointsRedemption(Request $request)
    {
        $rules = [
            'points' => 'required|numeric|min:0',
            'start_date' => 'required|date_format:d-m-Y',
        ];

        $messages = [
            'points.required' => 'Points are required.',
            'points.numeric' => 'Points must be a number.',
            'points.min' => 'Points must be at least :min.',
            'start_date.required' => 'Start date is required.',
            'start_date.date_format' => 'Start date must be in the format dd-mm-yyyy.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false, 'data' => null], 422);
        }

        $points = $request->input('points');
        list($agent_id, $start_date, $end_date) = prepareApiParameter($request);

        // Fetch the agent
        $agent = auth()->guard('api')->user();
        if (!$agent) {
            return response()->json(['message' => 'Agent not found.', 'status' => false, 'data' => null], 404);
        }

        if ($agent->cut_and_pay) {
            return response()->json(['message' => 'You are not allowed to redeem points because "cut and pay" is enabled for your account.', 'status' => false, 'data' => null], 403);
        }

        $inProgressRedemption = PointRedemption::where('agent_id', $agent_id)
            ->whereYear('policy_period_month_year', Carbon::parse($start_date)->year)
            ->whereMonth('policy_period_month_year', Carbon::parse($start_date)->month)
            ->where('status', 'in_progress')
            ->exists();

        if ($inProgressRedemption) {
            return response()->json(['message' => 'You already have a redemption in progress.', 'status' => false, 'data' => null], 409);
        }

        $total = Policy::whereDate('policy_start_date', '>=', $start_date)
            ->whereDate('policy_start_date', '<=', $end_date)
            ->where('agent_id', $agent_id)
            ->sum('agent_commission');

        $redeemPoints = PointRedemption::where('agent_id', $agent_id)
            ->whereYear('policy_period_month_year', Carbon::parse($start_date)->year)
            ->whereMonth('policy_period_month_year', Carbon::parse($start_date)->month)
            ->whereIn('status', ['in_progress', 'completed'])
            ->sum('points');

        $remainingPoints = round($total - $redeemPoints);

        if ($points > $remainingPoints + 2) {
            return response()->json(['message' => 'Redeemed points cannot exceed remaining points.', 'status' => false, 'data' => null], 400);
        }

        $tds = 0.05 * $points;
        if ($agent_id == "10001") {
            $tds = 0;
        }
        $amountToBePaid = $points - $tds;

        $pointRedemption = new PointRedemption();
        $pointRedemption->agent_id = $agent_id;
        $pointRedemption->points = $points;
        $pointRedemption->status = 'in_progress';
        $pointRedemption->tds = $tds;
        $pointRedemption->amount_to_be_paid = $amountToBePaid;
        $pointRedemption->policy_period_month_year = Carbon::createFromFormat('d-m-Y', $request->input('start_date'))->startOfMonth();
        $pointRedemption->save();
        $this->sendWhatsAppNotification($agent, $pointRedemption);
        $data = $this->points($request);

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Points redeemed successfully'
        ], 200);
    }


    private function sendWhatsAppNotification($agent, $pointRedemption)
    {
        $url = 'https://graph.facebook.com/v20.0/491697427350235/messages';

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => "+91" . env('Whatsapp_admin_phone'),
            'type' => 'template',
            'template' => [
                'name' => 'points_redemption_request',
                'language' => [
                    'code' => 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $agent->name
                            ],
                            [
                                'type' => 'text',
                                'text' => (string)$pointRedemption->points
                            ],
                            [
                                'type' => 'text',
                                'text' => $pointRedemption->policy_period_month_year->format('F')
                            ],
                            [
                                'type' => 'text',
                                'text' => $pointRedemption->created_at->format('d M Y')
                            ],
                            [
                                'type' => 'text',
                                'text' => number_format($pointRedemption->tds, 2)
                            ],
                            [
                                'type' => 'text',
                                'text' => number_format($pointRedemption->amount_to_be_paid, 2)
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . env('FACEBOOK_ACCESS_TOKEN'),
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            \Log::info('WhatsApp notification sent successfully for point redemption ID: ' . $pointRedemption->id);
            \Log::info('Response: ' . $response);
        } else {
            $error = curl_error($ch);
            \Log::error('Failed to send WhatsApp notification. HTTP Code: ' . $httpCode . '. Error: ' . $error);
            \Log::error('Response: ' . $response);
        }

        curl_close($ch);
    }



    // public function sendWhatsAppMessage($points, $agent)
    // {
    //     try {
    //         $sid = env('TWILIO_SID');
    //         $token = env('TWILIO_AUTH_TOKEN');
    //         $twilio = new Client($sid, $token);

    //         $messageBody = "$agent requested redeem of $points points.";

    //         $message = $twilio->messages
    //             ->create(
    //                 "whatsapp:+919802244899",
    //                 array(
    //                     "from" => "whatsapp:+14155238886",
    //                     "body" => $messageBody
    //                 )
    //             );

    //         return response()->json(['message' => 'WhatsApp message sent successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function points(Request $request)
    {
        list($agent_id, $start_date, $end_date) = prepareApiParameter($request);

        $royalData = Policy::whereDate('policy_start_date', '>=', $start_date)
            ->whereDate('policy_start_date', '<=', $end_date)
            ->where('agent_id', $agent_id)
            ->select('policy_no', 'policy_start_date', 'policy_end_date', 'customername', 'premium', 'agent_commission', 'insurance_company')
            ->get();

        $totalAgentCommission = $royalData->sum('agent_commission');

        $start_date_carbon = Carbon::parse($start_date);

        $reedeemPoints = PointRedemption::where('agent_id', $agent_id)
            ->whereYear('policy_period_month_year', $start_date_carbon->year)
            ->whereMonth('policy_period_month_year', $start_date_carbon->month)
            ->whereIn('status', ['in_progress', 'completed'])
            ->sum('points');

        $totalCompletedCommission = PointRedemption::where('agent_id', $agent_id)
            ->whereYear('policy_period_month_year', $start_date_carbon->year)
            ->whereMonth('policy_period_month_year', $start_date_carbon->month)
            ->where('status', 'completed')
            ->sum('points');

        $totalInProgressCommission = PointRedemption::where('agent_id', $agent_id)
            ->whereYear('policy_period_month_year', $start_date_carbon->year)
            ->whereMonth('policy_period_month_year', $start_date_carbon->month)
            ->where('status', 'in_progress')
            ->sum('points');

        $remainingPoints = $totalAgentCommission - $reedeemPoints;

        return [
            'remaining_points' => round($remainingPoints),
            'total_points' => round($totalAgentCommission),
            'total_completed_reedeem' => round($totalCompletedCommission),
            'total_in_progress_reedeem' => round($totalInProgressCommission),
            'policy' => $royalData,
        ];
    }

    public function PointsLedger(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::createFromFormat('d-m-Y', $request->start_date)->startOfDay() : Carbon::now()->firstOfMonth();
            $endDate = $request->end_date ? Carbon::createFromFormat('d-m-Y', $request->end_date)->endOfDay() : Carbon::now();
            $agentId = auth()->guard('api')->user()->id;

            $currentMonthStart = $startDate->copy()->startOfMonth();

            // Calculate the opening balance for the previous month
            $openingBalance = Policy::where('agent_id', $agentId)
                ->where('policy_start_date', '<', $currentMonthStart)
                ->sum('agent_commission');

            // Calculate the sum of redeemed points for the previous month
            $redeemPoints = PointRedemption::where('agent_id', $agentId)
                ->whereIn('status', ['in_progress', 'completed'])
                ->where('created_at', '<', $currentMonthStart)
                ->sum('points');

            $openingBalance -= $redeemPoints;

            // Create an array for the opening balance
            $openingBalanceRecord = (object) [
                'date' => $currentMonthStart->copy()->startOfMonth()->toDateString(),
                'opening_balance' => $openingBalance,
            ];

            $policies = DB::table('policies')
                ->whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agentId)
                ->select(
                    'policy_no',
                    DB::raw('DATE(policy_start_date) as date'), // Change the date format
                    'customername',
                    'agent_commission as credit',
                    DB::raw('NULL as debit'),
                    DB::raw('NULL as tds'),
                    DB::raw('NULL as status') // Adding status column with NULL value
                )
                ->get();

            // Retrieve point_redemptions for debit
            $debitRedemptions = DB::table('point_redemptions')
                ->where('agent_id', $agentId)
                ->whereIn('status', ['in_progress', 'completed'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('NULL as policy_no'),
                    DB::raw('DATE(created_at) as date'), // Change the date format
                    DB::raw('NULL as customername'),
                    DB::raw('NULL as credit'),
                    'amount_to_be_paid as debit',
                    DB::raw('NULL as tds'),
                    'status' // Adding status column
                )
                ->get();

            // Set status to NULL or 0 for policies
            foreach ($policies as $policy) {
                $policy->status = null;
            }

            // Retrieve point_redemptions for tds
            $tdsRedemptions = DB::table('point_redemptions')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('agent_id', $agentId)
                ->whereIn('status', ['in_progress', 'completed'])
                ->select(
                    DB::raw('NULL as policy_no'),
                    DB::raw('DATE(created_at) as date'), // Change the date format
                    DB::raw('NULL as customername'),
                    DB::raw('NULL as credit'),
                    DB::raw('NULL as debit'),
                    DB::raw('CAST(tds AS CHAR) as tds'), // Cast tds to string
                    'status' // Adding status column
                )
                ->get();

            // Set status to NULL or 0 for policies
            foreach ($tdsRedemptions as $tdsRedemption) {
                $tdsRedemption->status = null;
            }

            // Merge records and prepend the opening balance record
            $combinedRecords = collect([$openingBalanceRecord])->concat($policies)->concat($debitRedemptions)->concat($tdsRedemptions);

            // Sort the merged collection by ascending date
            $sortedRecords = $combinedRecords->sortBy('date');

            // Calculate balance for each record and round off
            $balance = $openingBalance;
            $sortedRecords = $sortedRecords->map(function ($record) use (&$balance) {
                if (isset($record->opening_balance)) {
                    $record->balance = round($record->opening_balance);
                } else {
                    $balance += isset($record->credit) ? $record->credit : 0;
                    $balance -= isset($record->debit) ? $record->debit : 0;
                    $balance -= isset($record->tds) ? $record->tds : 0;
                    $record->balance = round($balance);
                }
                return $record;
            });

            $sortedRecords = $sortedRecords->values();

            return response()->json([
                'status' => true,
                'data' => $sortedRecords,
                'message' => 'Ledger retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the ledger data.'
            ], 500);
        }
    }



    public function PendingPremiumLedger(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::createFromFormat('d-m-Y', $request->start_date)->startOfDay() : Carbon::now()->firstOfMonth();
            $endDate = $request->end_date ? Carbon::createFromFormat('d-m-Y', $request->end_date)->endOfDay() : Carbon::now();
            $agent = auth()->guard('api')->user();
            $agentId = $agent->id;
            $cutAndPay = $agent->cut_and_pay ?? 0;

            $currentMonthStart = $startDate->copy()->startOfMonth();

            // Calculate the opening balance for the previous month
            $submitBalance = Transaction::where('agent_id', $agentId)
                ->where('payment_date', '<', $currentMonthStart)
                ->sum('amount');

            // Calculate the sum of pending premium for the previous month
            $pendingAmount = Policy::where('agent_id', $agentId)
                ->where('payment_by', "SELF")
                ->where('policy_start_date', '<', $currentMonthStart)
                ->selectRaw('SUM(premium) as premium_total, SUM(agent_commission) as commission_total')
                ->first();


            $openingBalance = $pendingAmount['premium_total'] - $submitBalance;

            if ($cutAndPay) {
                $openingBalance = $pendingAmount['premium_total'] - $pendingAmount['commission_total'] - $submitBalance;
            }

            // Retrieve policies
            $policies = DB::table('policies')
                ->whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agentId)
                ->where('payment_by', "SELF")
                ->select(
                    'policy_no',
                    DB::raw('DATE(policy_start_date) as date'),
                    'customername',
                    'premium',
                    'agent_commission'
                )
                ->get();

            // Retrieve transactions
            $transactions = Transaction::where('agent_id', $agentId)
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->select(
                    DB::raw('payment_date as date'),
                    'amount as credit'
                )
                ->get();

            $combinedRecords = $policies->merge($transactions);

            $sortedRecords = $combinedRecords->sortBy('date');

            $balance = $openingBalance;

            $sortedRecords = $sortedRecords->map(function ($record) use (&$balance, $cutAndPay) {
                if (isset($record->opening_balance)) {
                    $record->balance = round($record->opening_balance);
                } else {
                    if ($cutAndPay == 1) {
                        $balance += isset($record->premium) ? $record->premium : 0;
                        $balance -= isset($record->credit) ? $record->credit : 0;
                        $balance -= isset($record->agent_commission) ? $record->agent_commission : 0;
                    } else {
                        $balance += isset($record->premium) ? $record->premium : 0;
                        $balance -= isset($record->credit) ? $record->credit : 0;
                    }
                    $record->balance = round($balance);
                }
                return $record;
            });

            $openingBalanceRecord = (object) [
                'date' => $currentMonthStart->copy()->startOfMonth()->toDateString(),
                'opening_balance' => round($openingBalance),
            ];

            // Add the opening balance record to the beginning of the sorted records
            $sortedRecords->prepend($openingBalanceRecord);

            return response()->json([
                'status' => true,
                'data' => $sortedRecords->values()->all(),
                'message' => 'Pending premium ledger retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching the pending premium ledger data.'
            ], 500);
        }
    }



    public function getClaim(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $startDate = !empty($startDate) ? Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay() : Carbon::now()->firstOfMonth();
        $endDate = !empty($endDate) ? Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay() : Carbon::now();

        $user = auth()->guard('api')->user();

        $data = Claim::where('users_id', $user->id)
            ->whereBetween('claim_date', [$startDate, $endDate])
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'get claim listing successfully'
        ]);
    }

    public function approvePointsRedemption(Request $request, $id)
    {
        // Log all received request information and the id
        Log::info('Received points redemption approval request', [
            'request_data' => $request->all(),
            'id' => $id,
        ]);

        // Your logic for approving points redemption goes here
        // Example: Update database, send notifications, etc.

        // Return JSON response with status true
        return response()->json(['status' => true, 'message' => 'Points Redemption Approved Successfully', 'id' => $id]);
    }
}
