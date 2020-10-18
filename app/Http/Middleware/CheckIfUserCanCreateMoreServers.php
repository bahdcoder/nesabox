<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfUserCanCreateMoreServers
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
        // THIS IS FOR THE BETA PERIOD:
        return $next($request);
        $user = auth()->user();

        if (
            !$user->subscribed('business') &&
            (!$user->subscribed('pro') && $user->servers()->count() === 1)
        ) {
            abort(400, 'Please upgrade your plan to add more servers.');
        }

        return $next($request);
    }
}
