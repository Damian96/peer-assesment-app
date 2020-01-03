<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * @deprecated see \App\User::can()
     * @var array
     */
    protected $allowed = [
        'user.login',
        'user.verify',
        'user.auth',
        'user.logout',
        'user.profile',
        'user.verify',
        'user.forgot',
        'user.forgotSend',

        'form.add', // @FIXME remove it
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @see \App\User::can(string $ability, array|mixed $arguments)
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->can($request->route()->getName(), $request->route()->parameters)) {
            throw abort(403);
        }

        return $next($request);
    }
}
