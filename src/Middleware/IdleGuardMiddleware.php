<?php

namespace Mretaa\AutoLogout\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class IdleGuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $idleTime = Config::get('idle-guard.idle_time', 300);
            $logoutTime = Config::get('idle-guard.logout_time', 60);
            $redirectUrl = Config::get('idle-guard.redirect_url', '/logout');

            $lastActivity = Session::get('last_activity', time());
            $now = time();

            if (($now - $lastActivity) > ($idleTime + $logoutTime)) {
                Auth::logout();
                Session::forget('last_activity');
                return redirect($redirectUrl);
            }

            Session::put('last_activity', $now);
        }

        return $next($request);
    }
}
