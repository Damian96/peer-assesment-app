<?php


namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified extends \Illuminate\Auth\Middleware\EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|void
     */
    public function handle($request, \Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())) {
            if ($request->expectsJson()) {
                return abort(403, 'Your email address is not verified.');
            } else {
                if ($request->session()->has('emailVerifiedSent'))
                    $request->session()->flash('emailVerifiedSent');
                return Redirect::route($redirectToRoute ?: 'verification.notice')
                    ->withHeaders($request->headers->all());
            }
        }

        return $next($request);
    }
}
