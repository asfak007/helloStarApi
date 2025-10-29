<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class IpThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip   = $request->ip();
        $user = $request->user();

        // Determine role (fallback to guest)
        $role = $user->role ?? 'guest';

        // 🎯 Set limits by role
        $limits = [
            'admin'  => 300, // generous
            'manager'=> 150,
            'user'   => 80,
            'guest'  => 40,
        ];

        // If role not defined above, default to 60/min
        $maxAttempts = $limits[$role] ?? 60;
        $decaySeconds = 60;

        // Unique key: IP + role to isolate by both
        $key = "throttle:{$role}:{$ip}";

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'status'  => 429,
                'message' => 'Too Many Requests',
                'role'    => $role,
                'retry_after_seconds' => $retryAfter,
            ], 429);
        }

        // Record a hit
        RateLimiter::hit($key, $decaySeconds);

        return $next($request);
    }

}
