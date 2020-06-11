<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateAPI
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('api_token') || $request->query->has('api_token') || $request->bearerToken()) {
            $user = User::whereApiToken($request->bearerToken() ? $request->bearerToken() : $request->get('api_token'));
            if ($user->exists()) {
                $user = $user->first();
                /**
                 * @var \App\User $user
                 */
                Auth::guard('api')->setUser($user);
                $request->session()->flash('wpes_headers', [
                    'X-WPES-USER' => $user->fullname,
                    'X-WPES-TOKEN' => $user->api_token
                ]);
                return $next($request);
            }
        } else {
            throw new UnauthorizedHttpException('Bearer', "You are not authorized to view this resource.");
        }

//        return response()->json([
//            'error' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found'], 404);
//        elseif ($request->route()->getAction()['as'] !== 'api.fallback.404') {
//            return Route::respondWithRoute('api.fallback.404');
//        } elseif ($request->route()->getAction()['as'] === 'api.fallback.404') {
//            $exception = new NotFoundHttpException("not found");
//            $request->request->add(['exception' => $exception]);
//            return $next($request);
//        }

        return redirect()->action('UserController@login', [], 302, $request->headers->all());
    }
}
