<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\vehiculo_carroceria;
use Storage;
use Illuminate\Validation\Rule;
use App\vehiculo;
use App\vehiculo_liq_base_gravable;
use App\vehiculo_liquidacion;
use App\vehiculo_liquidacion_descuento;
use App\vehiculo_liquidacion_mes;
use App\vehiculo_liquidacion_pago;
use App\vehiculo_liquidacion_vigencia;
use App\vehiculo_marca;
use App\vehiculo_clase;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\vehiculo_clase_grupo;
use App\vehiculo_cilindraje_grupo;
use App\vehiculo_bateria_grupo;
use App\vehiculo_bateria_tipo;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\VehiculoBaseGravable;

class ImpuestoController extends Controller
{

    public function calcularValores($vehiculoId, $vigenciaId)
    {
        return json_encode($this->calcularValor($vehiculoId, $vigenciaId));
    }

    private function getBaseGravable($vigencia, $vehiculo)
    {
        $baseGravable = null;
        if($vehiculo->hasVehiculoClase->name == 'MOTOCICLETA' || $vehiculo->hasVehiculoClase->name == 'MOTOCARRO'){
            if($vigencia->vigencia > 2016){
                
            }else{

            }
        }elseif($vehiculo->hasVehiculoClase->name == 'CAMIONETA DOBLECABINA'){

        }elseif($vehiculo->hasVehiculoClase->name == 'CAMIONETA' || $vehiculo->hasVehiculoClase->name == 'CAMPERO'){

        }elseif($vehiculo->hasVehiculoClase->name == 'AMBULANCIA') {

        }elseif($vehiculo->hasVehiculoClase->name =='BUS' || $vehiculo->hasVehiculoClase->name =='BUSETA' || $vehiculo->hasVehiculoClase->name == 'MICROBÚS'){

        }else{
            $baseGravable = vehiculo_liq_base_gravable::where('vehiculo_linea_id', $vehiculo->vehiculo_linea_id)->where('modelo', $vehiculo->modelo)->where('vigencia',$vigencia->vigencia)->first();
        }   
        return $baseGravable;
    }

    private function calcularValor($vehiculo, $vigenciafrm)
    {
        try{
            $vehiculo = vehiculo::find($vehiculo);
            $vigencia_liquidacion = vehiculo_liquidacion_vigencia::find($vigenciafrm);
            $base_gravable = $this->getBaseGravable($vigenciafrm, $vehiculo);
            if($base_gravable == null){
                return null;
            }else{
                $avaluo = $base_gravable->avaluo * 1000;
                $intereses = 0;
                $descuentos = 0;
                $derechosEntidad = $vigencia_liquidacion->derechos_entidad;
                $impuesto = ($avaluo * $vigencia_liquidacion->impuesto_publico) / 1000;
                $descuentosActivos = vehiculo_liquidacion_descuento::where('ve_li_vi_id', $vigenciafrm)->whereDate('vigente_desde', '<=', date('Y-m-d'))->where('vigente_hasta','>=',date('Y-m-d'))->get();
                foreach ($descuentosActivos as $descuento){
                    $descuentos += $impuesto * ($descuento->porcentaje / 100);
                }
                if($base_gravable != null){
                    if(date('Y') > $vigencia_liquidacion->vigencia){//si la vigencia a liquidar es menor al año actual, se procede a calcular los intereses
                        $vigencias = vehiculo_liquidacion_vigencia::where('vigencia', '>', $vigencia_liquidacion->vigencia)->orderBy('vigencia', 'asc')->get();
                        foreach ($vigencias as $vigencia){
                            $meses = $vigencia->hasMeses->chunk($vigencia->cantidad_meses_intereses)->toArray();
                            foreach ($meses as $mes){
                                if(is_array($mes)){
                                    $intereses += abs(round($impuesto * ((array_first($mes)['pivot']['porcentaje_interes'] / 100) / 366) * $this->calcularDias($vigencia->vigencia, array_pluck($mes, 'id')), -3));
                                }else{
                                    $intereses += abs(round($impuesto * (($mes['pivot']['porcentaje_interes'] / 100) / 366) * $this->calcularDias($vigencia->vigencia, [$mes->id]), -3));
                                }
                            }
                        }
                        return ['impuesto'=>$impuesto, 'intereses'=>$intereses,'descuentos'=>$descuentos, 'derechos_entidad'=>$derechosEntidad, 'valor_total'=>($impuesto+$intereses+$derechosEntidad)-$descuentos,'avaluo'=>$avaluo];
                    }elseif(date('Y') == $vigencia_liquidacion->vigencia){//Si la vigencia es la actual
                        return ['impuesto'=>$impuesto, 'intereses'=>$intereses,'descuentos'=>$descuentos, 'derechos_entidad'=>$derechosEntidad, 'valor_total'=>($impuesto+$intereses+$derechosEntidad)-$descuentos,'avaluo'=>$avaluo];
                    }else{//La vigencia es mayor a la actual. Error
                        return 'Error de vigencia';
                    }
                }else{
                    return 'Sin Base Gravable';
                }        
            }            
        }catch (\Exception $e){
            return 'Error de cálculo.';
        }
    }

    private function calcularDias($vigencia, $meses)
    {
        $dias = 0;
        if($vigencia < date('Y')){
            foreach ($meses as $mes){
                $dias += cal_days_in_month(CAL_GREGORIAN, $mes, $vigencia);
            }
        }elseif($vigencia == date('Y')){
            foreach ($meses as $mes){
                if($mes <= date('m')){
                    if($mes < date('m')){
                        $dias += cal_days_in_month(CAL_GREGORIAN, $mes, $vigencia);
                    }else{
                        $dias += date('d');
                    }
                }
            }
        }
        return $dias;
    }

    public function administar()
    {
        return view('admin.tramites.impuestos.administrar');
    }

