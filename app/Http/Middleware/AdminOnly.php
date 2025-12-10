<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->role !== User::ROLE_ADMIN) {
            return response()->json([
                'message' => 'Forbidden: admin only',
            ], 403);
        }

        return $next($request);
    }
}
