<?php

namespace App\Http\Middleware;

use Closure;
use anlutro\LaravelSettings\Facade as Setting;

class autorizeEloquentSave
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
        \Event::listen('eloquent.saving: *', function ($eventName, $model) {
            if(array_first($model)->connection === 'mysql_system'){
                return true;
            } else {
                return Setting::get('db_autorizado');
            }
        });

        return $next($request);
    }
}
