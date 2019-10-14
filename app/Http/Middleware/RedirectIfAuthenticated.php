<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\AdminRequest;
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
        if (Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard');
        }

        if ($request->route()->named('user.login')) {
            $adminRequest = new AdminRequest(
                $request->query(),
                $request->post(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->allFiles(),
                $request->server(),
                $request->getContent()
            );
            $adminRequest->setLaravelSession($request->session());
            return (new LoginController)->login($adminRequest);
        }


//            return redirect('/home');

        return $next($request);
    }
}