    public function nuevaLiquidacion($id)
    {
        $vehiculo = vehiculo::find($id);
        if($vehiculo->hasPropietariosActivos()->count() == 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El vehículo especificado no tiene propietarios vinculados.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
        $vigencias = vehiculo_liquidacion_vigencia::orderBy('vigencia')->pluck('vigencia', 'id');
        $descuentos = vehiculo_liquidacion_descuento::pluck('concepto', 'id');
        return view('admin.tramites.impuestos.nuevaLiquidacion', ['vehiculoId'=>$vehiculo->id,'vigencias'=>$vigencias,'descuentos'=>$descuentos])->render();
    }

    public function crearLiquidacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => 'required|integer|exists:vehiculo_liquidacion_vigencia,id',
            'vehiculo' => 'required|integer|exists:vehiculo,id'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia a liquidar.',
            'vigencia.integer' => 'El ID de la vigencia especificada no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en el sistema.',
            'vehiculo.required' => 'No se ha especificado el vehículo a liquidar.',
            'vehiculo.integer' => 'El ID del vehículo a liquidar no tiene un formato válido.',
            'vehiculo.exists' => 'El vehículo especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $vehiculo = vehiculo::find($request->vehiculo);
                if($vehiculo->hasVigenciaLiquidada($request->vigencia) != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El vehículo ya cuenta con una liquidación válida para la vigencia especificada.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                } elseif($vehiculo->hasPropietariosActivos()->count() == 0){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El vehículo especificado no tiene propietarios vinculados.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }else {
                    $vigencia = vehiculo_liquidacion_vigencia::find($request->vigencia);
                    if($vigencia->vigencia > date('Y')){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['La vigencia especificada en mayor a la vigencia actual.'],
                            'encabezado' => 'Error en la solicitud',
                        ], 200);
                    }
                    $codigo = null;
                    $consultaLiquidacion = $this->calcularValor($request->vehiculo, $request->vigencia);
                    $ultimaLiquidacion = vehiculo_liquidacion::where('codigo', 'like', date('Y').'%')->orderBy('created_at', 'desc')->first();
                    $fecha_vencimiento = null;
                    if($vigencia->vigencia == date('Y')){
                        $fecha_vencimiento = date('Y').'-12-31';
                    }else{
                        $fecha_vencimiento = date('Y-m-d');
                    }
                    if($ultimaLiquidacion != null){
                        $codigo = $ultimaLiquidacion->codigo;
                        $codigo++;
                    }else{
                        $codigo = date('Y').'000001';
                    }
                    vehiculo_liquidacion::create([
                        'valor_total' => $consultaLiquidacion['valor_total'],
                        'valor_mora_total' => $consultaLiquidacion['intereses'],
                        'valor_descuento_total' => $consultaLiquidacion['descuentos'],
                        'valor_impuesto' => $consultaLiquidacion['impuesto'],
                        'valor_avaluo' => $consultaLiquidacion['avaluo'],
                        'derechos_entidad' => $consultaLiquidacion['derechos_entidad'],
                        'fecha_vencimiento' => $fecha_vencimiento,
                        'vehiculo_liq_vig_id' => $request->vigencia,
                        'vehiculo_id' => $request->vehiculo,
                        'codigo' => $codigo
                    ]);
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado la nueva liquidación.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear la liquidación.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function obtenerLiquidaciones($placaVehiculo)
    {
        $vehiculo = vehiculo::with('hasLiquidaciones')->where('placa', $placaVehiculo)->first();
        return view('admin.tramites.impuestos.listadoLiquidaciones', ['liquidaciones'=>$vehiculo->hasLiquidaciones,'id'=>$vehiculo->id])->render();
    }

    public function obtenerInfoVehiculo($placaVehiculo)
    {
        $vehiculo = vehiculo::where('placa', $placaVehiculo)->first();
        if($vehiculo != null){
            return view('admin.tramites.impuestos.infoVehiculo', ['vehiculo'=>$vehiculo])->render();
        }else{
            return null;
        }
    }

    public function obtenerVigencias()
    {
        $vigencias = vehiculo_liquidacion_vigencia::paginate(25);
        return view('admin.tramites.impuestos.listadoVigencias', ['vigencias'=>$vigencias])->render();
    }

    public function nuevaVigencia()
    {
        $meses = vehiculo_liquidacion_mes::all();
        return view('admin.tramites.impuestos.nuevaVigencia', ['meses'=>$meses])->render();
    }

