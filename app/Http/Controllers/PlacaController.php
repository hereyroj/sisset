<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Validation\Rule;
use App\archivo_carpeta;
use App\archivo_carpeta_estado;
use App\Exports\PlacasPedidas;
use App\placa;
use App\Mail\NotificarPreAsignacion;
use App\solicitud_preasignacion;
use App\tramite_servicio;
use App\vehiculo_clase;
use App\vehiculo_clase_letra_terminacion;
use App\vehiculo_servicio;
use Maatwebsite\Excel\Facades\Excel;

class PlacaController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado entrada',
            '3' => 'Radicado salida',
            '4' => 'Consecutivo',
        ];
        $sFiltro = null;

        return view('admin.tramites.placa.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function nuevasPlacas()
    {
        $clases_vehiculos = vehiculo_clase::all();
        $servicios_vehiculos = vehiculo_servicio::pluck('name','id');
        $letrasTerminacion = vehiculo_clase_letra_terminacion::pluck('name', 'id');        

        return view('admin.tramites.placa.nuevasPlacas', ['clases_vehiculos' => $clases_vehiculos, 'servicios_vehiculos'=>$servicios_vehiculos, 'letrasTerminacion'=>$letrasTerminacion])->render();
    }

    public function getServiciosPorClaseVehiculo($clase_id)
    {
        $clase_vehiculo = vehiculo_clase::with('hasServicios')->find($clase_id);        

        return $clase_vehiculo->hasServicios->toJson();
    }

    public function getLetrasTerminacionPorClaseVehiculo($clase_id)
    {
        $clase_vehiculo = vehiculo_clase::with('hasLetrasTerminacion')->find($clase_id);

        return $clase_vehiculo->hasLetrasTerminacion->toJson();
    }

    public function obtenerPlacas()
    {
        $placas = placa::with('hasVehiculosClases', 'hasVehiculoServicio')->paginate(50);

        return view('admin.tramites.placa.listadoPlacas', ['placas' => $placas])->render();
    }

    private function registrarPlaca($letras, $numeros, $letraFinal, $orden, $servicio, $clases, $requiredLetraFinal)
    {
        $cadena = '';

        if($requiredLetraFinal === 'SI'){
            $numeros = sprintf("%'.02d", $numeros);
        }else{
            $numeros = sprintf("%'.03d", $numeros);
        }        

        if($orden == 'L'){
            if($requiredLetraFinal === 'SI'){
                $letraFinal = vehiculo_clase_letra_terminacion::find($letraFinal);
                $cadena = $letras.$numeros.$letraFinal->name;
            }else{
                $cadena = $letras.$numeros;
            }
        }else{
            $cadena = $numeros.$letras;
        }

        $placa = placa::create([
            'name' => $cadena,
            'vehiculo_servicio_id' => $servicio,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        foreach ($clases as $clase){
            $placa->hasVehiculosClases()->attach($clase, ['vehiculo_servicio_id'=>$servicio,'created_at'=>date('Y-m-d H:i:s')]);
        }

    }

    public function ingresarPlacas(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'letras_rango_inicial' => 'required|string',
            'letras_rango_final' => 'required|string',
            'numeros_rango_inicial' => 'required|numeric',
            'numeros_rango_final' => 'required|numeric',
            'clases_vehiculos' => 'required|array',
            'orden' => ['required', Rule::in(['L','N'])],
            'required_letra_final' => Rule::in(['SI','NO']),
            'letra_terminacion' => ['required','string']
        ], [
            'letras_rango_inicial.required' => 'No se ha especificado el rango alfabético inicial.',
            'letras_rango_inicial.string' => 'El valor especificado para el rango alfabético inicial no es válido.',
            'letras_rango_final.required' => 'No se ha especificado el rango alfabético final.',
            'letras_rango_final.string' => 'El valor especificado para el rango alfabético final no es válido.',
            'numeros_rango_inicial.required' => 'No se ha especificado el rango numérico inicial.',
            'numeros_rango_inicial.numeric' => 'El valor especificado para el rango numérico inicial no es válido.',
            'numeros_rango_final.required' => 'No se ha especificado el rango numérico final.',
            'numeros_rango_final.numeric' => 'El valor especificado para el rango numérico final no es válido.',
            'clases_vehiculos.required' => 'No se ha especificado al menos una clase de vehículo.',
            'clases_vehiculos.array' => 'El valor especificado para las clases de vehículos no es válido.',
            'orden.required' => 'No se ha especificado el orden de los elementos que conforman la placa.',
            'orden.in' => 'El valor especificado para el orden de los elementos que conforman la placa no es válido.',
            'required_letra_final.required' => 'No se ha especificado si se requiere una letra final.',
            'required_letra_final.in' => 'El valor especificado para el requerimiento de una letra final no es válido.',
            'letra_terminacion.string' => 'La letra final especificada no tiene un formato válido.',
            'letra_terminacion.in' => 'El valor especificado para la letra final no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $ini = strtoupper($request->letras_rango_inicial);
        $end = strtoupper($request->letras_rango_final);

        $num1 = $request->numeros_rango_inicial;
        $num2 = $request->numeros_rango_final;

        $clases_vehiculos = vehiculo_clase::whereIn('id', $request->clases_vehiculos)->get();
        $clases_con_letras = $clases_vehiculos->filter(function($item){
            return $item->hasLetrasTerminacion() != null;
        });

        /*
         * Proceso de validación de los rangos alfabéticos
         */
        if(strlen($ini) != strlen($end)){
            return response()->view('admin.mensajes.errors', [
                'mensaje' => 'Los rangos alfabéticos inicial y final no tienen la misma longitud de caracteres.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            switch (strlen($ini)){
                case 2:
                    if($ini[0] > $end[0]){
                        return response()->view('admin.mensajes.errors', [
                            'mensaje' => 'El primer caracter del rango inicial es superior al del rango final, lo cual no es válido.',
                            'encabezado' => '¡Error!',
                        ], 200);
                    }else{
                        if($ini[1] > $end[1] && $ini[0] == $end[0]){
                            return response()->view('admin.mensajes.errors', [
                                'mensaje' => 'El primer caracter del rango inicial es igual al del rango final, pero el segundo caracter del rango inicial es superior al del rango final, lo cual no es válido.',
                                'encabezado' => '¡Error!',
                            ], 200);
                        }
                    }
                    break;
                case 3:
                    if($ini[0] > $end[0]){
                        return response()->view('admin.mensajes.errors', [
                            'mensaje' => 'El primer caracter del rango inicial es superior al del rango final, lo cual no es válido.',
                            'encabezado' => '¡Error!',
                        ], 200);
                    }else{
                        if($ini[1] > $end[1] && $ini[0] == $end[0]){
                            return response()->view('admin.mensajes.errors', [
                                'mensaje' => 'El primer caracter del rango inicial es igual al del rango final, pero el segundo caracter del rango inicial es superior al del rango final, lo cual no es válido.',
                                'encabezado' => '¡Error!',
                            ], 200);
                        }else{
                            if($ini[2] > $end[2] && $ini[0] == $end[0] && $ini[1] == $end[1]){
                                return response()->view('admin.mensajes.errors', [
                                    'mensaje' => 'Los primeros dos caracteres del rango inicial y del rango final son iguales, pero el tercer caracter del rango inicial es superior al del rango final, lo cual no es válido.',
                                    'encabezado' => '¡Error!',
                                ], 200);
                            }
                        }
                    }
                    break;
                default:
                    return response()->view('admin.mensajes.errors', [
                        'mensaje' => 'Los rangos alfabéticos inicial y final no tienen la longitud de caracteres requerida.',
                        'encabezado' => '¡Error!',
                    ], 200);
            }
        }
        /*
         * Otras validaciones
         */
        if($request->required_letra_final === 'SI'){//validación del formato, solo se acepta letra final si el maximo rango numérico es inferior a dos dígitos y todas las clases de vehículos necesitan letra de terminación
            if($num2 > 99){
                return response()->view('admin.mensajes.errors', [
                    'mensaje' => 'El rango numérico final es mayor al permitido (99).',
                    'encabezado' => '¡Completado!',
                ], 200);
            }

            if(!$clases_vehiculos->count() === $clases_con_letras->count()){
                return response()->view('admin.mensajes.errors', [
                    'mensaje' => 'No todas las clases especificadas requieren letra de terminación. Por favor verifica y repita el proceso nuevamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }

        if($ini === $end){//las letras son iguales
            if($num1 > $num2){//validación del rango numérico
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El rango numérico inicial es menor al rango numérico final.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
        /*
         * Inicio del proceso de creación de placas
         */
        try{
            \DB::beginTransaction();
            $numeros = 0;
            $max_numeric_range = 0;
            if($request->requiere_letra_final == 'SI'){
                $max_numeric_range = 99;
            }else{
                $max_numeric_range = 999;
            }

            switch (strlen($ini))
            {
                case 2://dos letras

                    if($ini == $end){//ambas letras son iguales, sole se realiza iteración de los números
                        for ($i=$num1;$i<=$num2;$i++){
                            $this->registrarPlaca($ini, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->required_letra_final);
                        }
                    }else{
                        if($ini[0] == $end[0]){//primeras letras son iguales, segundas no
                            $segundas = range($ini[1], $end[1]);
                            foreach ($segundas as $segunda){
                                if($segunda == $ini[1]){
                                    $i = $num1;
                                    $numFinal = $max_numeric_range;
                                }elseif($segunda == $end[1]){
                                    $i = 0;
                                    $numFinal = $num2;
                                }else{
                                    $i = 0;
                                    $numFinal = $max_numeric_range;
                                }
                                for($i;$i<=$numFinal;$i++){
                                    $this->registrarPlaca($ini[0].$segunda, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                                }
                            }
                        }else{//ninguna letra es igual, iteración completa
                            $primeras = range($ini[0], 'Z');
                            foreach ($primeras as $primera){
                                $segundas = range($ini[1], 'Z');
                                foreach ($segundas as $segunda){
                                    if($primera.$segunda == $ini){
                                        $i = $num1;
                                        $numFinal = $num2;
                                    }elseif($primera.$segunda == $end){
                                        $i = 0;
                                        $numFinal = $num2;
                                    }else{
                                        $i = 0;
                                        $numFinal = $max_numeric_range;
                                    }
                                    for($i;$i<=$numFinal;$i++){
                                        $this->registrarPlaca($ini[0].$segunda, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 3:
                    if($ini == $end){//ambas letras son iguales, sole se realiza iteración de los números
                        for ($i=$num1;$i<=$num2;$i++){
                            $this->registrarPlaca($ini, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                        }
                    }else{
                        if($ini[0] == $end[0]){//primeras letras son iguales
                            if($ini[1] = $end[1]){//segundas letras son iguales, solo se calculan las terceras letras
                                $terceras = range($ini[2], $end[2]);
                                foreach ($terceras as $tercera){
                                    if($ini[0].$ini[1].$tercera == $ini){
                                        $i = $num1;
                                        $numFinal = $max_numeric_range;
                                    }elseif($tercera == $end[2]){
                                        $i = 0;
                                        $numFinal = $num2;
                                    }else{
                                        $i = 0;
                                        $numFinal = $max_numeric_range;
                                    }
                                    for($i;$i<=$numFinal;$i++){
                                        $this->registrarPlaca($ini[0].$ini[1].$tercera, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                                    }
                                }
                            }else{//segundas letras no son iguales
                                $segundas = range($ini[1], 'Z');
                                $i = 0;
                                foreach ($segundas as $segunda){
                                    if($ini[0].$segunda == $end[0].$end[1]){
                                        $terceras = range($ini[2], $end[2]);
                                    }else{
                                        $terceras = range($ini[2], 'Z');
                                    }
                                    foreach ($terceras as $tercera){
                                        if($ini[0].$segunda.$tercera == $ini){
                                            $i = $num1;
                                            $numFinal = $max_numeric_range;
                                        }elseif($tercera == $end[2]){
                                            $i = 0;
                                            $numFinal = $num2;
                                        }else{
                                            $i = 0;
                                            $numFinal = $max_numeric_range;
                                        }
                                        for($i;$i<=$numFinal;$i++){
                                            $this->registrarPlaca($ini[0].$segunda.$tercera, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                                        }
                                    }
                                }
                            }
                        }else{//las primeras letras no son iguales. Iteración completa
                            $primeras = range($ini[0], 'Z');
                            foreach ($primeras as $primera){
                                if($primera == $end[0]){
                                    $segundas = range($ini[1], $end[1]);
                                }else{
                                    $segundas = range($ini[1], 'Z');
                                }
                                foreach ($segundas as $segunda){
                                    if($ini[0].$segunda == $end[0].$end[1]){
                                        $terceras = range($ini[2], $end[2]);
                                    }else{
                                        $terceras = range($ini[2], 'Z');
                                    }
                                    foreach ($terceras as $tercera){
                                        if($primera.$segunda.$tercera == $ini){
                                            $i = $num1;
                                            $numFinal = $max_numeric_range;
                                        }elseif($tercera == $end[2]){
                                            $i = 0;
                                            $numFinal = $num2;
                                        }else{
                                            $i = 0;
                                            $numFinal = $max_numeric_range;
                                        }
                                        for($i;$i<=$numFinal;$i++){
                                            $this->registrarPlaca($ini[0].$segunda.$tercera, $i, $request->letra_terminacion, $request->orden, $request->vehiculo_servicio_id, $request->clases_vehiculos, $request->requiere_letra_final);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                default:
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Ha ocurrido un error inesperado con la longitud de caracteres.'],
                        'encabezado' => 'No se han registrado estos rangos:',
                    ], 200);
                    break;
            }
            \DB::commit();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se han ingresado los rangos correctamente en el sistema.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error inesperado.'],
                'encabezado' => 'No se han registrado estos rangos:',
            ], 200);
        }
    }

    public function liberarPlaca($id)
    {
        try {
            $placa = placa::find($id);
            $placa->hasPreAsignacionActiva()->pivot->update(['fecha_liberacion' => date('Y-m-d H:i:s')]);
            if ($placa->hasPreAsignacionActiva() == null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha liberado la especie venal.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la liberación',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la liberación',
            ], 200);
        }
    }

    public function editarPlaca($id)
    {
        $placa = placa::with('hasVehiculoServicio')->find($id);
        $clases_vehiculos = vehiculo_clase::all();
        $servicios_vehiculos = vehiculo_servicio::pluck('name', 'id');
        return view('admin.tramites.placa.editarPlaca', [
            'placa' => $placa,
            'clases_vehiculos' => $clases_vehiculos,
            'servicios_vehiculos' => $servicios_vehiculos
        ])->render();
    }

    public function actualizarPlaca(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|integer|exists:placa,id',
            'placa' => ['required', 'string', Rule::unique('placa','name')->ignore($request->id)],
            'vehiculo_servicio_id' => 'required|integer|exists:vehiculo_servicio,id',
            'clases_vehiculos' => 'required|array'
        ], [
            'id.required' => 'No se ha especificado la placa a actualizar.',
            'id.integer' => 'El ID de la placa a actualizar especificada no tiene un formato válido.',
            'id.exists' => 'La placa a actualizar especificada no existe en el sistema.',
            'placa.required' => 'No se ha especificado la placa.',
            'placa.string' => 'La placa especificada no tiene un formato válido.',
            'placa.unique' => 'La placa especificada ya existe en el sistema.',
            'vehiculo_servicio_id.required' => 'No se ha especificado el servicio del vehículo al que está dirigida la placa.',
            'vehiculo_servicio_id.integer' => 'El servicio del vehículo al que está dirigida la placa no tiene un formato válido.',
            'vehiculo_servicio_id.exists' => 'El servicio del vehículo especificado al que está dirigida la placa no existe en el sistema.',
            'clases_vehiculos.required' => 'No se han especificado las clases de vehículos al que está dirigida la placa.',
            'clases_vehiculos.array' => 'Las clases de vehículos especificados al que está dirigida la placa no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $placa = placa::find($request->id);
            $placa->name = $request->placa;
            $placa->vehiculo_servicio_id = $request->vehiculo_servicio_id;
            if ($placa->save()) {
                $placa->hasVehiculosClases()->detach();
                foreach ($request->clases_vehiculos as $clase){
                    $placa->hasVehiculosClases()->attach($clase, ['vehiculo_servicio_id'=>$request->vehiculo_servicio_id,'created_at'=>date('Y-m-d H:i:s')]);
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la especie venal.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la actualización',
                ], 200);
            }
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Se ha producido un error. Si el problema persiste, por favor contacte a un administrador.'],
                'encabezado' => 'Error en la actualización',
            ], 200);
        }
    }

    public function multipleLiberacionPlacas(Request $request)
    {
        try {
            $especies_venales = placa::whereIn('id', $request->evs)->get();
            foreach ($especies_venales as $placa) {
                $placa->hasPreAsignacionActiva()->pivot->update(['fecha_liberacion' => date('Y-m-d H:i:s')]);
            }
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se han actualizado el estado a todas las especies venales especificadas que no han sido matriculadas.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se han podido actualizar las especies venales especificadas.'],
                'encabezado' => 'Error en la liberación',
            ], 200);
        }
    }

    public function generarReportePlacasPedidas(Request $request)
    {
        $servicios = tramite_servicio::whereHas('hasFinalizacion', function ($query) use ($request){
            $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
        })->whereHas('hasTramites', function ($query){
            $query->where('requiere_placa', 'SI');
        })->get();

        if($servicios->count() > 0){
            return Excel::download(new PlacasPedidas($servicios), 'ReportePlacasPedidas-'.$request->fecha_inicio_submit.'-a-'.$request->fecha_fin_submit.'.xlsx');
        }else{
            \Session::flash('errorReporte', 'No hay registros de sustratos con los parámetros indicados para el reporte.');
            return back();
        }

    }
}
