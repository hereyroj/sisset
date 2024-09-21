<?php

namespace App\Http\Middleware;

use Closure;

class necesitaRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null)
    {
        if ($roles == null) {
            if ($request->ajax()) {
                return response()->view('admin.mensajes/errors', [
                    'errors' => [
                        'Ha ocurrido un error de configuración de acceso.'],
                    'encabezado' => 'Acceso denegado:',
                ], 200);
            } else {
                return response()->view('errors.403', ['message' => 'Ha ocurrido un error de configuración de acceso.']);
            }
        } else {
            $lista = '';
            $acceso = false;
            $roles = explode("|", $roles);
            foreach ($roles as $rol) {
                $lista = $lista.'<li>'.$rol.'</li>';
                if (auth()->user()->hasRole($rol)) {
                    $acceso = true;
                }
            }

            if ($acceso == false) {
                if ($request->ajax()) {
                    return response()->view('admin.mensajes/errors', [
                        'errors' => ['No tiene el/los siguiente(s) rol(es) requerido(s) para poder ingresar o realizar esta acción:<br><ul>'.$lista.'</ul>'],
                        'encabezado' => 'Acceso denegado:',
                    ], 200);
                } else {
                    return response()->view('errors.403', ['message' => 'No tiene el/los siguiente(s) rol(es) requerido(s) para poder ingresar o realizar esta acción:<br><ul>'.$lista.'</ul>']);
                }
            } else {
                return $next($request);
            }
        }
    }
}
