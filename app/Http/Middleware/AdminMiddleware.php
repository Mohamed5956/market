<?php

namespace App\Http\Middleware;

use App\Models\User;
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

//        dd(Auth::user());
//        $users = User::with('roles')->get();
//        dd($users);
        $user = User::where('id',Auth::user()->id)->with('role')->first();
        if (Auth::check() && $user->role->name=='admin') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
