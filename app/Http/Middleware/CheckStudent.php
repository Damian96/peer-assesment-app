<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckInstructor
 * @package App\Http\Middleware
 *
 * @property array $allowed The allowed routes of a Student
 */
class CheckStudent
{
    const ROLE = 'student';

    private $allowed = [
        'user.profile',
        'user.forgot',
        'user.forgotSend',
        'user.reset',

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
        if (Auth::user()->isStudent() && !in_array($request->route()->getName(), $this->allowed, true)) {
            throw abort(403);
        }

        return $next($request);
    }
}
