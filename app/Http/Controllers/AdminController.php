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
        $agents = Agent::all();

        return view('admin.transaction', ['data' => $users, 'agent' => $agents]);
    }
}