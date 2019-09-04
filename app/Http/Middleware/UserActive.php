<?php

namespace App\Http\Middleware;

use Closure;

class UserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if (\Auth::check()) {
            // The user is logged in...
            $user = \Auth::user();
            $user->last_active = date('Y-m-d H:i:s');
            $user->save();
        }
        return $next($request);
    }
}
