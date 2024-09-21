<?php

namespace App\Http\Middleware;

use anlutro\LaravelSettings\Facade as Setting;
use Closure;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        $vigencia = \DB::connection('mysql_system')->table('vigencia')->select('*')->where('vigencia', Setting::get('vigencia'))->first();
        $vigenciaActual = \DB::connection('mysql_system')->table('vigencia')->select('*')->where('vigencia', date('Y'))->first();

        if($vigencia == null && $vigenciaActual == null){
            return response(view('errors.403', ['message'=>'No existen registros de vigencias en el sistema. Deberá configurar inicialmente la vigencia antes de operar el sistema.']));
        }elseif($vigencia == null && $vigenciaActual != null){
            $vigencia = $vigenciaActual;
        }elseif ($vigencia != null && $vigenciaActual != null) {
            if ($vigencia->vigencia < $vigenciaActual->vigencia) {
                $vigencia = $vigenciaActual;
            }
        }

        $empresa = \DB::connection('mysql_system')->table('parametros_empresa')->select('*')->where('vigencia_id',$vigencia->id)->first();
        $pqr = \DB::connection('mysql_system')->table('parametros_to')->select('*')->where('vigencia_id',$vigencia->id)->first();
        $to = \DB::connection('mysql_system')->table('parametros_empresa')->select('*')->where('vigencia_id',$vigencia->id)->first();
        $tramites = \DB::connection('mysql_system')->table('parametros_tramites')->select('*')->where('vigencia_id',$vigencia->id)->first();
        $gd = \DB::connection('mysql_system')->table('parametros_gestion_documental')->select('*')->where('vigencia_id',$vigencia->id)->first();

        if($empresa == null || $pqr == null || $to == null || $tramites == null || $gd == null){
            return response(view('errors.403', ['message'=>'No existen registros de configuración para el sistema. Deberá configurar inicialmente la configuración de la actual vigencia antes de operar el sistema.']));        
        }else{
            Setting::set('vigencia',$vigencia->vigencia);
            Setting::set('salario_minimo', $vigencia->salario_minimo);
            Setting::save();
            if($empresa->empresa_logo_menu  != null){
                Setting::set('logo_menu', $empresa->empresa_logo_menu);
            }        
            if ($empresa->empresa_logo  != null) {
                Setting::set('logo_empresa', $empresa->empresa_logo);
            }        
            if ($empresa->empresa_header != null) {
                Setting::set('header', $empresa->empresa_header);
            }        
            if ($empresa->firma_director != null) {
                Setting::set('firma_director', $empresa->firma_director);
            }        
            
            Setting::set('empresa', $empresa->id);
            Setting::set('empresa_nombre', $empresa->empresa_nombre);
            Setting::set('empresa_sigla', $empresa->empresa_sigla);
            Setting::set('empresa_direccion', $empresa->empresa_direccion);
            Setting::set('empresa_telefono', $empresa->empresa_telefono);
            Setting::set('empresa_correo', $empresa->empresa_correo_contacto);
            Setting::set('empresa_web', $empresa->empresa_web);
            Setting::set('empresa_admin_email', $empresa->correo_administrador);
            Setting::set('empresa_descripcion', $empresa->descripcion);
            Setting::set('empresa_horario', $empresa->horario);
            Setting::set('empresa_facebook', $empresa->facebook);
            Setting::set('empresa_twitter', $empresa->twitter);            
            Setting::set('encabezado_documento', $gd->encabezado_documento);
            Setting::set('pie_documento', $gd->pie_documento);
            Setting::set('empresa_mapa', $empresa->empresa_map_coordinates);

            Setting::save();

            if(date('Y-m-d') > $vigencia->final_vigencia){
                Setting::set('db_autorizado', false);
                Setting::save();
            } else {
                Setting::set('db_autorizado', true);
                Setting::save();
            }

            if(Setting::get('db_autorizado') == false){
                \Session::flash('alerta_vigencia', 'La vigencia actual no permite realizar cambios.');
            } else {
                \Session::remove('alerta_vigencia');
            }

            return $next($request);
        }        
    }
}