<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAPI
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('api_token') || $request->query->has('api_token') || $request->bearerToken()) {
            $user = User::whereApiToken($request->bearerToken() ? $request->bearerToken() : $request->get('api_token'));
            if ($user->exists()) {
                Auth::guard('api')->setUser($user->first());
                return $next($request);
            }
        } elseif ($request->wantsJson()) {
            return $next($request);
        }

        return redirect()->action('UserController@login', [], 302, $request->headers->all());
    }
}
