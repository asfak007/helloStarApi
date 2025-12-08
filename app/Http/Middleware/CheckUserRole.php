<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // FIXED: correct role comparison
        if (strtolower($user->role->name) !== strtolower($role)) {
            return response()->json([
                'status' => 403,
                'message' => 'Forbidden: You don’t have access to this resource',
            ], 403);
        }

        return $next($request);
    }
}
