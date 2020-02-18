<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ResponseCacheMiddleware
{
    const MAX_AGE = 300;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = 'request|' . $request->url() . '|' . Auth::guard('api')->user()->getId();

        return Cache::remember($key, self::MAX_AGE, function () use ($request, $next) {
            return $next($request);
        });
    }
}
