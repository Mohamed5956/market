<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure(Request): (Response) $next
     * @return JsonResponse|mixed|Response
     */
    public function handle($request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
