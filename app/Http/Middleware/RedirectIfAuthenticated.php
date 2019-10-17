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
        $newRequest = null;
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        $controller = new UserController();
        if ($request->route()->named('user.home') && Auth::guard($guard)->check()) {
            return $controller->home($request);
        }

        if ($request->route()->named('user.login')) {
            $newRequest = new AdminRequest(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all()
            );
            $newRequest->setLaravelSession($request->session());
            return $controller->login($newRequest);
        }

        if ($request->route()->named('user.register')) {
            $newRequest = new RegisterRequest(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all()
            );
            $newRequest->setLaravelSession($request->session());
            return $controller->register($newRequest);
        }

        return $next($request);
    }
}
