<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\archivo_carpeta;
use App\archivo_carpeta_estado;
use App\placa;
use App\Mail\NotificarPreAsignacion;
use App\Mail\NotificarPreAsignacionRechazada;
use App\solicitud_preasignacion;
use App\solicitud_rechazo_motivo;
use App\usuario_tipo_documento;
use App\vehiculo_clase;
use App\vehiculo_servicio;

class PreAsignacionesController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Número motor',
            '2' => 'Número chasis',
        ];
        $sFiltro = null;

        return view('admin.tramites.preasignaciones.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function obtenerPlacasParaPreAsignar($solicitud_id)
    {
        $solicitud_preasignacion = solicitud_preasignacion::find($solicitud_id);
        $placas_disponibles = null;
        if ($solicitud_preasignacion->hasPlacaActiva() == null) {
            $vehiculoServicio = vehiculo_servicio::find($solicitud_preasignacion->vehiculo_servicio_id);
            if($vehiculoServicio->placa_consecutivo == 'NO'){
                $placas_disponibles = placa::where('vehiculo_servicio_id', $solicitud_preasignacion->vehiculo_servicio_id)->get();
                $placas_disponibles = $placas_disponibles->filter(function ($item) use ($solicitud_preasignacion){
                    return $item->hasVehiculoClase($solicitud_preasignacion->vehiculo_clase_id) == true;
                });
                $placas_disponibles = $placas_disponibles->filter(function ($item) {
                    return $item->isAvailable() == true;
                });
            }
            return view('admin.tramites.preasignaciones.placasParaPreAsignar', [
                'solicitud' => $solicitud_preasignacion,
                'placas' => $placas_disponibles,
            ])->render();
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El tramite de pre-asignación especificado ya cuenta con una asignación.'],
                'encabezado' => 'Error en el proceso',
            ], 200);
        }
    }

    public function preAsignarPlaca(Request $request)
    {
        $success = false;
        $placa = null;
        \DB::beginTransaction();
        try{
            $solicitud_preasignacion = solicitud_preasignacion::with('hasVehiculoServicio')->find($request->solicitud_preasignacion_id);
            if ($solicitud_preasignacion->hasPlacaActiva() == null) {
                if($solicitud_preasignacion->hasVehiculoServicio->placa_consecutivo == 'SI' && $request->placa_id == 'NO'){
                    $placas_disponibles = placa::whereHas('hasVehiculosClases', function($query) use ($solicitud_preasignacion){
                        $query->vehiculo_clase_id = $solicitud_preasignacion->vehiculo_clase_id;
                    })->whereHas('hasVehiculoServicio', function($query) use ($solicitud_preasignacion) {
                        $query->vehiculo_servicio_id = $solicitud_preasignacion->vehiculo_servicio_id;
                    }
                    )->orderBy('name')->get();
                    $placas_disponibles = $placas_disponibles->filter(function ($item) {
                        return $item->isAvailable() == true;
                    });
                    $placa = $placas_disponibles->first();
                    if ($placa->isAvailable()) {
                        $placa->hasSolicitudesPreAsignaciones()->attach($request->solicitud_preasignacion_id, [
                            'fecha_preasignacion' => date('Y-m-d H:i:s'),
                            'fecha_liberacion' => null,
                            'fecha_matricula' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        \DB::commit();
                        $success = true;
                    } else {
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['La placa ya cuenta con una pre-asignación activa o ya se encuentra matriculada.'],
                            'encabezado' => 'Error en la pre-asignación',
                        ], 200);
                    }
                }elseif($solicitud_preasignacion->hasVehiculoServicio->placa_consecutivo == 'SI' && $request->placa_id == 'NO'){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Los parámetros de placa y servicio del automotor no coinciden con los del sistema.'],
                        'encabezado' => 'Error en la pre-asignación',
                    ], 200);
                }else{
                    $placa = placa::find($request->placa_id);
                    if ($placa->isAvailable()) {
                        $placa->hasSolicitudesPreAsignaciones()->attach($request->solicitud_preasignacion_id, [
                            'fecha_preasignacion' => date('Y-m-d H:i:s'),
                            'fecha_liberacion' => null,
                            'fecha_matricula' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        \DB::commit();
                        $success = true;
                    } else {
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['La placa ya cuenta con una pre-asignación activa o ya se encuentra matriculada.'],
                            'encabezado' => 'Error en la pre-asignación',
                        ], 200);
                    }
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El tramite de pre-asignación especificado ya cuenta con una asignación.'],
                    'encabezado' => 'Error en la pre-asignación',
                ], 200);
            }
        }catch (\Exception $e){
            \DB::rollBack();
        }
        if($success == true){
            \Mail::to($solicitud_preasignacion->correo_electronico_solicitante)->send(new NotificarPreAsignacion($placa, $solicitud_preasignacion));
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha pre-asignado la placa.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso. Por favor intente nuevamente y si el problema persiste, comunicarse con un administrador.'],
                'encabezado' => 'Error en la pre-asignación',
            ], 200);
        }
    }

    public function obtenerMotivosRechazo()
    {
        $motivos = solicitud_rechazo_motivo::withTrashed()->paginate(10);
        return view('admin.tramites.preasignaciones.listadoMotivosRechazo', ['motivos'=>$motivos])->render();
    }

    public function editarMotivoRechazo($id)
    {
        $motivo = solicitud_rechazo_motivo::find($id);
        return view('admin.tramites.preasignaciones.editarMotivoRechazo', ['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoRechazo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('solicitud_rechazo_motivo')->ignore($request->id)],
            'id' => 'required|integer|exists:solicitud_rechazo_motivo'
        ], [
            'name.required' => 'No se ha especificado el nombre del motivo rechazo.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'id.required' => 'No se ha especificado el motivo rechazo a actualizar.',
            'id.integer' => 'El ID del motivo rechazo a actualizar no tiene un formato válido.',
            'id.exists' => 'El motivo rechazo a actualizar especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $motivo = solicitud_rechazo_motivo::find($request->id);
            $motivo->name = strtoupper($request->name);
            if($motivo->save()){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el motivo rechazo correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso. Por favor intente nuevamente y si el problema persiste, comunicarse con un administrador.'],
                    'encabezado' => 'Error en el proceso.',
                ], 200);
            }
        }
    }

    public function eliminarMotivoRechazo($id)
    {
        $motivo = solicitud_rechazo_motivo::find($id);
        if($motivo->delete()){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el motivo rechazo.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso. Por favor intente nuevamente y si el problema persiste, comunicarse con un administrador.'],
                'encabezado' => 'Error en el proceso.',
            ], 200);
        }
    }

    public function restaurarMotivoRechazo($id)
    {
        $motivo = solicitud_rechazo_motivo::withTrashed()->find($id);
        if($motivo->restore()){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha restaurado el motivo rechazo.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso. Por favor intente nuevamente y si el problema persiste, comunicarse con un administrador.'],
                'encabezado' => 'Error en el proceso.',
            ], 200);
        }
    }

    public function nuevoMotivoRechazo()
    {
        return view('admin.tramites.preasignaciones.nuevoMotivoRechazo')->render();
    }

    public function crearMotivoRechazo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|unique:solicitud_rechazo_motivo'
        ], [
            'name.required' => 'No se ha especificado el nombre del motivo rechazo.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = null;
            try{
                \DB::beginTransaction();
                solicitud_rechazo_motivo::create([
                    'name' => strtoupper($request->name),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
                $success = false;
            }

            if($success){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el motivo rechazo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso. Por favor intente nuevamente y si el problema persiste, comunicarse con un administrador.'],
                    'encabezado' => 'Error en el proceso.',
                ], 200);
            }
        }
    }

    public function rechazarLaSolicitud($id)
    {
        $motivosRechazo = solicitud_rechazo_motivo::pluck('name', 'id');

        return view('admin.tramites.preasignaciones.rechazarSolicitud', [
            'solicitud_id' => $id,
            'motivosRechazo' => $motivosRechazo,
        ])->render();
    }

    public function rechazarSolicitud(Request $request)
    {
        $solicitud = solicitud_preasignacion::find($request->solicitud_id);
        if ($solicitud->fueRechazada() == null) {
            $solicitud->hasRechazo()->attach($request->motivo_rechazo, ['observacion' => $request->observacion]);
            if ($solicitud->fueRechazada() != null) {
                \Mail::to($solicitud->correo_electronico_solicitante)->send(new NotificarPreAsignacionRechazada($solicitud));

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha rechazado la solicitud de pre-asignación.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en operación',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La solicitud ya cuenta con un rechazo.'],
                'encabezado' => 'Error en liberación',
            ], 200);
        }
    }

    public function obtenerSolicitudes()
    {
        $solicitudes = solicitud_preasignacion::paginate(50);
        return view('admin.tramites.preasignaciones.listadoSolicitudes', ['solicitudes' => $solicitudes])->render();
    }

    public function liberarSolicitud($id)
    {
        $solicitud = solicitud_preasignacion::find($id);
        if ($solicitud->hasPlacaActiva() != null) {
            try {
                $solicitud->hasPlacaActiva()->pivot->update(['fecha_liberacion' => date('Y-m-d H:i:s')]);

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha liberado la placa.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido liberar la placa.'],
                    'encabezado' => 'Error en la liberación.',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La solicitud no tiene ninguna placa asignada.'],
                'encabezado' => 'Error en la liberación.',
            ], 200);
        }
    }

    public function obtenerManifiesto($id)
    {
        $solicitud = solicitud_preasignacion::find($id);
        $name = explode('/', $solicitud->manifiesto_importacion);
        $headers = [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/'.$solicitud->manifiesto_importacion), array_last($name), $headers);
    }

    public function obtenerFactura($id)
    {
        $solicitud = solicitud_preasignacion::find($id);
        $name = explode('/', $solicitud->factura_compra);
        $headers = [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/'.$solicitud->factura_compra), array_last($name), $headers);
    }

    public function obtenerCedulaPropietario($id)
    {
        $solicitud = solicitud_preasignacion::find($id);
        $name = explode('/', $solicitud->cedula_propietario);
        $headers = [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/'.$solicitud->cedula_propietario), array_last($name), $headers);
    }

    private function obtenerComplementos()
    {
        $clases_vehiculos = vehiculo_clase::pluck('name', 'id');
        $tipos_documentos_identidad = usuario_tipo_documento::pluck('name', 'id');

        return [
            'clases_vehiculos' => $clases_vehiculos,
            'tipos_documentos' => $tipos_documentos_identidad,
        ];
    }

    public function nuevaPreasignacion()
    {
        $clases_vehiculos = vehiculo_clase::pluck('name', 'id');
        $tipos_documentos_identidad = usuario_tipo_documento::pluck('name', 'id');

        return view('admin.tramites.preasignaciones.nuevaPreasignacion', $this->obtenerComplementos());
    }

    public function crearPreasignacion(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipo_documento' => 'integer|exists:usuario_tipo_documento,id|required',
            'numero_documento' => 'numeric|required',
            'nombre_solicitante' => 'string|required',
            'telefono_solicitante' => 'numeric',
            'correo_solicitante' => 'required|email',
            'clase_vehiculo' => 'integer|exists:vehiculo_clase,id|required',
            'servicio_vehiculo' => 'integer|exists:vehiculo_servicio,id|required',
            'manifiesto_importacion' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'factura_compra' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'numero_motor' => 'string|required',
            'numero_chasis' => 'string|required',
            'observaciones' => 'string',
            'nombre_propietario' => 'required|string',
            'numero_documento_propietario' => 'numeric|required',
            'cedula_propietario' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'tipo_documento_propietario' => 'integer|exists:usuario_tipo_documento,id|required',
        ], [
            'tipo_documento.integer' => 'El formato del documento de identificación no es válido.',
            'tipo_documento.exists' => 'El documento de identificación especificado no existe en el sistema.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identificación.',
            'numero_documento.numeric' => 'El formato del número de documento no es válido. (Sólo números)',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad.',
            'nombre_solicitante.string' => 'El nombre del solicitante no tiene un formato válido.',
            'nombre_solicitante.required' => 'No se ha especificado el nombre del solicitante.',
            'telefono_solicitante.numeric' => 'El número de teléfono especificado no tiene un formato válido. (Sólo números)',
            'correo_solicitante.email' => 'El correo especificado no tiene un formato válido.',
            'correo_solicitante.required' => 'No se ha especificado un correo electrónico.',
            'clase_vehiculo.integer' => 'El formato de la clase de vehículo especificado no tiene un formato válido.',
            'clase_vehiculo.exists' => 'La clase de vehículo especificado no existe en el sistema.',
            'clase_vehiculo.required' => 'No se ha especificado la clase del vehículo.',
            'servicio_vehiculo.integer' => 'El formato del servicio del vehículo especificado no tiene un formato válido.',
            'servicio_vehiculo.exists' => 'El servicio del vehículo especificado no existe en el sistema.',
            'servicio_vehiculo.required' => 'No se ha especificado el servicio del vehículo.',
            'numero_motor.string' => 'El número de motor no tiene un formato válido. (Sólo número y letras)',
            'numero_motor.required' => 'No se ha especificado el número del motor.',
            'numero_chasis.string' => 'El número de motor de chasis un formato válido. (Sólo número y letras)',
            'numero_chasis.required' => 'No se ha especificado el número del chasis.',
            'observaciones.string' => 'Las observaciones no tiene un formato válido. (Sólo número y letras)',
            'manifiesto_importacion.mimes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png)',
            'manifiesto_importacion.mimetypes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png.)',
            'manifiesto_importacion.max' => 'El manifiesto de importación supera el tamaño máximo permitido de 2MB',
            'factura_compra.mimes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png)',
            'factura_compra.mimetypes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png.)',
            'factura_compra.max' => 'La factura de compra supera el tamaño máximo permitido de 2MB',
            'nombre_propietario.required' => 'No se ha especificado el nombre del propietario del vehículo.',
            'nombre_propietario.string' => 'EL nombre del propietario del vehículo especificado no tiene un formato válido.',
            'numero_documento_propietario.numeric' => 'El número de documento de identidad del propietario especificado no tiene un formato válido.',
            'numero_documento_propietario.required' => 'No se ha especificado el número de documento de identidad del propietario.',
            'cedula_propietario.mimes' => 'La cedula del propietario suministrada no tiene un formato válido.',
            'cedula_propietario.mimetypes' => 'La cedula del propietario suministrada no tiene un formato válido.',
            'cedula_propietario.max' => 'La cedula del propietario suministrada excede el tamaño máximo permitivo de :max MB.',
            'tipo_documento_propietario.integer' => 'El ID del tipo de documento de identidad del propietario no tiene un formato válido.',
            'tipo_documento_propietario.required' => 'No se ha especificado el tipo de documento de identidad del propietario.',
            'tipo_documento_propietario.exists' => 'El tipo de documento de identidad del propietaro especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.tramites.preasignaciones.nuevaPreasignacion', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
        } else {
            try {
                \DB::beginTransaction();
                $solicitud = solicitud_preasignacion::create([
                    'tipo_documento_solicitante_id' => $request->tipo_documento,
                    'numero_documento_solicitante' => $request->numero_documento,
                    'nombre_solicitante' => strtoupper($request->nombre_solicitante),
                    'numero_telefono_solicitante' => $request->telefono_solicitante,
                    'correo_electronico_solicitante' => strtoupper($request->correo_solicitante),
                    'vehiculo_clase_id' => $request->clase_vehiculo,
                    'vehiculo_servicio_id' => $request->servicio_vehiculo,
                    'numero_motor' => strtoupper($request->numero_motor),
                    'numero_chasis' => strtoupper($request->numero_chasis),
                    'nombre_propietario' => strtoupper($request->nombre_propietario),
                    'numero_documento_propietario' => strtoupper($request->numero_documento_propietario),
                    'tipo_documento_propietario_id' => strtoupper($request->tipo_documento_propietario),
                    'observacion' => strtoupper($request->observaciones),
                ]);
                \DB::commit();
                if($request->manifiesto_importacion != null){
                    $solicitud->manifiesto_importacion = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('manifiesto_importacion'));
                    $solicitud->save();
                }
                if($request->factura_compra != null){
                    $solicitud->factura_compra = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('factura_compra'));
                    $solicitud->save();
                }
                if($request->cedula_propietario != null){
                    $solicitud->cedula_propietario = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('cedula_propietario'));
                    $solicitud->save();
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'La pre-asignación ha sido registrada satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido registrar la pre-asignación.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function matricularPreasignacion($id)
    {
        try {
            $solicitud = solicitud_preasignacion::find($id);
            if($solicitud->hasPlacaActiva()->pivot->fecha_liberacion != null || $solicitud->hasPlacaActiva()->pivot->fecha_matricula != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La Pre-Asignación no puede ser matriculada debido a que pudo ser liberada o ya está matriculada.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
            $carpeta = archivo_carpeta::firstOrCreate([
                'name' => strtoupper($solicitud->hasPlacaActiva()->name),
                'available' => archivo_carpeta_estado::select('estado_carpeta')->where('id', 1)->first()->estado_carpeta,
                'archivo_carpeta_estado_id' => 1,
                'vehiculo_clase_id' => $solicitud->vehiculo_clase_id,
                'vehiculo_servicio_id' => $solicitud->vehiculo_servicio_id,
            ]);
            if ($carpeta != null) {
                $solicitud->hasPlacaActiva()->pivot->update(['fecha_matricula' => date('Y-m-d H:i:s')]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha matriculado la placa '.$solicitud->hasPlacaActiva()->name.'.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido matricular la placa '.$solicitud->hasPlacaActiva()->name.'.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido matricular la placa '.$solicitud->hasPlacaActiva()->name.'.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function subirManifiesto($id){
        return view('admin.tramites.preasignaciones.subirManifiesto', ['id'=>$id])->render();
    }

    public function subirFactura($id){
        return view('admin.tramites.preasignaciones.subirFactura', ['id'=>$id])->render();
    }

    public function guardarManifiesto(Request $request){
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'file.mimes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png)',
            'file.mimetypes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png.)',
            'file.max' => 'El manifiesto de importación supera el tamaño máximo permitido de 2MB',
            'file.required' => 'No se ha suministrado el manifiesto de importación'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $solicitud = solicitud_preasignacion::find($request->id);
            $solicitud->manifiesto_importacion = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('file'));
            if($solicitud->save()){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha cargado el manifiesto de importación.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido cargar el manifiesto de importación.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function guardarFactura(Request $request){
        $validator = \Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
        ], [
            'file.mimes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png)',
            'file.mimetypes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png.)',
            'file.max' => 'La factura de compra supera el tamaño máximo permitido de 2MB',
            'file.required' => 'No se ha suministrado la factura de compra',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $solicitud = solicitud_preasignacion::find($request->id);
            $solicitud->factura_compra = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('file'));
            if($solicitud->save()){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha cargado la factura de compra.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido cargar la factura de compra.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }
}
