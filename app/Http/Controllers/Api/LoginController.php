<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{


    public function agentlogin(Request $request)
    {
        try {
            $emailOrMobile = $request->input('email');
            $password = $request->input('password');

            if (empty($password) || empty($emailOrMobile)) {
                return response()->json(['message' => 'Password or email/mobile number missing', 'status' => false, 'data' => []], 400);
            }

            $users = User::where('email', $emailOrMobile)
                ->orWhere('mobile_number', $emailOrMobile)
                ->orWhere('pan_number', $emailOrMobile)
                ->orWhere('username', $emailOrMobile)
                
                ->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'User not found', 'status' => false, 'data' => []], 404);
            }

            foreach ($users as $user) {
                if (Hash::check($password, $user->password)) {
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
                        'aadhar_number' => $user->aadhar_number,
                        'pan_number' => $user->pan_number
                    ];

                    $token = $user->createToken('MyApp')->accessToken;

                    return response()->json([
                        'status' => true,
                        'data' => $record,
                        'token' => $token
                    ], 200);
                }
            }

            return response()->json(['message' => 'Invalid credentials', 'status' => false, 'data' => []], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false, 'data' => []], 500);
        }
    }



    public function customerSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'mobile_number' => 'required|string|max:15|unique:users',
            'address' => 'nullable|string|max:255', // Assuming address is optional
        ]);

        if ($validator->fails()) {
            // Return only the first validation error
            $firstError = $validator->errors()->first();
            return response()->json(['message' => 'Validation Error', 'error' => $firstError, 'status' => false], 422);
        }

        try {
            // Create new agent
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->address = $request->address;
            $user->mobile_number = $request->mobile_number;
            $user->save();

            // Assign the 'customer' role to the new user
            $user->assignRole('customer');
            $role = $user->getRoleNames();
            $token = $user->createToken('MyApp')->accessToken;

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
                'aadhar_number' => $user->aadhar_number,
                'pan_number' => $user->pan_number
            ];


            return response()->json([
                'status' => true,
                'data' => $record,
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

    public function DeleteAccount()
    {
        try {
            $user = auth()->guard('api')->user();
           // $user->delete(); // This will soft delete the user
            $user->tokens()->delete(); // Delete all tokens associated with the user

            return response()->json([
                'message' => 'Account deleted successfully',
                'status' => true,
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
