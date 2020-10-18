<?php

namespace App\Http\Middleware;

use Closure;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $plan)
    {
        // THIS IS FOR THE BETA PERIOD:
        return $next($request);

        if (
            !auth()
                ->user()
                ->subscribed($plan)
        ) {
            abort(400, 'Please upgrade your plan to perform this action.');
        }

        return $next($request);
    }
}
