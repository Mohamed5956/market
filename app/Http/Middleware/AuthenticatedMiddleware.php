<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