    public function crearVigencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => 'required|numeric|unique:vehiculo_liquidacion_vigencia,vigencia',
            'impuesto_publico' => 'required|numeric',
            'cantidad_meses' => 'required|numeric',
            'derechos' => 'required|numeric'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.numeric' => 'La vigencia especificada no tiene un formato válido.',
            'vigencia.unique' => 'La vigencia especificada ya está registrada.',
            'impuesto_publico.required' => 'No se ha especificado el valor del impuesto.',
            'impuesto_publico.numeric' => 'El valor del impuesto especificado no tiene un formato válido.',
            'cantidad_meses.required' => 'No se ha especificado la cantidad de meses de intereses.',
            'cantidad_meses.numeric' => 'La cantidad de meses de intereses especificada no tiene un formato válido.',
            'derechos.required' => 'No se ha especificado el valor de los derechos de la entidad.',
            'derechos.numeric' => 'El valor de los derechos de la entidad especificado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $vigencia = vehiculo_liquidacion_vigencia::create([
                    'vigencia' => $request->vigencia,
                    'impuesto_publico' => $request->impuesto_publico,
                    'cantidad_meses_intereses' => $request->cantidad_meses,
                    'derechos_entidad' => $request->derechos
                ]);
                $meses = vehiculo_liquidacion_mes::all();
                foreach ($meses as $mes){
                    $vigencia->hasMeses()->attach($mes->id, ['porcentaje_interes'=>$request->input(['mes_'.$mes->id])]);
                }
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la vigencia correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear la vigencia.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarVigencia($id)
    {
        $meses = vehiculo_liquidacion_mes::all();
        $vigencia = vehiculo_liquidacion_vigencia::find($id);
        return view('admin.tramites.impuestos.editarVigencia', ['meses'=>$meses,'vigencia'=>$vigencia])->render();
    }

    public function actualizarVigencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liquidacion_vigencia,id',
            'vigencia' => ['required','numeric',Rule::unique('vehiculo_liquidacion_vigencia', 'vigencia')->ignore($request->id)],
            'impuesto_publico' => 'required|numeric',
            'cantidad_meses' => 'required|numeric',
            'derechos' => 'required|numeric'
        ], [
            'id.required' => 'No se ha especificado la vigencia a modificar.',
            'id.integer' => 'El ID de la vigencia especificada no tiene un formato válido.',
            'id.exists' => 'La vigencia especificada no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.numeric' => 'La vigencia especificada no tiene un formato válido.',
            'vigencia.unique' => 'La vigencia especificada ya está registrada.',
            'impuesto_publico.required' => 'No se ha especificado el valor del impuesto.',
            'impuesto_publico.numeric' => 'El valor del impuesto especificado no tiene un formato válido.',
            'cantidad_meses.required' => 'No se ha especificado la cantidad de meses de intereses.',
            'cantidad_meses.numeric' => 'La cantidad de meses de intereses especificada no tiene un formato válido.',
            'derechos.required' => 'No se ha especificado el valor de los derechos de la entidad.',
            'derechos.numeric' => 'El valor de los derechos de la entidad especificado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $vigencia = vehiculo_liquidacion_vigencia::find($request->id);
                $vigencia->vigencia = $request->vigencia;
                $vigencia->impuesto_publico = $request->impuesto_publico;
                $vigencia->cantidad_meses_intereses = $request->cantidad_meses;
                $vigencia->derechos_entidad = $request->derechos;
                $vigencia->save();
                $vigencia->hasMeses()->detach();
                $meses = vehiculo_liquidacion_mes::all();
                foreach ($meses as $mes){
                    $vigencia->hasMeses()->attach($mes->id, ['porcentaje_interes'=>$request->input(['mes_'.$mes->id])]);
                }
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la vigencia correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se pudo actualizar la vigencia.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function obtenerBasesGravables()
    {
        $basesGravables = vehiculo_liq_base_gravable::paginate(50);
        return view('admin.tramites.impuestos.listadoBasesGravables', ['basesGravables'=>$basesGravables])->render();
    }

    public function nuevaBaseGravable()
    {        
        $vehiculosMarcas = vehiculo_marca::orderBy('name')->pluck('name','id');
        $clasesVehiculos = vehiculo_clase::orderBy('name')->pluck('name','id');
        $carroceriasVehiculos = vehiculo_carroceria::orderBy('name')->pluck('name','id');
        return view('admin.tramites.impuestos.nuevaBaseGravable', ['vehiculosMarcas' => $vehiculosMarcas->prepend('Ninguna', null), 'vehiculosClases'=>$clasesVehiculos->prepend('Ninguna', null), 'vehiculosCarrocerias'=>$carroceriasVehiculos->prepend('Ninguna', null)])->render();
    }

    public function crearBaseGravable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'linea' => 'nullable|integer|exists:vehiculo_linea,id',
            'modelo' => 'required|numeric',
            'vigencia' => 'required|numeric',
            'avaluo' => 'required|numeric',
            'clase' => 'nullable|integer|exists:vehiculo_clase,id',
            'carroceria' => 'nullable|integer|exists:vehiculo_carroceria,id',
            'grupo' => 'nullable|string|max:2|min:2',
            'tonelaje' => 'nullable|numeric',
            'pasajeros' => 'nullable|numeric'
        ], [
            'linea.integer' => 'El ID de la línea especificada no tiene un formato válido.',
            'linea.exists' => 'La línea especificada no existe en el sistema.',
            'modelo.required' => 'No se ha especificado el modelo.',
            'modelo.numeric' => 'El modelo especificado no tiene un formato válido.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.numeric' => 'La vigencia especificada no tiene un formato válido.',
            'avaluo.required' => 'No se ha especificado el avalúo.',
            'avaluo.numeric' => 'El avalúo especificado no tiene un formato válido.',
            'clase.integer' => 'El valor especificado para la carrocería del vehículo tiene un formato inválido.',
            'clase.exists' => 'La carrocería del vehículo especificada no existe en el sistema.',
            'carroceria.integer' => 'El valor especificado para la carrocería del vehículo no tienen un formato válido.',
            'carroceria.exists' => 'La carrocería del vehículo especificada no existe en el sistema.',
            'grupo.string' => 'El valor especificado pra el Grupo no tiene un formato válido.',
            'grupo.min' => 'La longitud de caracteres para el Grupo es in correcta: debe tener como mínimo :min caracteres.',
            'grupo.max' => 'La longitud de caracteres para el Grupo es in correcta: debe tener como máximo :min caracteres.',
            'tonelaje.numeric' => 'El valor especificado para el tonelaje no tiene un formato válido.',
            'pasajeros.numeric' => 'El valor especificado para Pasajeros no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } 
        
        if($request->linea != null && $request->marca == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha especificado la marca del vehículo a la que pertenece la línea especificada.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        if($request->carroceria != null && $request->clase == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha especificado la clase del vehículo a la que pertenece la carrocería especificada.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        
            try{
                vehiculo_liq_base_gravable::create([
                    'vehiculo_linea_id' => $request->linea,
                    'modelo' => $request->modelo,
                    'vigencia' => $request->vigencia,
                    'avaluo' => $request->avaluo,
                    'vehiculo_clase_id' => $request->clase,
                    'vehiculo_carroceria_id' => $request->carroceria,
                    'grupo' => $request->grupo,
                    'tonelaje' => $request->tonelaje,
                    'pasaje' => $request->pasajeros
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la base gravable correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se pudo crear la base gravable.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        
    }

    public function editarBaseGravable($id)
    {
        $vehiculosMarcas = vehiculo_marca::orderBy('name')->pluck('name','id');
        $baseGravable = vehiculo_liq_base_gravable::find($id);
        return view('admin.tramites.impuestos.editarBaseGravable', ['vehiculosMarcas' => $vehiculosMarcas,'baseGravable'=>$baseGravable])->render();
    }

    public function actualizarBaseGravable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liq_base_gravable,id',
            'linea' => 'required|integer|exists:vehiculo_linea,id',
            'modelo' => 'required|numeric',
            'vigencia' => 'required|numeric',
            'avaluo' => 'required|numeric',
            'clase' => 'nullable|integer|exists:vehiculo_clase,id',
            'carroceria' => 'nullable|integer|exists:vehiculo_carroceria,id',
            'grupo' => 'nullable|string|max:2|min:2',
            'tonelaje' => 'nullable|numeric',
            'pasajeros' => 'nullable|numeric'
        ], [
            'id.required' => 'No se ha especificado la base gravable a modificar.',
            'id.integer' => 'El ID de la base gravable a modificar no tiene un formato válido.',
            'id.exists' => 'La base gravable a modificar especificada no existe en el sistema.',
            'linea.required' => 'No se ha especificado la línea.',
            'linea.integer' => 'El ID de la línea especificada no tiene un formato válido.',
            'linea.exists' => 'La línea especificada no existe en el sistema.',
            'modelo.required' => 'No se ha especificado el modelo.',
            'modelo.numeric' => 'El modelo especificado no tiene un formato válido.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.numeric' => 'La vigencia especificada no tiene un formato válido.',
            'avaluo.required' => 'No se ha especificado el avalúo.',
            'avaluo.numeric' => 'El avalúo especificado no tiene un formato válido.',
            'clase.integer' => 'El valor especificado para la carrocería del vehículo tiene un formato inválido.',
            'clase.exists' => 'La carrocería del vehículo especificada no existe en el sistema.',
            'carroceria.integer' => 'El valor especificado para la carrocería del vehículo no tienen un formato válido.',
            'carroceria.exists' => 'La carrocería del vehículo especificada no existe en el sistema.',
            'grupo.string' => 'El valor especificado pra el Grupo no tiene un formato válido.',
            'grupo.min' => 'La longitud de caracteres para el Grupo es in correcta: debe tener como mínimo :min caracteres.',
            'grupo.max' => 'La longitud de caracteres para el Grupo es in correcta: debe tener como máximo :min caracteres.',
            'tonelaje.numeric' => 'El valor especificado para el tonelaje no tiene un formato válido.',
            'pasajeros.numeric' => 'El valor especificado para Pasajeros no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $baseGravable = vehiculo_liq_base_gravable::find($request->id);
                $baseGravable->vehiculo_linea_id = $request->linea;
                $baseGravable->modelo = $request->modelo;
                $baseGravable->vigencia = $request->vigencia;
                $baseGravable->avaluo = $request->avaluo;
                $baseGravable->vehiculo_clase_id = $request->clase;
                $baseGravable->vehiculo_carroceria_id = $request->carroceria;
                $baseGravable->grupo = $request->grupo;
                $baseGravable->tonelaje = $request->tonelaje;
                $baseGravable->pasaje = $request->pasajeros;
                $baseGravable->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la base gravable.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se pudo actualizar la base gravable.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function obtenerDescuentos()
    {
        $descuentos = vehiculo_liquidacion_descuento::paginate(25);
        return view('admin.tramites.impuestos.listadoDescuentos', ['descuentos'=>$descuentos])->render();
    }

    public function nuevoDescuento()
    {
        $vigencias = vehiculo_liquidacion_vigencia::orderBy('vigencia')->pluck('vigencia','id');
        return view('admin.tramites.impuestos.nuevoDescuento',['vigencias'=>$vigencias])->render();
    }

    public function crearDescuento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => 'required|integer|exists:vehiculo_liquidacion_vigencia,id',
            'concepto' => 'required|string',
            'porcentaje' => 'required|numeric',
            'vigente_desde_submit' => 'required|date',
            'vigente_hasta_submit' => 'required|date'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificada no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en el sistema.',
            'concepto.required' => 'No se ha especificado el concepto.',
            'concepto.string' => 'El concepto especificado no tiene un formato válido.',
            'porcentaje.required' => 'No se ha especificado el porcentaje de descuento.',
            'porcentaje.numeric' => 'El porcentaje de descuento especificado no tiene un formato válido.',
            'vigente_desde_submit.required' => 'No se ha especificado la fecha de incio del descuento.',
            'vigente_desde_submit.date' => 'La fecha de incio especificada no tiene un formato válido.',
            'vigente_hasta_submit.required' => 'No se ha especificado la fecha de finalización del descuento.',
            'vigente_hasta_submit.date' => 'La fecha de finalización especificada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                if($request->vigente_desde > $request->vigente_hasta){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El valor del campo "Vigente desde" debe ser menor al valor del campo "Vigente hasta".'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }
                vehiculo_liquidacion_descuento::create([
                    'concepto' => $request->concepto,
                    'porcentaje' => $request->porcentaje,
                    've_li_vi_id' => $request->vigencia,
                    'vigente_desde' => $request->vigente_desde_submit,
                    'vigente_hasta' => $request->vigente_hasta_submit,
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => '',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podidor crear el descuento.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarDescuento($id)
    {
        $vigencias = vehiculo_liquidacion_vigencia::orderBy('vigencia')->pluck('vigencia','id');
        $descuento = vehiculo_liquidacion_descuento::find($id);
        return view('admin.tramites.impuestos.editarDescuento',['vigencias'=>$vigencias,'descuento'=>$descuento])->render();
    }

    public function actualizarDescuento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liquidacion_descuento,id',
            'vigencia' => 'required|integer|exists:vehiculo_liquidacion_vigencia,id',
            'concepto' => 'required|string',
            'porcentaje' => 'required|numeric',
            'vigente_desde_submit' => 'required|date',
            'vigente_hasta_submit' => 'required|date'
        ], [
            'id.required' => 'No se ha especificado el descuento a modificar.',
            'id.integer' => 'El ID del descuento a modificar no tiene un formato válido.',
            'id.exists' => 'El descuento especificado no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificada no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en el sistema.',
            'concepto.required' => 'No se ha especificado el concepto.',
            'concepto.string' => 'El concepto especificado no tiene un formato válido.',
            'porcentaje.required' => 'No se ha especificado el porcentaje de descuento.',
            'porcentaje.numeric' => 'El porcentaje de descuento especificado no tiene un formato válido.',
            'vigente_desde_submit.required' => 'No se ha especificado la fecha de incio del descuento.',
            'vigente_desde_submit.date' => 'La fecha de incio especificada no tiene un formato válido.',
            'vigente_hasta_submit.required' => 'No se ha especificado la fecha de finalización del descuento.',
            'vigente_hasta_submit.date' => 'La fecha de finalización especificada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                if($request->vigente_desde > $request->vigente_hasta){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El valor del campo "Vigente desde" debe ser menor al valor del campo "Vigente hasta".'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }
                $descuento = vehiculo_liquidacion_descuento::find($request->id);
                $descuento->concepto = $request->concepto;
                $descuento->porcentaje = $request->porcentaje;
                $descuento->vigente_desde = $request->vigente_desde_submit;
                $descuento->vigente_hasta = $request->vigente_hasta_submit;
                $descuento->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el descuento correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el descuento.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function imprimirLiquidacion($id)
    {
        $liquidacion = vehiculo_liquidacion::find($id);
        $pdf = \PDF::loadView('admin.tramites.impuestos.imprimirLiquidacion',['liquidacion'=>$liquidacion])->setPaper('letter')->setOption('enable-smart-shrinking', true)->setOption('no-outline', true);
        return $pdf->inline();
    }

    public function importarBasesGravablesF1()
    {
        return view('admin.tramites.impuestos.importarBasesGravables')->render();
    }

    public function importarBasesGravablesF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel|required',
        ], [
            'required' => 'No se ha suministrado un archivo de registros.',
            'mimetypes' => 'El archivo de importación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            \DB::beginTransaction();
            $ruta_archivo = 'basesGravablesImportadas-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
            \Storage::disk('imports')->putFileAs('liquidaciones', $request->file('archivo'), $ruta_archivo);
            /*Excel::load(storage_path('app/imports/liquidaciones/'.$ruta_archivo), function($reader) {
                foreach ($reader->all() as $fila){
                    $marca = vehiculo_marca::firstOrCreate([
                        'name' => strtoupper($fila->marca)
                    ]);
                    $linea = vehiculo_linea::firstOrCreate([
                        'nombre' => strtoupper($fila->linea),
                        'cilindraje' => $fila->cilindraje,
                        'vehiculo_marca_id' => $marca->id
                    ]);
                    vehiculo_liq_base_gravable::create([
                        'vehiculo_linea_id' => $linea->id,
                        'modelo' => $fila->modelo,
                        'vigencia' => $fila->vigencia,
                        'avaluo' => $fila->avaluo
                    ]);
                }
            })->get();*/
            $headings = (new HeadingRowImport)->toArray($ruta_archivo);
            dd($headings);
            Excel::import(new VehiculoBaseGravable, $ruta_archivo);
            \DB::commit();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha realizado la importación correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en la operación. Por favor revise que el archivo cumpla con las especificaciones dadas.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function registrarPagoF1($id)
    {
        return view('admin.tramites.impuestos.registrarPago',['id'=>$id])->render();
    }

    public function registrarPagoF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liquidacion,id',
            'numero_consignacion' => 'required|numeric',
            'valor_consignacion' => 'required|numeric',
            'archivo_consignacion' => 'required',
        ], [
            'id.required' => 'No se ha especificado la liquidación a pagar.',
            'id.integer' => 'El ID de la liquidación a pagar no tiene un formato válido.',
            'id.exists' => 'La liquidación a pagar no existe en el sistema.',
            'numero_consignacion.required' => 'No se ha especificado el número de la consignación.',
            'numero_consignacion.numeric' => 'El número de consignación especificado no tiene un fromato válido.',
            'valor_consignacion.required' => 'No se ha especificado el valor de la consignación.',
            'valor_consignacion.numeric' => 'El valor de la consignación especificada no tiene un formato válido.',
            'archivo_consignacion.required' => 'No se ha proporcionado la consignación digitalizada.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $liquidacion = vehiculo_liquidacion::find($request->id);

            if($liquidacion->valor_total > $request->valor_consignacion){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El valor de la consignación es inferior al valor total de la liquidación a pagar.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }

            $pago = vehiculo_liquidacion_pago::create([
                'vehiculo_liquidacion_id' => $request->id,
                'numero_consignacion' => $request->numero_consignacion,
                'valor_consignacion' => $request->valor_consignacion
            ]);

            $pago->consignacion = \Storage::disk('liquidacionesVehiculos')->putFile($request->id, $request->file('archivo_consignacion'));
            $pago->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha registrado el pago correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en la operación. Por favor revise nuevamente la información.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function verPago($id)
    {
        $pago = vehiculo_liquidacion_pago::find($id);
        return view('admin.tramites.impuestos.verPago',['pago'=>$pago])->render();
    }

    public function verConsignacion($id)
    {
        $pago = vehiculo_liquidacion_pago::find($id);

        $filePath = $pago->consignacion;

        // file not found
        if( ! \Storage::disk('liquidacionesVehiculos')->exists($filePath) ) {
            abort(404);
        }

        $pdfContent = \Storage::disk('liquidacionesVehiculos')->get($filePath);

        // for pdf, it will be 'application/pdf'
        $type       = \Storage::disk('liquidacionesVehiculos')->mimeType($filePath);
        $fileName   = explode('/', $pago->consignacion);

        return \Response::make($pdfContent, 200, [
            'Content-Type'        => $type,
            'Content-Disposition' => 'inline; filename="'.array_last($fileName).'"'
        ]);
    }

    public function editarPagoF1($id)
    {
        $pago = vehiculo_liquidacion_pago::find($id);
        return view('admin.tramites.impuestos.editarPago',['pago'=>$pago])->render();
    }

    public function editarPagoF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liquidacion_pago,id',
            'numero_consignacion' => 'required|numeric',
            'valor_consignacion' => 'required|numeric',

        ], [
            'id.required' => 'No se ha especificado el pago a modificar.',
            'id.integer' => 'El ID del pago a modificar no tiene un formato válido.',
            'id.exists' => 'El pago especificado no existe en el sistema.',
            'numero_consignacion.required' => 'No se ha especificado el número de la consignación.',
            'numero_consignacion.numeric' => 'El número de consignación especificado no tiene un fromato válido.',
            'valor_consignacion.required' => 'No se ha especificado el valor de la consignación.',
            'valor_consignacion.numeric' => 'El valor de la consignación especificada no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $pago = vehiculo_liquidacion_pago::find($request->id);
            if($pago->hasLiquidacion->valor_total > $request->valor_consignacion){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El valor de la consignación es inferior al valor total de la liquidación a pagar.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }

            $pago->numero_consignacion = $request->numero_consignacion;
            $pago->valor_consignacion = $request->valor_consignacion;
            $pago->save();

            if($request->archivo_consignacion != null){
                $pago->consignacion = \Storage::disk('liquidacionesVehiculos')->putFile($request->id, $request->file('archivo_consignacion'));
                $pago->save();
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha modificado el pago correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en la operación. Por favor revise nuevamente la información.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function reCalcularLiquidacionF1($id)
    {
        $vigencias = vehiculo_liquidacion_vigencia::orderBy('vigencia')->pluck('vigencia','id');
        $liquidacion = vehiculo_liquidacion::find($id);
        return view('admin.tramites.impuestos.reCalcularLiquidacion', ['vigencias'=>$vigencias,'liquidacion'=>$liquidacion])->render();
    }

    public function reCalcularLiquidacionF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_liquidacion,id'
        ], [
            'id.required' => 'No se ha especificado la liquidación a recalcular.',
            'id.integer' => 'El ID de la liquidación especificada no tiene un formato válido.',
            'id.exists' => 'La liquidación especificada no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $liquidacion = vehiculo_liquidacion::find($request->id);
                if($liquidacion->hasPago != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La liquidación a re-calcular ya presenta un pago.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
                $consultaLiquidacion = $this->calcularValor($liquidacion->vehiculo_id, $liquidacion->vehiculo_liq_vig_id);
                $liquidacion->valor_total = $consultaLiquidacion['valor_total'];
                $liquidacion->valor_mora_total = $consultaLiquidacion['intereses'];
                $liquidacion->valor_descuento_total = $consultaLiquidacion['descuentos'];
                $liquidacion->valor_impuesto = $consultaLiquidacion['impuesto'];
                $liquidacion->valor_avaluo = $consultaLiquidacion['avaluo'];
                $liquidacion->derechos_entidad = $consultaLiquidacion['derechos_entidad'];
                $liquidacion->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha re-calculado la liquidación.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido re-calcular la liquidación.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }
    
    public function importarRegistros(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'vigencias' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel',
            'avaluos' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel',
            'vehiculos' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel',
            'propietarios' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel',
            'pagos' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel',
        ], [
            'vigencias.mimetypes' => 'El archivo de registros de vigencias no tiene un formato válido.',
            'avaluos.mimetypes' => 'El archivo de registros de avaluos no tiene un formato válido.',
            'vehiculos.mimetypes' => 'El archivo de registros de vehiculos no tiene un formato válido.',
            'propietarios.mimetypes' => 'El archivo de registros de propietarios no tiene un formato válido.',
            'pagos.mimetypes' => 'El archivo de registros de pagos no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }*/

        $vigencias = null;
        $avaluos = null;
        $vehiculos = null;
        $propietarios = null;
        $pagos = null;
        
        if($request->file('vigencias') != null){
            $vigencias = $this->importarVigencias($request->file('vigencias'));
        }

        if($request->file('avaluos') != null){
            $avaluos = $this->importarAvaluos($request->file('avaluos'));
        }

        if($request->file('vehiculos') != null){
            $vehiculos = $this->importarVehiculos($request->file('vehiculos'));
        }

        if($request->file('propietarios') != null){
            $propietarios = $this->importarPropietarios($request->file('propietarios'));
        }

        if($request->file('pagos') != null){
            $pagos = $this->importarPagos($request->file('pagos'));
        }

        $resultado = '<div>El resultado para la importación de vigencias fue:<br>';
        if($vigencias == null){
            $resultado .= 'No se especificó documento.';
        }elseif($vigencias == false){
            $resultado .= 'Hubo errores. No se importó ningún registro.';
        }else{
            $resultado .= 'Se importaron los registros exitosamente.';
        }

        $resultado .= '<br><br>El resultado para la importación de avalúos fue:<br>';
        if($avaluos == null){
            $resultado .= 'No se especificó documento.';
        }elseif($avaluos == false){
            $resultado .= 'Hubo errores. No se importó ningún registro.';
        }else{
            $resultado .= 'Se importaron los registros exitosamente.';
        }

        $resultado .= '<br><br>El resultado para la importación de vehículos fue:<br>';
        if($vehiculos == null){
            $resultado = $resultado.'No se especificó documento.';
        }elseif($vehiculos == false){
            $resultado = $resultado.'Hubo errores. No se importó ningún registro.';
        }else{
            $resultado = $resultado.'Se importaron los registros exitosamente.';
        }

        $resultado .= '<br><br>El resultado para la importación de propietarios fue:<br>';
        if($propietarios == null){
            $resultado .= 'No se especificó documento.';
        }elseif($propietarios == false){
            $resultado .= 'Hubo errores. No se importó ningún registro.';
        }else{
            $resultado .= 'Se importaron los registros exitosamente.';
        }

        $resultado .= '<br><br>El resultado para la importación de pagos fue:<br>';
        if($pagos == null){
            $resultado .= 'No se especificó documento.';
        }elseif($pagos == false){
            $resultado .= 'Hubo errores. No se importó ningún registro.';
        }else{
            $resultado .= 'Se importaron los registros exitosamente.';
        }

        $resultado .= '</div>';

        return response()->view('admin.mensajes.default', ['mensaje'=>$resultado, 'encabezado'=>'Resultado']);
    }
    
    private function importarVigencias($vigencias)
    {
        $ruta_archivo = 'vigencias-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
        Storage::disk('imports')->putFileAs('liquidaciones/vehiculos', $vigencias, $ruta_archivo);
        $success = false;
        try{
            \DB::beginTransaction();
            Excel::import(new \App\Imports\VehiculoLiquidacionVigenciaImport, storage_path('app/imports/liquidaciones/vehiculos/'.$ruta_archivo));
            \DB::commit();
            $success = true;
        }catch (\Exception $e){            
            \DB::rollBack();            
        }
        return $success;
    }
    
    private function importarAvaluos($avaluos)
    {
        $success = false;
        try{
            \DB::beginTransaction();
            $ruta_archivo = 'basesGravablesImportadas-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xlsx';
            \Storage::disk('imports')->putFileAs('basesGravablesImportadas', $avaluos, $ruta_archivo);
            \anlutro\LaravelSettings\Facade::set('importHeaders', (new HeadingRowImport)->toArray(storage_path('app/imports/basesGravablesImportadas/'.$ruta_archivo)));
            Excel::import(new VehiculoBaseGravable, storage_path('app/imports/basesGravablesImportadas/'.$ruta_archivo));
            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
            dd($e);
        }
        return $success; 
    }
    
    private function importarVehiculos($vehiculos)
    {
        $ruta_archivo = 'vehiculos-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
        Storage::disk('imports')->putFileAs('liquidaciones/vehiculos', $vehiculos, $ruta_archivo);
        $success = false;
        try{
            \DB::beginTransaction();
            Excel::import(new \App\Imports\VehiculoImport, storage_path('app/imports/liquidaciones/vehiculos/'.$ruta_archivo));
            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
        }
        return $success;       
    }
    
    private function importarPropietarios($propietarios)
    {
        $ruta_archivo = 'propietaros-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
        Storage::disk('imports')->putFileAs('liquidaciones/vehiculos', $propietarios, $ruta_archivo);
        $success = false;
        try{
            \DB::beginTransaction();
            Excel::import(new \App\Imports\VehiculoPropietarioImport, storage_path('app/imports/liquidaciones/vehiculos/'.$ruta_archivo));
            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
        }
        return $success;    
    }
    
    private function importarPagos($pagos)
    {
        $ruta_archivo = 'pagos-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
        Storage::disk('imports')->putFileAs('liquidaciones/vehiculos', $pagos, $ruta_archivo);
        $success = false;
        try{
            \DB::beginTransaction();
            Excel::import(new \App\Imports\VehiculoLiquidacionPagoImport, storage_path('app/imports/liquidaciones/vehiculos/'.$ruta_archivo));
            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
        }
        return $success;    
    }

    public function obtenerClasesGrupos()
    {
        $clasesGrupos = vehiculo_clase_grupo::all();
        return view( 'admin.tramites.impuestos.listadoGruposClases', ['clasesGrupos'=>$clasesGrupos])->render();
    }

    public function nuevaClaseGrupo()
    {
        $clasesVehiculo = vehiculo_clase::orderBy('name')->pluck('name','id');    
        $marcasVehiculos = vehiculo_marca::orderBy('name')->pluck('name','id'); 
        return view('admin.tramites.impuestos.nuevoGrupoClase', ['clasesVehiculo'=>$clasesVehiculo, 'marcasVehiculo'=>$marcasVehiculos])->render();
    }

    public function crearClaseGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clase_vehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'marca_vehiculo' => 'required|integer|exists:vehiculo_marca,id',
            'nombre' => 'required|string|unique:vehiculo_clase_grupo,name',
            'vigencia' => 'required|numeric'
        ], [
            'clase_vehiculo.required' => 'No se ha especificado la clase de vehículo.',
            'clase_vehiculo.integer' => 'El valor para la clase de vehículo tiene un formato inválido.',
            'clase_vehiculo.exists' => 'La clase de vehículo especificada no existe en el sistema.',
            'marca_vehiculo.required' => 'No se ha especificado la marca de vehículo.',
            'marca_vehiculo.integer' => 'El valor para la marca de vehículo tiene un formato inválido.',
            'marca_vehiculo.exists' => 'La marca de vehículo especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            vehiculo_clase_grupo::create([
                'vigencia' => $request->vigencia,
                'name' => strtoupper($request->nombre),
                'vehiculo_clase_id' => $request->clase_vehiculo,
                'vehiculo_marca_id' => $request->marca_vehiculo
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el grupo de clase.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el grupo de clase.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function editarClaseGrupo($id)
    {
        $claseGrupo = vehiculo_clase_grupo::find($id);
        $clasesVehiculo = vehiculo_clase::orderBy('name')->pluck('name','id');    
        $marcasVehiculos = vehiculo_marca::orderBy('name')->pluck('name','id'); 
        return view('admin.tramites.impuestos.editarGrupoClase', ['clasesVehiculo'=>$clasesVehiculo, 'marcasVehiculo'=>$marcasVehiculos, 'claseGrupo'=>$claseGrupo])->render();
    }

    public function actualizarClaseGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_clase_grupo',
            'clase_vehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'marca_vehiculo' => 'required|integer|exists:vehiculo_marca,id',
            'nombre' => 'required|string|unique:vehiculo_clase_grupo,name',
            'vigencia' => 'required|numeric'
        ], [
            'id.required' => 'No se ha especificado el grupo de clase a modificar.',
            'id.integer' => 'El grupo de clase especificado no tiene un formato válido.',
            'id.exists' => 'El grupo de clase especificado no existe en el sistema.',
            'clase_vehiculo.required' => 'No se ha especificado la clase de vehículo.',
            'clase_vehiculo.integer' => 'El valor para la clase de vehículo tiene un formato inválido.',
            'clase_vehiculo.exists' => 'La clase de vehículo especificada no existe en el sistema.',
            'marca_vehiculo.required' => 'No se ha especificado la marca de vehículo.',
            'marca_vehiculo.integer' => 'El valor para la marca de vehículo tiene un formato inválido.',
            'marca_vehiculo.exists' => 'La marca de vehículo especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $claseGrupo = vehiculo_clase_grupo::find($request->id);
            $claseGrupo->vigencia = $request->vigencia;
            $claseGrupo->name = strtoupper($request->nombre);
            $claseGrupo->vehiculo_clase_id = $request->clase_vehiculo;
            $claseGrupo->vehiculo_marca_id = $request->marca_vehiculo;
            $claseGrupo->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el grupo de clase.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el grupo de clase.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function obtenerCilindrajesGrupos()
    {
        $cilindrajesGrupos = vehiculo_cilindraje_grupo::all();
        return view('admin.tramites.impuestos.listadoGruposCilindrajes', ['cilindrajesGrupos'=>$cilindrajesGrupos])->render();
    }

    public function nuevoCilindrajeGrupo()
    {
        $clasesVehiculo = vehiculo_clase::orderBy('name')->pluck('name','id');    
        return view( 'admin.tramites.impuestos.nuevoGrupoCilindraje', ['clasesVehiculo'=>$clasesVehiculo])->render();
    }

    public function crearCilindrajeGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clase_vehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'nombre' => 'required|string|unique:vehiculo_cilindraje_grupo,name',
            'vigencia' => 'required|numeric',
            'desde' => 'required|numeric',
            'hasta' => 'required|numeric'
        ], [
            'clase_vehiculo.required' => 'No se ha especificado la clase de vehículo.',
            'clase_vehiculo.integer' => 'El valor para la clase de vehículo tiene un formato inválido.',
            'clase_vehiculo.exists' => 'La clase de vehículo especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.',
            'desde.required' => 'No se ha especificado el parámetro desde.',
            'desde.numeric' => 'El valor especificado para el parámetro desde no tiene un formato válido.',
            'hasta.required' => 'No se ha especificado el parámetro hasta.',
            'hasta.numeric' => 'El valor especificado para el parámetro hasta no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        if($request->desde > $request->hasta){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El parámetro Desde no puede ser mayor al parámetro Hasta.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }

        try{
            vehiculo_cilindraje_grupo::create([
                'vigencia' => $request->vigencia,
                'name' => strtoupper($request->nombre),
                'vehiculo_clase_id' => $request->clase_vehiculo,
                'desde' => $request->desde,
                'hasta' => $request->hasta
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el grupo de cilindraje.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el grupo de cilindraje.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function editarCilindrajeGrupo($id)
    {
        $cilindrajeGrupo = vehiculo_cilindraje_grupo::find($id);
        $clasesVehiculo = vehiculo_clase::orderBy('name')->pluck('name','id');    
        return view( 'admin.tramites.impuestos.editarGrupoCilindraje', ['clasesVehiculo'=>$clasesVehiculo, 'cilindrajeGrupo'=>$cilindrajeGrupo])->render();
    }

    public function actualizarCilindrajeGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_cilindraje_grupo,id',
            'clase_vehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'nombre' => 'required|string|unique:vehiculo_cilindraje_grupo,name',
            'vigencia' => 'required|numeric',
            'desde' => 'required|numeric',
            'hasta' => 'required|numeric'
        ], [
            'id.required' => 'No se ha especificado el grupo cilindraje a modificar.',
            'id.integer' => 'El valor del grupo cilindraje a modificar no tiene un formato válido.',
            'id.exists' => 'El grupo cilindraje a modificar no existe en el sistema.',
            'clase_vehiculo.required' => 'No se ha especificado la clase de vehículo.',
            'clase_vehiculo.integer' => 'El valor para la clase de vehículo tiene un formato inválido.',
            'clase_vehiculo.exists' => 'La clase de vehículo especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.',
            'desde.required' => 'No se ha especificado el parámetro desde.',
            'desde.numeric' => 'El valor especificado para el parámetro desde no tiene un formato válido.',
            'hasta.required' => 'No se ha especificado el parámetro hasta.',
            'hasta.numeric' => 'El valor especificado para el parámetro hasta no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        if($request->desde > $request->hasta){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El parámetro Desde no puede ser mayor al parámetro Hasta.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }

        try{
            $cilindrajeGrupo = vehiculo_cilindraje_grupo::find($request->id);
            $cilindrajeGrupo->vigencia = $request->vigencia;
            $cilindrajeGrupo->name = strtoupper($request->nombre);
            $cilindrajeGrupo->vehiculo_clase_id = $request->clase_vehiculo;
            $cilindrajeGrupo->desde = $request->desde;
            $cilindrajeGrupo->hasta = $request->hasta;
            $cilindrajeGrupo->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el grupo de cilindraje.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el grupo de cilindraje.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function obtenerBateriasGrupos()
    {
        $bateriasGrupos = vehiculo_bateria_grupo::all();
        return view('admin.tramites.impuestos.listadoGruposBaterias', ['bateriasGrupos'=>$bateriasGrupos])->render();
    }

    public function nuevaBateriaGrupo()
    {
        $tiposBateria = vehiculo_bateria_tipo::orderBy('name')->pluck('name','id');    
        return view( 'admin.tramites.impuestos.nuevoGrupoBateria', ['tiposBateria'=>$tiposBateria])->render();
    }

    public function crearBateriaGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bateria_tipo' => 'required|integer|exists:vehiculo_bateria_tipo,id',
            'nombre' => 'required|string|unique:vehiculo_bateria_grupo,name',
            'vigencia' => 'required|numeric',
            'desde' => 'required|numeric',
            'hasta' => 'required|numeric'
        ], [
            'bateria_tipo.required' => 'No se ha especificado el tipo de batería.',
            'bateria_tipo.integer' => 'El valor para el tipo de batería tiene un formato inválido.',
            'bateria_tipo.exists' => 'El tipo de batería especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.',
            'desde.required' => 'No se ha especificado el parámetro desde.',
            'desde.numeric' => 'El valor especificado para el parámetro desde no tiene un formato válido.',
            'hasta.required' => 'No se ha especificado el parámetro hasta.',
            'hasta.numeric' => 'El valor especificado para el parámetro hasta no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        if($request->desde > $request->hasta){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El parámetro Desde no puede ser mayor al parámetro Hasta.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }

        try{
            vehiculo_bateria_grupo::create([
                'vigencia' => $request->vigencia,
                'name' => strtoupper($request->nombre),
                'vehiculo_bateria_tipo_id' => $request->bateria_tipo,
                'desde' => $request->desde,
                'hasta' => $request->hasta
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el grupo de bateria.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el grupo de bateria.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function editarBateriaGrupo($id)
    {
        $bateriaGrupo = vehiculo_bateria_grupo::find($id);
        $tiposBateria = vehiculo_bateria_tipo::orderBy('name')->pluck('name','id');    
        return view( 'admin.tramites.impuestos.editarGrupoBateria', ['tiposBateria'=>$tiposBateria, 'bateriaGrupo'=>$bateriaGrupo])->render();
    }

    public function actualizarBateriaGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_bateria_grupo,id',
            'bateria_tipo' => 'required|integer|exists:vehiculo_bateria_tipo,id',
            'nombre' => 'required|string|unique:vehiculo_bateria_grupo,name',
            'vigencia' => 'required|numeric',
            'desde' => 'required|numeric',
            'hasta' => 'required|numeric'
        ], [
            'id.required' => 'No se ha especificado el grupo bateria a modificar.',
            'id.integer' => 'El valor del grupo bateria a modificar no tiene un formato válido.',
            'id.exists' => 'El grupo bateria a modificar no existe en el sistema.',
            'bateria_tipo.required' => 'No se ha especificado el tipo de batería.',
            'bateria_tipo.integer' => 'El valor para el tipo de batería tiene un formato inválido.',
            'bateria_tipo.exists' => 'El tipo de batería especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del grupo.',
            'nombre.string' => 'El nombre del grupo especificado tiene un formato inválido.',
            'nombre.unique' => 'El nombre especificado ya existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que corresponde el grupo.',
            'vigencia.numeric' => 'El valor especificado para la vigencia del grupo no tiene un formato válido.',
            'desde.required' => 'No se ha especificado el parámetro desde.',
            'desde.numeric' => 'El valor especificado para el parámetro desde no tiene un formato válido.',
            'hasta.required' => 'No se ha especificado el parámetro hasta.',
            'hasta.numeric' => 'El valor especificado para el parámetro hasta no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        if($request->desde > $request->hasta){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El parámetro Desde no puede ser mayor al parámetro Hasta.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }

        try{
            $bateriaGrupo = vehiculo_bateria_grupo::find($request->id);
            $bateriaGrupo->vigencia = $request->vigencia;
            $bateriaGrupo->name = strtoupper($request->nombre);
            $bateriaGrupo->vehiculo_bateria_tipo_id = $request->bateria_tipo;
            $bateriaGrupo->desde = $request->desde;
            $bateriaGrupo->hasta = $request->hasta;
            $bateriaGrupo->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el grupo de bateria.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el grupo de bateria.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }
}
