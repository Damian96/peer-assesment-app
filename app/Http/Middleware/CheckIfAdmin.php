<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckIfAdmin
{
    /**
     * Checked that the logged in user is an administrator.
     *
     * @param User $user the model user class
     * @return bool whether the user is an admin
     */
    private function checkIfUserIsAdmin($user)
    {
         return $user->isAdmin();
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param \App\Http\Requests\AdminRequest $request the incoming request
     * @return [type] [description]
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (backpack_auth()->guest() || !$this->checkIfUserIsAdmin(backpack_user())) {
            return $this->respondToUnauthorizedRequest($request);
        }

        return $next($request);
    }
}
