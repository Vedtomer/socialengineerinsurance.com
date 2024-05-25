<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


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
        return view('admin.dashboard');
    }
}