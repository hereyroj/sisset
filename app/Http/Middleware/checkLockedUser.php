<?php

namespace App\Http\Middleware;

use Closure;

class checkLockedUser
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
        if(auth()->user()->lock_session === 'yes'){
            auth()->guard()->logout();

            $request->session()->flush();

            $request->session()->regenerate();

            return redirect('/')->withErrors(['No tiene permitido ingresar al sitio.']);
        }
        return $next($request);
    }
}
