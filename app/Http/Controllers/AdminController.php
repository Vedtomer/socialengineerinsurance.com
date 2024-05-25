<?php
namespace App\Http\Controllers;

use DataTables;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shriramgi;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\Royalsundaram;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{


    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if ($request->isMethod('get')) {
            return view('admin.login');
        }

        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');

            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->route('admin.login')
                ->with('error', 'Invalid login credentials');
        }
        return redirect()->route('admin.login')->with('error', 'Invalid login credentials');
    }
    public function dashboard(Request $request)
    {
        return "abc";
    }
}