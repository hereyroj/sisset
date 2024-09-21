<?php

namespace App\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAMiddleware
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
        $authenticator = app(Authenticator::class)->boot($request);
        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }
        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
