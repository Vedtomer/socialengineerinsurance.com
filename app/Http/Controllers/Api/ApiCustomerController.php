<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Transaction;
use App\Models\Slider;
use App\Models\CustomerPolicy;
use App\Models\User;
use App\Models\Policy;
use Carbon\Carbon;
use App\Models\PointRedemption;
use Illuminate\Support\Facades\DB;

class ApiCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home(Request $request)
    {
        // Fetch data for each policy type using the model
        $dummyData = [
            'life_insurance' => CustomerPolicy::where('policy_type', 'life_insurance')->get(),
            'health_insurance' => CustomerPolicy::where('policy_type', 'health_insurance')->get(),
            'general_insurance' => CustomerPolicy::where('policy_type', 'general_insurance')->get(),
            'claim' => [], // Assuming this is another type of data you might fetch later
            'sliders' => Slider::where('status', 1)->pluck('image')->toArray(),
        ];

        return response()->json([
            'message' => 'Success',
            'status' => true,
            'data' => $dummyData
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
