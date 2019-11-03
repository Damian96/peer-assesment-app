<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckInstructor
{
    const ROLE = 'student';

    private $allowed = [
        'user.home',
        'user.profile',
        'user.forgot',
        'user.forgotSend',
        'user.reset',
        'user.verify',
        'user.update',

        'course.index',

        'session.index',
        'session.view',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isInstructor() && !in_array($request->route()->getName(), $this->allowed, true)) {
            throw abort(403);
        }

        return $next($request);
    }
}
