<?php

namespace App\Http\Middleware;

use App\Http\Controllers\User\UserController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard('web')->check() && $request->route()->named('user.login')) {
            return redirect('/profile', 302, $request->headers->all(), $request->secure());
        } elseif (!Auth::guard('web')->check() && !$request->route()->named('user.login')) {
            return redirect('/login', 302, $request->headers->all(), $request->secure());
        }

        return $next($request);
    }
}
