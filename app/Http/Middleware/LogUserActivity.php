<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the response
        $response = $next($request);
        
        // Only log authenticated requests
        if (Auth::check()) {
            $user = Auth::user();
            
            // Create activity log
            UserActivity::create([
                'user_id' => $user->id,
                'user_type' => $user->roles->pluck('name')->first() ?? 'unknown', // Get the first role name
                'method' => $request->method(),
                'route' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_data' => json_encode($this->filterSensitiveData($request->all())),
                'response_code' => $response->getStatusCode(),
                'response_data' => $this->shouldLogResponse($request) ? json_encode($response->getContent()) : null,
            ]);
        }
        
        return $response;
    }
    
    /**
     * Filter sensitive data from the request.
     *
     * @param array $data
     * @return array
     */
    protected function filterSensitiveData(array $data)
    {
        // Define sensitive fields to be masked
        $sensitiveFields = ['password', 'password_confirmation', 'credit_card', 'token'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '******';
            }
        }
        
        return $data;
    }
    
    /**
     * Determine if the response should be logged.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function shouldLogResponse(Request $request)
    {
        // Only log smaller responses or specific routes
        // You can customize this logic based on your needs
        return $request->is('api/agent/*') && !$request->is('api/agent/home');
    }
}