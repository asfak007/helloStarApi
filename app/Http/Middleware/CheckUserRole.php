<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // ✔ Check role using role slug/name
        if (!strtolower($user->role->name) !== strtolower($role)) {
            return response()->json([
                'status' => 403,
                'message' => 'Forbidden: You don’t have access to this resource',
            ], 403);
        }

        return $next($request);
    }
}


