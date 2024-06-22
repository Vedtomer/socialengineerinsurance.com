<?php


namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{


    public function agentlogin(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');

            if (empty($password) || empty($email)) {
                return response()->json(['message' => 'Password or email missing', 'status' => false, 'data' => []], 400);
            }

            $credentials = $request->only('email', 'password');
            $emailCredentials = $credentials;
            $mobileCredentials = [
                'mobile_number' => $email,
                'password' => $password
            ];

            if (Auth::attempt($emailCredentials) || Auth::attempt($mobileCredentials)) {
                $user = Auth::user();


                if (!$user->status) {
                    return response()->json(['message' => 'Your account is not active', 'status' => false, 'data' => []], 400);
                }
                $role = $user->getRoleNames();
                $record = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'state' => $user->state,
                    'city' => $user->city,
                    'address' => $user->address,
                    'mobile_number' => $user->mobile_number,
                    'commission' => [],
                    'roles' => $role[0],
                    'aadhar_number' => $user->aadhar_number,  // Add aadhaar_number
                    'pan_number' => $user->pan_number           // Add pan_number
                ];

                $token = $user->createToken('MyApp')->accessToken;

                return response()->json([
                    'status' => true,
                    'data' => $record,
                    'token' => $token
                ], 200);
            }

            return response()->json(['message' => 'User not found', 'status' => false, 'data' => []], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false, 'data' => []], 500);
        }
    }


    public function agentSignUp(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agents',
            'password' => 'required|string|min:6',
            'mobile_number' => 'required|string|max:15|unique:agents',
            'address' => 'nullable|string|max:255', // Assuming address is optional
        ]);

        if ($validator->fails()) {
            // Return only the first validation error
            $firstError = $validator->errors()->first();
            return response()->json(['message' => 'Validation Error', 'error' => $firstError, 'status' => false], 422);
        }

        try {
            // Create new agent
            $agent = new Agent();
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->password = Hash::make($request->password);
            $agent->address = $request->address;
            $agent->mobile_number = $request->mobile_number;
            $agent->save();

            // Generate a token for the newly created agent
            $token = $agent->createToken('MyApp')->accessToken;

            return response()->json([
                'status' => true,
                'data' => $agent,
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false], 500);
        }
    }

    public function agentlogout()
    {

        try {
            auth()->guard('api')->user()->tokens()->delete();
            return response()->json(['message' => 'Logout successfully', 'status' => true, 'data' => []], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
