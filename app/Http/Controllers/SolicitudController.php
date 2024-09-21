<?php

namespace App\Http\Controllers;

use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\archivo_carpeta_estado;
use App\archivo_carpeta_prestamo;
use App\archivo_solicitud;
use App\archivo_carpeta;
use App\archivo_solicitud_funcionario;
use App\archivo_solicitud_motivo;
use App\Events\nuevaMisSolicitudCarpeta;
use App\Events\solicitudCarpetaAprobada;
use App\Events\solicitudCarpetaDenegada;
use App\Events\solicitudCarpetaEntregada;
use App\Events\solicitudCarpetaIngresa;
use App\Events\solicitudCarpetaValidada;
use App\Tramite;
use App\tramite_solicitud;
use App\User;
use App\archivo_solicitud_validacion;
use App\archivo_solicitud_denegacion;
use App\vehiculo_clase;
use App\vehiculo_servicio;
use Validator;
use App\archivo_solicitud_va_ve;
use App\archivo_solicitud_de_mo;
use Session;

class SolicitudController extends Controller
{
    public function entregarCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'solicitudId' => 'required|integer|exists:archivo_solicitud,id',
            'usuarioRecibe' => 'required|integer|exists:users,id',
            'PIN' => 'required|numeric',
        ], [
            'solicitudId.required' => 'No se ha proporcionado el ID de la solicitud.',
            'solicitudId.integer' => 'El formato de la solicitud proporcionada no es válida.',
            'usuarioRecibe.required' => 'No se ha proporcionado el ID del usuario destinatario.',
            'usuarioRecibe.integer' => 'El formato del usuario destinatario no es válida.',
            'solicitudId.exists' => 'La solicitud no existe en la base de datos',
            'usuarioRecibe.exists' => 'El usuario proporcionado no existe en la base de datos',
            'PIN.required' => 'No se ha especificado el PIN de seguridad.',
            'PIN.numeric' => 'El PIN de seguridad especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $user_delivered = User::find($request->usuarioRecibe);
            if (\Hash::check($request->PIN, $user_delivered->pin_code)) {
                $success = false;
                \DB::beginTransaction();
                try {
                    $archivo_solicitud = archivo_solicitud::with('hasCarpetaPrestada')->find($request->solicitudId);
                    $archivo_carpeta_prestamo = $archivo_solicitud->hasCarpetaPrestada;
                    $archivo_carpeta_prestamo->funcionario_recibe_id = $request->usuarioRecibe;
                    $archivo_carpeta_prestamo->funcionario_entrega_id = auth()->user()->id;
                    $archivo_carpeta_prestamo->fecha_entrega = date('Y-m-d H:i:s');
                    $archivo_carpeta_prestamo->save();

                    event(new solicitudCarpetaEntregada($archivo_solicitud, $archivo_carpeta_prestamo));

                    \DB::commit();
                    $success = true;
                } catch (\Exception $e) {
                    $success = false;
                    \DB::rollBack();
                }

                if ($success) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'La carpeta ha sido entregada satisfactoriamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido autorizar la entrega de la carpeta.'],
                        'encabezado' => 'Errores en el proceso:',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El PIN de seguridad no coincide.'],
                    'encabezado' => 'Errores en la verificación:',
                ], 200);
            }
        }
    }

    public function procesarSolicitudes()
    {
        $criterios = [
            '1' => 'Código digiturno',
        ];

        return view('admin.solicitudes.procesarSolicitudes', ['criterios' => $criterios]);
    }

    public function entregarCarpetas()
    {
        $criterios = [
            '1' => 'Código digiturno',
        ];

        return view('admin.solicitudes.entregarCarpetas', ['criterios' => $criterios]);
    }

    public function validarSolicitudes()
    {
        $criterios = [
            '1' => 'Código digiturno',
        ];

        return view('admin.solicitudes.validarSolicitudes', ['criterios' => $criterios]);
    }

    public function aprobarSolicitud($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:archivo_solicitud,id',
        ], [
            'id.required' => 'No se ha proporcionado el ID de la solicitud.',
            'id.exists' => 'El ID de la solicitud proporcionado no existe.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                \DB::beginTransaction();
                $solicitud_carpeta = archivo_solicitud::find($id);
                /*
                 * Identificar otras solicitudes prioritarias
                 */
                if($solicitud_carpeta->origen_type == 'App\tramite_Servicio'){
                    $solicitudPrioritaria = archivo_solicitud::whereHas('hasSolicitudFuncionario', function ($query) use ($solicitud_carpeta){
                        $query->where('placa', $solicitud_carpeta->hasOrigen->placa)->whereHas('hasMotivo', function ($query2){
                            $query2->where('priorizar', 1);
                        });
                    })->doesntHave('hasCarpetaPrestada')->where('id','!=',$solicitud_carpeta->id)->first();
                    if($solicitudPrioritaria != null){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['La carpeta especificada tiene un solicitud prioritaria por parte de '.$solicitudPrioritaria->hasOrigen->hasFuncionario->name. 'para '.$solicitudPrioritaria->hasOrigen->HasMotivo->name.'. Por tal motivo no se aprueba esta solicitud.'],
                            'encabezado' => 'Error en la aprobación',
                        ], 200);
                    }
                }
                if ($solicitud_carpeta->getEstado() === 'Por procesar') {
                    $archivo_carpeta = archivo_carpeta::where('name', $solicitud_carpeta->hasOrigen->placa)->first();
                    $archivo_carpeta_estado = archivo_carpeta_estado::where('name', 'EN INVENTARIO')->first();
                    if ($archivo_carpeta == null) {
                        $archivo_carpeta = archivo_carpeta::create([
                            'name' => $solicitud_carpeta->hasOrigen->placa,
                            'vehiculo_clase_id' => $solicitud_carpeta->hasOrigen->vehiculo_clase_id,
                            'vehiculo_servicio_id' => $solicitud_carpeta->hasOrigen->vehiculo_servicio_id,
                            'available' => 'NO',
                            'radicado' => null,
                            'archivo_carpeta_estado_id' => $archivo_carpeta_estado->id,
                        ]);
                    } else {
                        $archivo_carpeta->vehiculo_clase_id = $solicitud_carpeta->hasOrigen->vehiculo_clase_id;
                        $archivo_carpeta->vehiculo_servicio_id = $solicitud_carpeta->hasOrigen->vehiculo_servicio_id;
                        $archivo_carpeta->save();
                        if($archivo_carpeta->available == 'NO') {
                            if($archivo_carpeta->hasEstado->estado_carpeta == 'NO'){
                                return response()->view('admin.mensajes.errors', [
                                    'errors' => ['La carpeta solicitada tiene un estado que impide su salida del archivo. El estado es: ' . $archivo_carpeta->hasEstado->name . '.'],
                                    'encabezado' => 'Error en la aprobación',
                                ], 200);
                            } elseif($archivo_carpeta->hasPrestamoActivo() != null){
                                return response()->view('admin.mensajes.errors', [
                                    'errors' => ['La carpeta solicitada tiene un prestamo activo. El funcionario que la tiene es: ' . $archivo_carpeta->hasPrestamoActivo()->hasFuncionarioRecibe->name. '.'],
                                    'encabezado' => 'Error en la aprobación',
                                ], 200);
                            } elseif($archivo_carpeta->hasPrestamoPendiente() != null){
                                return response()->view('admin.mensajes.errors', [
                                    'errors' => ['La carpeta solicitada tiene un prestamo pendiente.'],
                                    'encabezado' => 'Error en la aprobación',
                                ], 200);
                            } elseif ($archivo_carpeta->hasSolicitudPendiente() != null) {
                                if($archivo_carpeta->origen_type == 'App\tramite_servicio'){
                                    if ($archivo_carpeta->hasSolicitudPendiente()->id != $solicitud_carpeta->hasOrigen->hasSolicitud->id) {
                                        return response()->view('admin.mensajes.errors', [
                                            'errors' => ['La carpeta solicitada tiene un trámite en curso diferente al que se le solicita.'],
                                            'encabezado' => 'Error en la aprobación',
                                        ], 200);
                                    }
                                }
                            }
                        }
                    }

                    $archivo_carpeta->unAvailable();
                    $archivo_carpeta_prestamo = new archivo_carpeta_prestamo();
                    $archivo_carpeta_prestamo->archivo_carpeta_id = $archivo_carpeta->id;
                    $archivo_carpeta_prestamo->funcionario_autoriza_id = auth()->user()->id;
                    $archivo_carpeta_prestamo->save();

                    $solicitud_carpeta->archivo_carpeta_prestamo_id = $archivo_carpeta_prestamo->id;
                    $solicitud_carpeta->save();

                    event(new solicitudCarpetaAprobada($solicitud_carpeta, $archivo_carpeta_prestamo));

                    \DB::commit();
                    $success = true;
                } else {
                    $success = false;
                    \DB::rollback();
                }
            } catch (\Exception $e) {
                $success = false;
                \DB::rollback();
            }

            if ($success) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'La solicitud ha sido aprobada exitosamente y se encuentra en la lista de espera a ser entregada.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la aprobación',
                ], 200);
            }
        }
    }

    public function solicitudesSinAprobar()
    {
        $solicitudes = archivo_solicitud::doesntHave('hasValidacion')->doesntHave('hasCarpetaPrestada')->doesntHave('hasDenegacion')->orderBy('created_at', 'asc')->get();

        return view('admin.solicitudes.listadoSolicitudesSinAprobar', ['solicitudes' => $solicitudes])->render();
    }

    public function solicitudesSinEntregar()
    {
        $solicitudes = archivo_solicitud::doesntHave('hasValidacion')->doesntHave('hasDenegacion')->has('hasCarpetaPrestada')->with('hasCarpetaPrestada')->whereHas('hasCarpetaPrestada', function ($query) {
            $query->where('fecha_entrega', null);
        })->orderBy('created_at', 'asc')->get();

        return view('admin.solicitudes.listadoSolicitudesSinEntregar', ['solicitudes' => $solicitudes])->render();
    }

    public function solicitudesSinDevolver()
    {
        $solicitudes = archivo_solicitud::doesntHave('hasValidacion')->doesntHave('hasDenegacion')->has('hasCarpetaPrestada')->whereHas('hasCarpetaPrestada', function ($query) {
            $query->where('fecha_entrega', '!=', null)->where('fecha_devolucion', null);
        })->orderBy('created_at', 'asc')->get();

        return view('admin.solicitudes.listadoSolicitudesSinDevolver', ['solicitudes' => $solicitudes])->render();
    }

    public function solicitudesSinValidar()
    {
        $solicitudes = archivo_solicitud::doesntHave('hasValidacion')->doesntHave('hasDenegacion')->has('hasCarpetaPrestada')->with('hasTramiteServicio', 'hasCarpetaPrestada')->whereHas('hasCarpetaPrestada', function ($query) {
            $query->where('fecha_devolucion', '!=', null);
        })->orderBy('created_at', 'asc')->paginate(25);

        return view('admin.solicitudes.listadoSolicitudesSinValidar', ['solicitudes' => $solicitudes])->render();
    }

    public function ingresarCarpeta($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:archivo_solicitud,id',
        ], [
            'id.required' => 'No se ha proporcionado el ID de la solicitud.',
            'id.exists' => 'El ID de la solicitud proporcionado no existe.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = false;
            try {
                \DB::beginTransaction();
                $archivo_solicitud = archivo_solicitud::with('hasCarpetaPrestada','hasCarpetaPrestada.hasCarpeta')->find($id);
                $archivo_carpeta_prestamo = $archivo_solicitud->hasCarpetaPrestada;
                $archivo_carpeta_prestamo->fecha_devolucion = date("Y-m-d H:i:s");
                $archivo_carpeta_prestamo->save();
                $carpeta = $archivo_carpeta_prestamo->hasCarpeta;
                $carpeta->available();
                event(new solicitudCarpetaIngresa($archivo_solicitud, $archivo_carpeta_prestamo));
                \DB::commit();
                $success = true;
            } catch (\Exception $e) {
                \DB::rollBack();
                $success = false;
            }

            if ($success) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la entrada de la carpeta al archivo',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la aprobación',
                ], 200);
            }
        }
    }

    public function validarSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipoValidacion' => 'required|integer|exists:archivo_solicitud_va_ve,id',
            'solicitudId' => 'required|integer|exists:archivo_solicitud,id',
            'observacionValidacion' => 'string',
        ], [
            'tipoValidacion.required' => 'No se ha proporcionado un ID valido para el tipo de validación.',
            'tipoValidacion.exists' => 'El ID del tipo de validación proporcionado no existe.',
            'tipoValidacion.integer' => 'El ID del tipo de validación especificado no tiene un formato válido.',
            'solicitudId.required' => 'No se ha proporcionado un ID valido para la solicitud.',
            'solicitudId.exists' => 'El ID de la solicitud proporcionada no existe.',
            'solicitudId.integer' => 'El ID de la solicitud especificado no tiene un formato válido.',
            'observacionValidacion.string' => 'La observación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $archivo_solicitud = archivo_solicitud::find($request->solicitudId);
                $archivo_solicitud->hasValidacion()->attach($request->tipoValidacion, [
                    'user_revision_id' => auth()->user()->id,
                    'observation' => $request->observacionValidacion,
                ]);
                event(new solicitudCarpetaValidada($archivo_solicitud));

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la evaluación de la solicitud.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la validación',
                ], 200);
            }
        }
    }

    public function denegarSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipoDenegacion' => 'required|integer|exists:archivo_solicitud_de_mo,id',
            'solicitudId' => 'required|integer|exists:archivo_solicitud,id',
            'observacionDenegacion' => 'string',
        ], [
            'tipoDenegacion.required' => 'No se ha proporcionado un ID valido para el tipo de denegación.',
            'tipoDenegacion.exists' => 'El ID del tipo de denegación proporcionado no existe.',
            'tipoDenegacion.integer' => 'El ID del tipo de denegación especificado no tiene un formato válido.',
            'solicitudId.required' => 'No se ha proporcionado un ID valido para la solicitud.',
            'solicitudId.exists' => 'El ID de la solicitud proporcionada no existe.',
            'solicitudId.integer' => 'El ID de la solicitud especificada no tiene un formato válido.',
            'observacionDenegacion' => 'La observación especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        } else {
            try {
                $archivo_solicitud = archivo_solicitud::find($request->solicitudId);
                $archivo_solicitud->hasDenegacion()->attach($request->tipoDenegacion, [
                    'user_revision_id' => auth()->user()->id,
                    'observation' => $request->observacionDenegacion,
                ]);
                event(new solicitudCarpetaDenegada($archivo_solicitud));

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la denegación de la solicitud.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la validación',
                ], 200);
            }
        }
    }

    public function filtrarBusqueda($filtro, $parametro)
    {
        $parametro = strtoupper($parametro);
        $solicitudes = archivo_solicitud::with('hasSolicitud', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('user_request_id', Auth::user()->id)->whereHas('hasCarpeta', function (
                $query
            ) use ($parametro) {
                $query->where('name', 'like', '%'.$parametro.'%');
            })->orWhere('digiturno_code', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(25);

        return view('admin.solicitudes.listadoMisSolicitudes', [
            'solicitudes' => $solicitudes,
            'parametro' => $parametro,
        ])->render();
    }

    public function filtrarSinAprobar($parametro, $filtro)
    {
        $resultados = null;
        switch ($filtro) {
            case 1:
                $resultados = archivo_solicitud::whereHas('hasCarpeta', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '=', null)->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $resultados = archivo_solicitud::doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '=', null)->where('digiturno_code', $parametro)->orderBy('created_at', 'desc')->get();
                break;
            case 3:
                $resultados = archivo_solicitud::whereHas('hasSolicitante', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '=', null)->orderBy('created_at', 'desc')->get();
                break;
        }

        return view('admin.solicitudes.listadoSolicitudesSinAprobar', ['solicitudes' => $resultados])->render();
    }

    public function filtrarSinEntregar($parametro, $filtro)
    {
        $resultados = null;
        switch ($filtro) {
            case 1:
                $resultados = archivo_solicitud::whereHas('hasCarpeta', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_delivered', null)->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $resultados = archivo_solicitud::doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_delivered', null)->where('digiturno_code', $parametro)->orderBy('created_at', 'desc')->get();
                break;
            case 3:
                $resultados = archivo_solicitud::whereHas('hasSolicitante', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_delivered', null)->orderBy('created_at', 'desc')->get();
                break;
        }

        return view('admin.solicitudes.listadoSolicitudesSinEntregar', ['solicitudes' => $resultados])->render();
    }

    public function filtrarSinDevolver($parametro, $filtro)
    {
        $resultados = null;
        switch ($filtro) {
            case 1:
                $resultados = archivo_solicitud::whereHas('hasCarpeta', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '=', null)->where('folder_delivered', '!=', null)->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $resultados = archivo_solicitud::doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '=', null)->where('folder_delivered', '!=', null)->where('digiturno_code', $parametro)->orderBy('created_at', 'desc')->get();
                break;
            case 3:
                $resultados = archivo_solicitud::whereHas('hasSolicitante', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '=', null)->where('folder_delivered', '!=', null)->orderBy('created_at', 'desc')->get();
                break;
        }

        return view('admin.solicitudes.listadoSolicitudesSinDevolver', ['solicitudes' => $resultados])->render();
    }

    public function filtrarSinValidar($parametro, $filtro)
    {
        $resultados = null;
        switch ($filtro) {
            case 1:
                $resultados = archivo_solicitud::whereHas('hasCarpeta', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '!=', null)->where('folder_delivered', '!=', null)->has('hasValidacion', '=', 0)->orderBy('created_at', 'desc')->get();
                break;
            case 2:
                $resultados = archivo_solicitud::doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '!=', null)->where('folder_delivered', '!=', null)->has('hasValidacion', '=', 0)->where('digiturno_code', $parametro)->orderBy('created_at', 'desc')->get();
                break;
            case 3:
                $resultados = archivo_solicitud::whereHas('hasSolicitante', function ($query) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->doesntHave('hasDenegacion')->with('hasTurno', 'hasFuncionarioAutoriza', 'hasFuncionarioRecibe', 'hasFuncionarioEntrega')->where('request_aproved', '!=', null)->where('folder_returned', '!=', null)->where('folder_delivered', '!=', null)->has('hasValidacion', '=', 0)->orderBy('created_at', 'desc')->get();
                break;
        }

        return view('admin.solicitudes.listadoSolicitudesSinAprobar', ['solicitudes' => $resultados])->render();
    }

    public function get_denegarSolicitud($id)
    {
        $tiposDenegaciones = archivo_solicitud_de_mo::pluck('name', 'id');

        return view('admin.solicitudes.denegarSolicitud', [
            'tiposDenegaciones' => $tiposDenegaciones,
            'id' => $id,
        ])->render();
    }

    public function get_entregarCarpeta($id)
    {
        $archivo_solicitud = archivo_solicitud::find($id);
        $funcionario = $archivo_solicitud->hasOrigen->hasFuncionario;
        $usuarios = User::pluck('name', 'id');
        $usuarioSolicitante = $funcionario->id;

        return view('admin.solicitudes.entregarCarpeta', [
            'usuarios' => $usuarios,
            'id' => $id,
            'usuarioSolicitante' => $usuarioSolicitante,
        ])->render();
    }

    public function get_validarSolicitud($id)
    {
        $tiposValidaciones = archivo_solicitud_va_ve::pluck('name', 'id');

        return view('admin.solicitudes.validarSolicitud', [
            'tiposValidaciones' => $tiposValidaciones,
            'id' => $id,
        ])->render();
    }

    public function obtenerTiposValidaciones()
    {
        $tiposValidaciones = archivo_solicitud_va_ve::paginate(25);
        return view('admin.solicitudes.listadoTiposValidaciones', [
            'tiposValidaciones' => $tiposValidaciones
        ])->render();
    }

    public function nuevoTipoValidacion()
    {
        return view('admin.solicitudes.crearTipoValidacion')->render();
    }

    public function crearTipoValidacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:archivo_solicitud_va_ve,name'
        ], [
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            archivo_solicitud_va_ve::create([
                'name' => strtoupper($request->name)
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el tipo de validación.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }

    public function editarTipoValidacion($id)
    {
        $tipoValidacion = archivo_solicitud_va_ve::find($id);
        return view('admin.solicitudes.editarTipoValidacion',['tipoValidacion'=>$tipoValidacion])->render();
    }

    public function actualizarTipoValidacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:archivo_solicitud_va_ve,id',
            'name' => ['required','string',Rule::unique('archivo_solicitud_va_ve','name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo ha actualizar.',
            'id.integer' => 'El ID del tipo ha actualizar no es válido..',
            'id.exists' => 'El tipo especificado no existe.',
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            $tipoValidacion = archivo_solicitud_va_ve::find($request->id);
            $tipoValidacion->name = strtoupper($request->name);
            $tipoValidacion->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el tipo de validación.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }

    public function misSolicitudes_index()
    {
        $criterios = [
            '1' => 'Código digiturno',
        ];

        return view('admin.solicitudes.misSolicitudes', ['criterios'=>$criterios]);
    }

    public function misSolicitudes_todas()
    {
        $misSolicitudes = archivo_solicitud_funcionario::where('funcionario_id', auth()->user()->id)->paginate(50);
        return view('admin.solicitudes.listadoMisSolicitudes',['solicitudes'=>$misSolicitudes])->render();
    }

    public function misSolicitudes_crear()
    {
        $motivos = archivo_solicitud_motivo::pluck('name','id');
        return view('admin.solicitudes.nuevaSolicitud', ['motivos'=>$motivos])->render();
    }

    public function misSolicitudes_registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|integer|exists:archivo_solicitud_motivo,id',
            'placa' => 'required|string|exists:archivo_carpeta,name'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try{
            $archivo_carpeta = archivo_carpeta::where('name', $request->placa)->first();
            $motivoSolicitud = archivo_solicitud_motivo::find($request->motivo);

            if($archivo_carpeta != null){
                $solicitudPrioritaria = archivo_solicitud::whereHas('hasSolicitudFuncionario', function ($query) use ($request){
                    $query->where('placa', $request->placa)->whereHas('hasMotivo', function ($query2){
                        $query2->where('priorizar', 1);
                    });
                })->doesntHave('hasCarpetaPrestada')->first();
                if($solicitudPrioritaria != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La carpeta especificada tiene un solicitud prioritaria por parte de '.$solicitudPrioritaria->hasOrigen->hasFuncionario->name. 'para '.$solicitudPrioritaria->hasOrigen->HasMotivo->name.'. Por tal motivo no se realizará esta solicitud.'],
                        'encabezado' => 'Error en la aprobación',
                    ], 200);
                }
                if($archivo_carpeta->available == 'NO') {
                    if($archivo_carpeta->hasEstado->estado_carpeta == 'NO'){
                        if(!$motivoSolicitud->priorizar){
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['La carpeta solicitada tiene un estado que impide su salida del archivo. El estado es: ' . $archivo_carpeta->hasEstado->name . '.'],
                                'encabezado' => 'Error en la aprobación',
                            ], 200);
                        }
                    } elseif($archivo_carpeta->hasPrestamoActivo() != null){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['La carpeta solicitada tiene un prestamo activo. El funcionario que la tiene es: ' . $archivo_carpeta->hasPrestamoActivo()->hasFuncionarioRecibe->name. '.'],
                            'encabezado' => 'Error en la aprobación',
                        ], 200);
                    } elseif($archivo_carpeta->hasPrestamoPendiente() != null){
                        if(!$motivoSolicitud->priorizar) {
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['La carpeta solicitada tiene un prestamo pendiente.'],
                                'encabezado' => 'Error en la aprobación',
                            ], 200);
                        }else{
                            $archivo_carpeta->hasPrestamoPendiente()->delete();
                        }
                    } elseif ($archivo_carpeta->hasSolicitudPendiente() != null) {
                        if(!$motivoSolicitud->priorizar){
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['La carpeta solicitada tiene una solicitud en marcha.'],
                                'encabezado' => 'Error en la aprobación',
                            ], 200);
                        }
                    }
                }
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La carpeta solicitada no existe en el sistema.'],
                    'encabezado' => 'Error en la aprobación',
                ], 200);
            }

            $solicitud = archivo_solicitud_funcionario::create([
                'funcionario_id' => auth()->user()->id,
                'archivo_sol_mo_id' => $request->motivo,
                'placa' => $request->placa
            ]);

            archivo_solicitud::create([
                'request_date' => date('Y-m-d H:i:s'),
                'origen_id' => $solicitud->id,
                'origen_type' => 'App\archivo_solicitud_funcionario',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            event(new nuevaMisSolicitudCarpeta($solicitud));
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha realizado la solicitud correctamente.',
                'encabezado' => '¡Completado!'
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido realizar la solicitud.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function obtenerMotivosSolicitud()
    {
        $motivos = archivo_solicitud_motivo::paginate(25);
        return view('admin.solicitudes.listadoMotivosSolicitud', [
            'motivos' => $motivos
        ])->render();
    }

    public function nuevoMotivoSolicitud()
    {
        return view('admin.solicitudes.crearMotivoSolicitud')->render();
    }

    public function crearMotivoSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:archivo_solicitud_motivo,name',
            'priorizar' => 'required|boolean'
        ], [
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.',
            'priorizar.require' => 'No se ha especificado si el motivo de solicitud priorizará la salida del archivo.',
            'priorizar.boolean' => 'El valor del campo forzar salida no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            archivo_solicitud_motivo::create([
                'name' => strtoupper($request->name),
                'priorizar' => $request->priorizar
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el motivo solicitud.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }

    public function editarMotivoSolicitud($id)
    {
        $motivo = archivo_solicitud_motivo::find($id);
        return view('admin.solicitudes.editarMotivoSolicitud',['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:archivo_solicitud_motivo,id',
            'name' => ['required','string',Rule::unique('archivo_solicitud_motivo','name')->ignore($request->id)],
            'priorizar' => 'required|boolean'
        ], [
            'id.required' => 'No se ha especificado el tipo ha actualizar.',
            'id.integer' => 'El ID del tipo ha actualizar no es válido..',
            'id.exists' => 'El tipo especificado no existe.',
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.',
            'priorizar.require' => 'No se ha especificado si el motivo de solicitud priorizará la salida del archivo.',
            'priorizar.boolean' => 'El valor del campo forzar salida no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            $motivo = archivo_solicitud_motivo::find($request->id);
            $motivo->name = strtoupper($request->name);
            $motivo->priorizar = $request->priorizar;
            $motivo->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el motivo solicitud.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }

    public function obtenerMotivosDenegacion()
    {
        $motivos = archivo_solicitud_de_mo::paginate(25);
        return view('admin.solicitudes.listadoMotivosDenegacion', [
            'motivos' => $motivos
        ])->render();
    }

    public function nuevoMotivoDenegacion()
    {
        return view('admin.solicitudes.crearMotivoDenegacion')->render();
    }

    public function crearMotivoDenegacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:archivo_solicitud_motivo,name'
        ], [
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            archivo_solicitud_de_mo::create([
                'name' => strtoupper($request->name)
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el motivo denegación.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }

    public function editarMotivoDenegacion($id)
    {
        $motivo = archivo_solicitud_de_mo::find($id);
        return view('admin.solicitudes.editarMotivoDenegacion',['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoDenegacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:archivo_solicitud_motivo,id',
            'name' => ['required','string',Rule::unique('archivo_solicitud_motivo','name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo ha actualizar.',
            'id.integer' => 'El ID del tipo ha actualizar no es válido..',
            'id.exists' => 'El tipo especificado no existe.',
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la denegación:',
            ], 200);
        }

        try {
            $motivo = archivo_solicitud_de_mo::find($request->id);
            $motivo->name = strtoupper($request->name);
            $motivo->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el motivo denegación.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la validación',
            ], 200);
        }
    }
}


