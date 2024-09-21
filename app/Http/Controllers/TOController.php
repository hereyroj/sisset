<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Mail\actualizacionTO;
use App\Mail\creacionTO;
use App\Mail\notificarTOAnulada;
use App\Providers\AppServiceProvider;
use App\to_file_history;
use App\vehiculo;
use App\vehiculo_clase;
use App\empresa_transporte;
use App\sistema_parametros_to;
use App\vehiculo_marca;
use App\vehiculo_nivel_servicio;
use App\vehiculo_radio_operacion;
use App\vehiculo_carroceria;
use App\tarjeta_operacion;
use App\vehiculo_combustible;
use Validator;
use Illuminate\Validation\Rule;
use PDF;
use Illuminate\Http\Response;

class TOController extends Controller
{
    public function administrar()
    {
        return view('admin.tramites.to.administrar');
    }

    public function verificarVigencia($placa)
    {
        $to = tarjeta_operacion::where('placa', strtoupper($placa))->orderBy('created_at', 'desc')->first();
        $vehiculo = vehiculo::where('placa', $placa)->first();
        if($vehiculo == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No existe un vehículo registrado en el sistema con la placa especificada: '.strtoupper($placa).'. Por favor realice el registro del automotor en el módulo correspondiente para que pueda registrar una tarjeta de operación.'],
                'encabezado' => 'Sin registro:',
            ], 200);
        } elseif ($vehiculo->hasEmpresaActiva() == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El vehículo de placa '.strtoupper($placa). ' no está vinculado a una empresa de transporte. Es requisito obligatorio tener una vinculación.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } elseif ($to === '' || $to === null) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'La placa '.strtoupper($placa).' no tiene una tarjeta vigente o está próxima a expirar.',
                'encabezado' => 'Disponible',
            ], 200);
        } else {
            if ($to->fecha_vencimiento > Carbon::now()->addMonths(1)->toDateString()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La placa '.$placa.' tiene una Tarjeta vigente con fecha de vencimiento el '.$to->fecha_vencimiento.'.'],
                    'encabezado' => 'Posible duplicado:',
                ], 200);
            }
        }
    }

    private function obtenerComplementos()
    {
        $tipoVehiculo = vehiculo_clase::orderBy('name', 'asc')->pluck('name', 'id');
        $empresasTransporte = empresa_transporte::orderBy('name', 'asc')->pluck('name', 'id');
        $marcaVehiculo = vehiculo_marca::orderBy('name', 'asc')->pluck('name', 'id');
        $nivelServicio = vehiculo_nivel_servicio::orderBy('name', 'asc')->pluck('name', 'id');
        $radioOperacion = vehiculo_radio_operacion::orderBy('name', 'asc')->pluck('name', 'id');
        $tipoCarroceria = vehiculo_carroceria::orderBy('name', 'asc')->pluck('name', 'id');
        $claseCombustible = vehiculo_combustible::orderBy('name', 'asc')->pluck('name', 'id');

        return [
            'tipoVehiculo' => $tipoVehiculo,
            'empresasTransporte' => $empresasTransporte,
            'marcaVehiculo' => $marcaVehiculo,
            'nivelServicio' => $nivelServicio,
            'radioOperacion' => $radioOperacion,
            'tipoCarroceria' => $tipoCarroceria,
            'claseCombustible' => $claseCombustible,
        ];
    }

    public function nuevaTO()
    {
        return view('admin.tramites.to.nuevaTo', $this->obtenerComplementos());
    }

    public function guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tarjeta_operacion,id',
            'fechaVencimiento_submit' => 'required|date',
            'duplicado' => 'integer|required',
            'actualizaVehiculo' => ['required', Rule::in(['NO', 'SI'])],
            'actualizaVinculacion' => ['required', Rule::in(['NO', 'SI'])]
        ], [
            'id.required' => 'No se ha especificado una tarjeta para actualización.',
            'id.integer' => 'El ID de la tarjeta especificada no tiene un formato válido.',
            'id.exists' => 'La tarjeta especificada no existe en la base de datos.',
            'fechaVencimiento_submit.required' => 'No se ha especificado una fecha de vencimiento.',
            'fechaVencimiento_submit.date' => 'La fecha de vencimiento especificada no tiene un formato válido.',
            'duplicado.integer' => 'No tiene un formato valido.',
            'duplicado.required' => 'Se debe especificar si la TO es original o duplicado.',
            'actualizaVehiculo.required' => 'No se ha especificado si se debe actualizar la información del vehículo en la base de datos.',
            'actualizaVehiculo.in' => 'El valor especificado para actualizar la información del vehículo no es válido.',
            'actualizaVinculacion.required' => 'No se ha especificado si se debe actualizar la información de vinculación del vehículo en la base de datos.',
            'actualizaVinculacion.in' => 'El valor especificado para actualizar la información de vinculación del vehículo no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $to = Tarjeta_operacion::find($request->id);
                $vehiculo = vehiculo::find($to->vehiculo_id);
                /* Valor básicos */
                $to->fecha_vencimiento = $request->fechaVencimiento_submit;
                $to->duplicado = $request->duplicado;
                /* Actualizaciones: vehículo */
                if($request->actualizaVehiculo === 'SI'){
                    $to->placa = $vehiculo->placa;
                    $to->tipo_vehiculo_id = $vehiculo->vehiculo_clase_id;
                    $to->tipo_carroceria_id = $vehiculo->vehiculo_carroceria_id;
                    $to->marca_vehiculo_id = $vehiculo->vehiculo_marca_id;
                    $to->modelo = $vehiculo->modelo;
                    $to->clase_combustible_id = $vehiculo->vehiculo_combustible_id;
                    $to->numero_motor = $vehiculo->numero_motor;
                    $to->capacidad_pasajeros = $vehiculo->capacidad_pasajeros;
                    $to->capacidad_toneladas = $vehiculo->capacidad_toneladas;
                }
                /* Actualizaciones: vinculación */
                if($request->actualizaVinculacion === 'SI'){
                    $to->empresa_transporte_id = $vehiculo->hasEmpresaActiva()->pivot->empresa_transporte_id;
                    $to->nivel_servicio_id = $vehiculo->hasEmpresaActiva()->pivot->nivel_servicio_id;
                    $to->numero_interno = $vehiculo->hasEmpresaActiva()->pivot->numero_interno;
                    $to->radio_operacion_id = $vehiculo->hasEmpresaActiva()->pivot->radio_operacion_id;
                }

                if ($to->save()) {
                    $empresa = empresa_transporte::find($to->empresa_transporte_id);
                    Mail::to($empresa)->send(new actualizacionTO($to->placa, $to->id, $to->updated_at, $empresa->name));

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha actualizado la tarjeta de operación.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido actualizar la tarjeta de operación.'],
                        'encabezado' => 'Errores en la creación:',
                    ], 200);
                }
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar la tarjeta de operación.'],
                    'encabezado' => 'Errores en la creación:',
                ], 200);
            }

        }
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fechaVencimiento_submit' => 'required|date',
            'placa' => 'required|max:8|string|min:6|exists:vehiculo,placa',
            'duplicado' => 'integer|required'
        ], [
            'fechaVencimiento_submit.required' => 'No se ha especificado una fecha de vencimiento.',
            'fechaVencimiento_submit.date' => 'La fecha de vencimiento especificada no tiene un formato válido.',
            'placa.required' => 'No se ha especificado una placa.',
            'placa.max' => 'La placa debe tener un máximo de :max caracteres.',
            'placa.min' => 'La placa debe tener un mínimo de :min caracteres.',
            'placa.exists' => 'La placa especificada no existe en la base de datos.',
            'duplicado.integer' => 'No tiene un formato valido.',
            'duplicado.required' => 'Se debe especificar si la TO es original o duplicado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = true;
            $vehiculo = vehiculo::where('placa', $request->placa)->first();
            if($vehiculo->hasEmpresaActiva() == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El vehículo de placa '.strtoupper($request->placa). ' no está vinculado a una empresa de transporte. Es requisito obligatorio tener una vinculación.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }else{
                \DB::beginTransaction();
                try{
                    if(tarjeta_operacion::all()->count() <= 0){
                        $parametrosTO = sistema_parametros_to::whereHas('hasVigencia', function($query){
                            $query->where('vigencia', \Setting::get('vigencia'));
                        })->first();
                        \DB::update('ALTER Table tarjeta_operacion AUTO_INCREMENT = '.$parametrosTO->consecutivo_inicial.';');
                    }

                    \DB::table('tarjeta_operacion')->where('vehiculo_id', $vehiculo->id)->where('anulada', null)->update(['anulada'=>'SI']);
                    $to = new tarjeta_operacion();                    
                    $to->fecha_vencimiento = $request->fechaVencimiento_submit;
                    $to->placa = $vehiculo->placa;
                    $to->tipo_vehiculo_id = $vehiculo->vehiculo_clase_id;
                    $to->tipo_carroceria_id = $vehiculo->vehiculo_carroceria_id;
                    $to->marca_vehiculo_id = $vehiculo->vehiculo_marca_id;
                    $to->modelo = $vehiculo->modelo;
                    $to->empresa_transporte_id = $vehiculo->hasEmpresaActiva()->pivot->empresa_transporte_id;
                    $to->clase_combustible_id = $vehiculo->vehiculo_combustible_id;
                    $to->numero_motor = $vehiculo->numero_motor;
                    $to->nivel_servicio_id = $vehiculo->hasEmpresaActiva()->pivot->nivel_servicio_id;
                    $to->capacidad_pasajeros = $vehiculo->capacidad_pasajeros;
                    $to->capacidad_toneladas = $vehiculo->capacidad_toneladas;
                    $to->numero_interno = $vehiculo->hasEmpresaActiva()->pivot->numero_interno;
                    $to->radio_operacion_id = $vehiculo->hasEmpresaActiva()->pivot->radio_operacion_id;
                    $to->sede = "";
                    $to->zona_operacion = "";
                    $to->duplicado = $request->duplicado;
                    $to->vehiculo_id = $vehiculo->id;
                    $to->save();
                    \DB::commit();
                }catch (\Exception $e){
                    \DB::rollBack();
                    $success = false;
                }

                if ($success == true) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado la tarjeta de operación.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear la tarjeta de operación.'],
                        'encabezado' => 'Errores en la creación:',
                    ], 200);
                }
            }
        }
    }

    public function obtenerTSO()
    {
        $tos = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->orderBy('id', 'desc')->paginate(25);

        return view('admin.tramites.to.listadoTSO', ['tos' => $tos])->render();
    }

    public function editar($id)
    {
        $to = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->find($id);
        $to->vehiculo = vehiculo::find($to->vehiculo_id);
        return view('admin.tramites.to.editar', array_add($this->obtenerComplementos(), 'to', $to))->render();
    }

    public function ver($id)
    {
        $to = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->find($id);

        return view('admin.tramites.to.ver', ['to' => $to])->render();
    }

    public function imprimir($param)
    {
        if ($param == null) {
            return back()->withErrors(['No se ha especificado parámetros de búsqueda.']);
        } else {
            /*
             * Realiza la consulta de acuerdo a la informacion dada. Esta consulta trae toda la informacion asociada de la TO
             */
            $to = tarjeta_operacion::with('hasManyFileHistory', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->where('placa', $param)->orWhere('id', $param)->get();
            /*
             * Comprobamos que se hayan encontrado resultados
             */
            if ($to == null) {
                return false;
            } elseif ($to[0]->hasManyFileHistory->count() > 0) {
                $fileHistory = $to[0]->hasManyFileHistory->last();
                if ($fileHistory->created_at == $to[0]->updated_at) {
                    $headers = [
                        'Content-Type: application/pdf',
                        'Content-Disposition: attachment; filename="'.$fileHistory->file_name.'"',
                    ];

                    return Response()->download(storage_path('app/tos/'.$to[0]->id.'/'.$fileHistory->file_name), $fileHistory->file_name, $headers);
                } else {
                    $fileHistory->status = 'old';
                    $fileHistory->save();
                    /*
                     * Configuramos las fechas para renderizarlas en la plantilla blade
                     */
                    $fecha = explode("-", $to[0]['fecha_vencimiento']);
                    /*
                     * Enviamos la TO a la vista y renderizamos el pdf
                     */
                    $pdf = PDF::loadView('admin.tramites.to.imprimir', [
                        'to' => $to,
                        'dia' => $fecha[2],
                        'mes' => $fecha[1],
                        'año' => $fecha[0],
                    ])->setOption('no-outline', true)->setOption('margin-bottom', 0)->setOption('margin-left', 1)->setOption('margin-right', 1)->setOption('margin-top', 1)->setOption('page-width', 85)->setOption('page-height', 54)->setOption('enable-smart-shrinking', true);

                    \Storage::disk('tos')->put($to[0]->id.'/tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf', $pdf->download('tarjeta_de_operacion.pdf'));

                    $fileHistory = new to_file_history();
                    $fileHistory->tarjeta_operacion_id = $to[0]->id;
                    $fileHistory->status = 'current';
                    $fileHistory->file_name = 'tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf';
                    $fileHistory->sha1 = sha1_file(storage_path('app/tos/'.$to[0]->id.'/tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf'));
                    $fileHistory->mime = 'application/pdf';
                    $fileHistory->created_at = $to[0]->updated_at;
                    $fileHistory->save();

                    $headers = [
                        'Content-Type: application/pdf',
                        'Content-Disposition: attachment; filename="'.$fileHistory->file_name.'"',
                    ];

                    return Response()->download(storage_path('app/tos/'.$to[0]->id.'/'.$fileHistory->file_name), $fileHistory->file_name, $headers);
                }
            } else {
                /*
                 * Configuramos las fechas para renderizarlas en la plantilla blade
                 */
                $fecha = explode("-", $to[0]['fecha_vencimiento']);
                /*
                 * Enviamos la TO a la vista y renderizamos el pdf
                 */
                $pdf = PDF::loadView('admin.tramites.to.imprimir', [
                    'to' => $to,
                    'dia' => $fecha[2],
                    'mes' => $fecha[1],
                    'año' => $fecha[0],
                ])->setOption('no-outline', true)->setOption('margin-bottom', 0)->setOption('margin-left', 1)->setOption('margin-right', 1)->setOption('margin-top', 1)->setOption('page-width', 85)->setOption('page-height', 54)->setOption('enable-smart-shrinking', true);

                if ($to[0]->hasManyFileHistory->count() > 0) {
                    $fileHistory = $to[0]->hasManyFileHistory->last();
                    $fileHistory->status = 'old';
                    $fileHistory->save();
                }

                \Storage::disk('tos')->put($to[0]->id.'/tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf', $pdf->download('tarjeta_de_operacion.pdf'));

                $fileHistory = new to_file_history();
                $fileHistory->tarjeta_operacion_id = $to[0]->id;
                $fileHistory->status = 'current';
                $fileHistory->file_name = 'tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf';
                $fileHistory->sha1 = sha1_file(storage_path('app/tos/'.$to[0]->id.'/tarjeta-de-operacion-'.$to[0]->id.'-'.$to[0]->updated_at->format('Y-m-d_H-i-s').'.pdf'));
                $fileHistory->mime = 'application/pdf';
                $fileHistory->created_at = $to[0]->updated_at;
                $fileHistory->save();

                $headers = [
                    'Content-Type: application/pdf',
                    'Content-Disposition: attachment; filename="'.$fileHistory->file_name.'"',
                ];

                return Response()->download(storage_path('app/tos/'.$to[0]->id.'/'.$fileHistory->file_name), $fileHistory->file_name, $headers);
            }
        }
    }

    public function filtrarBusqueda($parametro)
    {
        $parametro = strtoupper($parametro);
        $tos = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->where('placa', 'like', '%'.$parametro.'%')->orWhereHas('hasEmpresaTransporte', function (
                $query
            ) use ($parametro) {
                $query->where('name', 'like', '%'.$parametro.'%');
            })->orWhere('id', 'like', '%'.$parametro.'%')->paginate(25);

        return view('admin.tramites.to.listadoTSO', ['tos' => $tos, 'parametro' => $parametro])->render();
    }

    public function obtenerDatosVehiculo($placa)
    {
        $vehiculo = vehiculo::where('placa', $placa)->first();
        if($vehiculo != null){
            $vehiculo->hasEmpresaActiva = $vehiculo->hasEmpresaActiva();
            return $vehiculo->toJson();
        }else{
            return null;
        }
    }
}
