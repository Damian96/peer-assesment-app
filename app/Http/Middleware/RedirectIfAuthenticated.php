<?php

namespace App\Http\Middleware;

use App\Http\Controllers\User\UserController;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\RegisterRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!empty($guard) && Auth::guard($guard)->check()) {
            return redirect('/home', 302, $request->headers->all(), $request->secure());
        }

        return $next($request);
    }
}
