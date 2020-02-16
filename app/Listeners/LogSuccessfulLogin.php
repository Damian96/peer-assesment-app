<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if (Auth::guard('web')->check()) {
            $event->user->last_login = now(config('app.timezone'))->format(config('constants.date.stamp'));
            $event->user->saveOrFail();
        }
    }
}
