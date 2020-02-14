<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
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
        if (!Auth::user()->can($request->route()->getName(), $request->route()->parameters)) {
            throw abort(403);
        }

        return $next($request);
    }
}
