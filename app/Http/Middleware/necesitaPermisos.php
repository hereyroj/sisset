<?php

namespace App\Http\Middleware;

use Closure;

class necesitaPermisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permisos = null)
    {
        if ($permisos == null) {
            if ($request->ajax()) {
                return response()->view('admin.mensajes/errors', [
                    'errors' => ['Error en la configuración de permisos.'],
                    'encabezado' => 'Denegado:',
                ], 200);
            } else {
                return response()->view('errors.403', ['message' => 'Error en la configuración de permisos.']);
            }
        } else {
            $acceso = false;
            $lista = '';
            $permisos = explode("|", $permisos);
            foreach ($permisos as $permiso) {
                $lista = $lista.'<li>'.$permiso.'</li>';
                if (auth()->user()->puedeHacerlo($permiso)) {
                    $acceso = true;
                }
            }

            if ($acceso == false) {
                if ($request->ajax()) {
                    return response()->view('admin.mensajes/errors', [
                        'errors' => ['0' => 'No tiene el/los permiso(s) requerido(s) para poder ingresar o realizar la acción:<br><ul>'.$lista.'</ul>'],
                        'encabezado' => 'Denegado:',
                    ], 200);
                } else {
                    return response()->view('errors.403', ['message' => 'No tiene el/lo(s) permiso(s) requerido(s) para poder ingresar o realizar la acción:<br><ul>'.$lista.'</ul>']);
                }
            } else {
                return $next($request);
            }
        }
    }
}
