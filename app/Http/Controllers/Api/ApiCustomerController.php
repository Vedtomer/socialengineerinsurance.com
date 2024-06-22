<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Transaction;
use App\Models\Slider;
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

        $dummyData = [
            'health_insurance' => [],
            'genral_insurance' => [],
            'life_insurance' => [],
            'claim' => [],
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
