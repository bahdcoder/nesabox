<?php

namespace App\Http\Middleware;

use Closure;

class ValidateApiToken
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
        $token = $request->query('api_token');

        if (! $token) {
            abort(403, __('Invalid api token.'));
        }

        return $next($request);
    }
}
