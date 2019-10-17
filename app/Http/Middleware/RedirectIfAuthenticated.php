<?php

namespace App\Http\Middleware;

use App\Http\Controllers\User\UserController;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\RegisterRequest;
use Closure;
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
    public function handle($request, Closure $next, $guard = null)
    {
        if (!empty($guard) && Auth::guard($guard)->check()) {
            return redirect('/home', 302, $request->headers->all(), $request->secure());
        }

        $controller = new UserController();
        if ($request->route()->named('user.home')) {
            return $controller->home($request);
        }

        if ($request->route()->named('user.login')) {
            return $controller->login($request);
        }

        if ($request->route()->named('user.register')) {
            return $controller->register($request);
        }

        return $next($request);
    }
}
