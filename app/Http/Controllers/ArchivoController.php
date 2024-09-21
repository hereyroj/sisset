<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\archivo_carpeta_ca_mo;
use App\archivo_carpeta;
use App\archivo_carpeta_cancelacion;
use App\archivo_carpeta_estado;
use App\archivo_carpeta_traslado;
use App\archivo_solicitud;
use App\departamento;
use App\ciudad;
use App\Imports\ArchivoCarpetaImport;
use App\vehiculo_clase;
use App\vehiculo_servicio;
use Illuminate\Validation\Rule;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Storage;
use App\Exports\HistorialCarpeta;

class ArchivoController extends Controller
{
    public function obternerCiudadesDpto($idDpto)
    {
        $ciudades = ciudad::where('departamento_id', $idDpto)->get();

        return $ciudades->toJson();
    }

    public function administrar()
    {
        return view('admin.archivo.administrar');
    }

    public function realizarTrasladoCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carpetaTraslado' => 'required|integer|exists:archivo_carpeta,id',
            'fecha_traslado_submit' => 'date|required',
            'departamentoTraslado' => 'required|integer|exists:departamento,id',
            'ciudadTraslado' => 'required|integer|exists:municipio,id',
            'num_certificado_runt' => 'required|numeric',
        ], [
            'carpetaTraslado.required' => 'No se ha especificado el ID de la carpeta a trasladar.',
            'carpetaTraslado.integer' => 'El ID de la carpeta no tiene un formato válido.',
            'carpetaTraslado.exists' => 'El ID de la carpeta especificada no existe en la base de datos.',
            'fecha_traslado_submit.date' => 'El formato de fecha de traslado no es válido.',
            'fecha_traslado_submit.required' => 'No se ha especificado la fecha del traslado.',
            'departamentoTraslado.required' => 'No se ha especificado el departamento de traslado.',
            'departamentoTraslado.integer' => 'El ID del departamento especificado no tiene un formato válido.',
            'departamentoTraslado.exists' => 'El departamento especificado no existe en la base de datos.',
            'ciudadTraslado.required' => 'No se ha especificado el municipio de traslado.',
            'ciudadTraslado.integer' => 'El ID del municipio especificado no tiene un formato válido.',
            'ciudadTraslado.exists' => 'El municipio especificado no existe en la base de datos.',
            'num_certificado_runt.required' => 'No se ha proporcionado el número del certificado RUNT del funcionario que autorizó el traslado.',
            'num_certificado_runt.numeric' => 'El número del certificado RUNT que autorizó el traslado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $estado = archivo_carpeta_estado::where('name', 'TRASLADADA')->first();//obtenemos el objeto que representa el estado "TRASLADADA" para obtener su id
            $carpeta = archivo_carpeta::with('couldHaveTraslado', 'couldHaveCancelacion')->find($request->carpetaTraslado);
            $carpetaOld = $carpeta;
            if ($carpeta->hasSolicitudPendiente() == null) {
                if ($carpeta->couldHaveTraslado != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La carpeta ya tiene un registro de traslado.'],
                        'encabezado' => 'Error en el traslado',
                    ], 200);
                } elseif ($carpeta->couldHaveCancelacion != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La carpeta tiene un estado de cancelada. No se puede aplicar un traslado.'],
                        'encabezado' => 'Error en el traslado',
                    ], 200);
                } else {
                    $carpeta->available = $estado->estado_carpeta;//disponibilidad por defecto de acuerdo al estado que se le asigna
                    $carpeta->archivo_carpeta_estado_id = $estado->id;
                    if ($carpeta->save()) {
                        $traslado = new archivo_carpeta_traslado();
                        $traslado->fecha_traslado = $request->fecha_traslado_submit;
                        $traslado->departamento_id = $request->departamentoTraslado;
                        $traslado->municipio_id = $request->ciudadTraslado;
                        $traslado->num_certificado_runt = $request->num_certificado_runt;
                        $traslado->carpeta_id = $carpeta->id;
                        if ($traslado->save()) {
                            return response()->view('admin.mensajes.success', [
                                'mensaje' => 'Se ha registrado el traslado de la carpeta.',
                                'encabezado' => '¡Completado!',
                            ], 200);
                        } else {
                            $carpetaOld->save();

                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                                'encabezado' => 'Error en el traslado',
                            ], 200);
                        }
                    } else {
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['No se ha podido realizar los cambios. Por favor contacte a un administrador.'],
                            'encabezado' => 'Error en el traslado',
                        ], 200);
                    }
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La carpeta esta siendo usada en un trámite o su estado actual no permite modificaciones.'],
                    'encabezado' => 'Error en el traslado',
                ], 200);
            }
        }
    }

    public function editarCarpeta($id)
    {
        $carpeta = archivo_carpeta::find($id);
        $clasesVehiculos = vehiculo_clase::pluck('name', 'id');
        $serviciosVehiculos = vehiculo_servicio::pluck('name', 'id');

        return view('admin.archivo.editarCarpeta', [
            'carpeta' => $carpeta,
            'clasesVehiculos' => $clasesVehiculos,
            'serviciosVehiculos' => $serviciosVehiculos,
        ]);
    }

    public function actualizarCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCarpeta' => 'required|integer|exists:archivo_carpeta,id',
            'name' => 'string|max:8|min:6',
            'claseVehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'servicioVehiculo' => 'required|integer|exists:vehiculo_servicio,id',
        ], [
            'idCarpeta.required' => 'No se especificado una carpeta.',
            'idCarpeta.integer' => 'El ID de la carpeta especificada no tiene un formato válido',
            'idCarpeta.exists' => 'La carpeta especificada no existe en la base de datos.',
            'name.string' => 'El formato del nombre de placa no es válido.',
            'name.max' => 'La placa tiene un límite máximo de :max caracteres',
            'name.min' => 'La placa tiene un límite mínimo de :min caracteres',
            'claseVehiculo.required' => 'No se ha especificado la clase del vehiculo al que pertenece la placa.',
            'claseVehiculo.integer' => 'El ID del tipo de clase de vehiculo especificado no existe en la base de datos.',
            'claseVehiculo.exists' => 'La clase de vehiculo especificado no existe en la base de datos.',
            'servicioVehiculo.required' => 'No se ha especificado el servicio del vehículo.',
            'servicioVehiculo.integer' => 'El ID del servicio de vehículo especificado no tiene un formato válido.',
            'servicioVehiculo.exists' => 'El servicio de vehículo especificada no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $claseVehiculo = vehiculo_clase::find($request->claseVehiculo);
            if ($claseVehiculo->required_letter == 'yes') {
                if (is_numeric(substr($request->name, -1))) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La clase de vehículo escogida requiere que la placa termine con una letra'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                } else {
                    return $this->actualizarCarpetaDB($request);
                }
            } else {
                if (is_numeric(substr($request->name, -1))) {
                    return $this->actualizarCarpetaDB($request);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La clase de vehículo escogida requiere que la placa termine con una letra'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            }
        }
    }

    private function actualizarCarpetaDB(Request $request)
    {
        $carpeta = archivo_carpeta::find($request->idCarpeta);
        $carpeta->vehiculo_clase_id = $request->claseVehiculo;
        $carpeta->vehiculo_servicio_id = $request->servicioVehiculo;
        $carpeta->name = $request->name;
        if ($carpeta->save()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la carpeta.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la actualización',
            ], 200);
        }
    }

    public function obternerCarpetas($txtCarpeta, $criterioBusqueda)
    {
        if ($criterioBusqueda == 'serie') {
            $nombre = substr($txtCarpeta, 0, 2);
            $terminacion = substr($txtCarpeta, -1);
            if (ctype_alpha($terminacion) && strlen($txtCarpeta) > 3) {
                $carpetas = archivo_carpeta::with('couldHaveTraslado', 'couldHaveCancelacion', 'hasEstado', 'hasClase', 'hasServicio')->where('name', 'like', $nombre.'%')->where('name', 'like', '%'.$terminacion)->orderBy(\DB::raw('LENGTH(name), name'))->get();
            } else {
                $carpetas = archivo_carpeta::with('couldHaveTraslado', 'couldHaveCancelacion', 'hasEstado', 'hasClase', 'hasServicio')->where('name', 'like', $txtCarpeta.'%')->orderBy(\DB::raw('LENGTH(name), name'))->get();
            }
        } elseif ($criterioBusqueda == 'nombre') {
            $carpetas = archivo_carpeta::with('couldHaveTraslado', 'couldHaveCancelacion', 'hasEstado', 'hasClase', 'hasServicio')->where('name', $txtCarpeta)->get();
        }

        if ($carpetas != null) {
            return view('admin.archivo.listadoCarpetasBusqueda', ['carpetas' => $carpetas])->render();
        } else {
            return null;
        }
    }

    public function obtenerHistorialCarpeta($idCarpeta)
    {
        $historial_carpeta = archivo_solicitud::with('hasCarpetaPrestada', 'hasTramiteServicio', 'hasTramiteServicio.hasFuncionario', 'hasCarpetaPrestada.hasFuncionarioAutoriza', 'hasCarpetaPrestada.hasFuncionarioEntrega', 'hasCarpetaPrestada.hasFuncionarioRecibe')->whereHas('hasCarpetaPrestada', function($query) use ($idCarpeta){
            $query->archivo_carpeta_id = $idCarpeta;
        })->get();
        if ($historial_carpeta->count() > 0) {
            return view('admin.archivo.historialCarpeta', [
                'historiales' => $historial_carpeta
            ])->render();
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carpeta especificada no cuenta con datos de historial.'],
                'encabezado' => 'Sin información',
            ], 200);
        }
    }

    public function obtenerTrasladoCarpeta($idCarpeta)
    {
        $changearchivo_carpeta_traslado = archivo_carpeta_traslado::where('carpeta_id', $idCarpeta)->get()->first();
        if ($changearchivo_carpeta_traslado != null) {
            return view('admin.archivo.verTraslado', ['traslado' => $changearchivo_carpeta_traslado])->render();
        } else {
            echo null;
        }
    }

    public function obtenerCancelacionCarpeta($idCarpeta)
    {
        $archivo_cancelacion_carpeta = archivo_carpeta_cancelacion::where('archivo_carpeta_id', $idCarpeta)->get()->first();
        if ($archivo_cancelacion_carpeta != null) {
            return view('admin.archivo.verCancelacion', ['cancelacion' => $archivo_cancelacion_carpeta])->render();
        } else {
            return null;
        }
    }

    public function revertirTrasladoCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCarpetaRevertirTraslado' => 'required|integer|exists:archivo_carpeta,id',
            'revertirEstado' => 'required|integer|exists:archivo_carpeta_estado,id',
        ], [
            'idCarpetaRevertirTraslado.required' => 'No se ha expecificado una carpeta para revertir el traslado.',
            'idCarpetaRevertirTraslado.integer' => 'El ID de la carpeta a trasladar no tiene un formato válido.',
            'idCarpetaRevertirTraslado.exists' => 'El ID de la carpeta especificada no existe en la base de datos.',
            'revertirEstado.integer' => 'El ID del nuevo estado no tiene un formato válido.',
            'revertirEstado.exists' => 'El nuevo estado especificado no existe en la base de datos.',
            'revertirEstado.required' => 'No se especificado el nuevo estado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $changearchivo_carpeta_traslado = archivo_carpeta_traslado::where('carpeta_id', $request->idCarpetaRevertirTraslado)->get()->first();
            $estado = archivo_carpeta_estado::find($request->revertirEstado);
            if ($changearchivo_carpeta_traslado != null) {
                $changearchivo_carpeta_traslado->delete();
                $carpeta = archivo_carpeta::find($request->idCarpetaRevertirTraslado);
                $carpeta->archivo_carpeta_estado_id = $estado->id;
                $carpeta->available = $estado->estado_carpeta;
                $carpeta->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha revertio el traslado de la carpeta.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado un registro de traslado de la carpeta.'],
                    'encabezado' => 'Error en la eliminación',
                ], 200);
            }
        }
    }

    public function revertirCancelacionCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCarpetaRevertirCancelacion' => 'required|integer|exists:archivo_carpeta,id',
            'revertirEstado' => 'required|integer|exists:archivo_carpeta_estado,id',
        ], [
            'idCarpetaRevertirCancelacion.required' => 'No se ha expecificado una carpeta para revertir el traslado.',
            'idCarpetaRevertirCancelacion.integer' => 'El ID de la carpeta a trasladar no tiene un formato válido.',
            'idCarpetaRevertirCancelacion.exists' => 'El ID de la carpeta especificada no existe en la base de datos.',
            'revertirEstado.integer' => 'El ID del nuevo estado no tiene un formato válido.',
            'revertirEstado.exists' => 'El nuevo estado especificado no existe en la base de datos.',
            'revertirEstado.required' => 'No se especificado el nuevo estado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $archivo_cancelacion_carpeta = archivo_carpeta_cancelacion::where('archivo_carpeta_id', $request->idCarpetaRevertirCancelacion)->get()->first();
            $estado = archivo_carpeta_estado::find($request->revertirEstado);
            if ($archivo_cancelacion_carpeta != null) {
                $archivo_cancelacion_carpeta->delete();
                $carpeta = archivo_carpeta::find($request->idCarpetaRevertirCancelacion);
                $carpeta->archivo_carpeta_estado_id = $estado->id;
                $carpeta->available = $estado->estado_carpeta;
                $carpeta->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha revertio el cancelacion de la carpeta.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado un registro de cancelacion de la carpeta.'],
                    'encabezado' => 'Error en la eliminación',
                ], 200);
            }
        }
    }

    public function cambiarEstadoCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carpetaId' => 'required|integer|exists:archivo_carpeta,id',
            'estadoCarpetaId' => 'integer|exists:archivo_carpeta_estado,id',
        ], [
            'carpetaId.required' => 'No se ha especificado una carpeta.',
            'carpetaId.integer' => 'El ID de la carpeta especificada no es válido.',
            'carpetaId.exists' => 'La carpeta especificada no existe en la base de datos.',
            'estadoCarpetaId.required' => 'No se ha especificado un estado.',
            'estadoCarpetaId.integer' => 'El ID del estado especificado no tiene un formato válido.',
            'estadoCarpetaId.exists' => 'El estado especificado no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $carpeta = archivo_carpeta::find($request->carpetaId);
            $estado_carpeta = archivo_carpeta_estado::find($request->estadoCarpetaId);
            $carpeta->archivo_carpeta_estado_id = $estado_carpeta->id;
            $carpeta->available = $estado_carpeta->estado_carpeta;
            if ($carpeta->hasSolicitudPendiente() != null) {//si la carpeta se encuentra actualmente con una solicitud en tramite, no se le cambia el estado actual de la carpeta
                $carpeta = null;
                $estado_carpeta = null;

                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede cambiar el estado de la carpeta: está en un trámite.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                if ($carpeta->save()) {
                    $carpeta = null;
                    $estado_carpeta = null;

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha realizado el cambio de estado de la carpeta.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    $carpeta = null;
                    $estado_carpeta = null;

                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido cambiar el estado de la carpeta.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }
            }
        }
    }

    public function crearMultiplesCarpetas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txtLetras' => 'required|string|max:3|min:3',
            'rangoInicial' => 'numeric|required|max:998|min:1',
            'rangoFinal' => 'numeric|required|max:999|min:1',
            'letraTerminacion' => 'string|max:1|exists:vehiculo_clase_letra_terminacion,name',
            'claseVehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'estadoCarpeta' => 'required|integer|exists:archivo_carpeta_estado,id',
        ], [
            'txtLetras.required' => 'No se ha especificado las letras de la serie.',
            'rangoInicial.required' => 'No se ha especificado el rango inicial.',
            'rangoFinal.required' => 'No se ha especificado el rango final.',
            'claseVehiculo.required' => 'No se ha especificado la clase del vehiculo.',
            'estadoCarpeta.required' => 'No se ha especificado el estado de la carpeta.',
            'claseVehiculo.integer' => 'El ID de la clase de vehículo especificada no tiene un formato válido.',
            'estadoCarpeta.integer' => 'El ID del estado de carpeta no tiene un formato válido.',
            'txtLetras.max' => 'El límite máximo de unidades para las letras de la serie son :max.',
            'rangoInicial.max' => 'El límite máximo de unidades para el rango inicial es :max.',
            'rangoFinal.max' => 'El límite máximo de unidades para el rango final es :max.',
            'letraTerminacion.max' => 'El límite máximo de unidades para la letra de terminación es :max.',
            'txtLetras.min' => 'El límite mínimo de unidades para las letras de la serie son :min.',
            'rangoInicial.min' => 'El límite mínimo de unidades para el rango inicial es :min.',
            'rangoFinal.min' => 'El límite mínimo de unidades para el rango final es :min.',
            'letraTerminacion.min' => 'El límite mínimo de unidades para la letra de terminación es :min.',
            'txtLetras.string' => 'El formato de las letras de serie no es válido.',
            'letraTerminacion.string' => 'El formato de la letra de terminación no es válido.',
            'rangoInicial.numeric' => 'El formato de rango inicial no es válido.',
            'rangoFinal.numeric' => 'El formato de rango final no es válido.',
            'letraTerminacion.exists' => 'La letra de terminación especificada no está asignada a una clase de vehículo.',
            'claseVehiculo.exists' => 'La clase de vehículo especificada no existe en la base de datos.',
            'estadoCarpeta.exists' => 'El estado de carpeta especificado no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $claseVehiculo = vehiculo_clase::find($request->claseVehiculo);
            $estado = archivo_carpeta_estado::find($request->estadoCarpeta);
            if ($request->rangoInicial < $request->rangoFinal) {
                if ($request->rangoFinal < 100 && $claseVehiculo->required_letter == 'yes' && $request->letraTerminacion != null) {
                    for ($i = $request->rangoInicial; $i <= $request->rangoFinal; $i++) {
                        $numero = '';
                        if ($i < 10) {
                            $numero = '0'.$i;
                        } else {
                            $numero = $i;
                        }
                        archivo_carpeta::firstOrCreate([
                            'name' => strtoupper($request->txtLetras.$numero.$request->letraTerminacion),
                            'available' => $estado->estado_carpeta,
                            'archivo_carpeta_estado_id' => $estado->id,
                            'vehiculo_clase_id' => $request->claseVehiculo,
                            'vehiculo_servicio_id' => $request->servicioVehiculo,
                        ]);
                    }

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha realizado el ingreso del rango.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } elseif ($claseVehiculo->required_letter == 'yes' && $request->letraTerminacion != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha especificado una letra de terminación.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                } elseif ($claseVehiculo->required_letter == 'yes') {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El rango excede el máximo permitido para esta clase de vehículo.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                } else {
                    for ($i = $request->rangoInicial; $i <= $request->rangoFinal; $i++) {
                        $numero = '';
                        if ($i < 10) {
                            $numero = '00'.$i;
                        } elseif ($i < 100) {
                            $numero = '0'.$i;
                        } else {
                            $numero = $i;
                        }
                        archivo_carpeta::firstOrCreate([
                            'name' => strtoupper($request->txtLetras.$numero),
                            'available' => $estado->estado_carpeta,
                            'archivo_carpeta_estado_id' => $estado->id,
                            'vehiculo_clase_id' => $request->claseVehiculo,
                            'vehiculo_servicio_id' => $request->servicioVehiculo,
                        ]);
                    }

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha realizado el ingreso del rango.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El rango inicial debe ser menor al rango final.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function crearCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placa' => 'required|string|max:8|min:6',
            'claseVehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'estadoCarpeta' => 'required|integer|exists:archivo_carpeta_estado,id',
            'servicioCarpeta' => 'required|integer|exists:vehiculo_servicio,id',
        ], [
            'placa.required' => 'No se ha especificado el nombre de la placa.',
            'claseVehiculo.required' => 'No se ha especificado la clase del vehículo.',
            'estadoCarpeta.required' => 'No se ha especificado el estado de la carpeta.',
            'claseVehiculo.integer' => 'El ID de la clase de vehículo especificado no tiene un formato válido.',
            'estadoCarpeta.integer' => 'El ID del estado especificado no tiene un formato válido.',
            'placa.max' => 'La placa tiene un límite máximo de :max caracteres',
            'placa.min' => 'La placa tiene un límite mínimo de :min caracteres',
            'claseVehiculo.exists' => 'La clase de vehículo especificada no existe en la base de datos.',
            'estadoCarpeta.exists' => 'El estado especificado no existe en la base de datos.',
            'placa.string' => 'El formato de placa no tiene un formato válido.',
            'servicioVehiculo.required' => 'No se ha especificado el servicio del vehículo.',
            'servicioVehiculo.integer' => 'El ID del servicio de vehículo especificado no tiene un formato válido.',
            'servicioVehiculo.exists' => 'El servicio de vehículo especificada no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $carpeta = archivo_carpeta::firstOrCreate([
                    'name' => strtoupper($request->placa),
                    'available' => archivo_carpeta_estado::select('estado_carpeta')->where('id', $request->estadoCarpeta)->first()->estado_carpeta,
                    'archivo_carpeta_estado_id' => $request->estadoCarpeta,
                    'vehiculo_clase_id' => $request->claseVehiculo,
                    'vehiculo_servicio_id' => $request->servicioVehiculo,
                ]);
                if ($carpeta != null) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado la carpeta.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear la carpeta.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear la carpeta.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function exportarHistorialCarpeta($idCarpeta)
    {
        return Excel::download(new HistorialCarpeta($idCarpeta), 'HistorialCarpeta.xlsx');
    }

    public function realizarImportacionRegistros(Request $request)
    {
        try{
            //Excel::import(new ArchivoCarpetaImport(), $request->file('registros'));
            $import = new ArchivoCarpetaImport();
            $import->import($request->file('registros'));
            if(count($import->errors()) > 0){
                return response()->view('admin.mensajes.errors', [
                    'errors' => $import->errors(),
                    'encabezado' => 'Importación realizada pero con errores.',
                ], 200);
            }else{
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han importado los registros.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso.'],
                'encabezado' => 'Error en la importación',
            ], 200);
        }
        /*
        $validator = Validator::make($request->all(), [
            'registros' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel|required',
        ], [
            'required' => 'No se ha suministrado un archivo de registros.',
            'mimetypes' => 'El archivo de importación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            /*$ruta_archivo = 'registrosImportados-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
            Storage::disk('imports')->putFileAs('archivo', $request->file('registros'), $ruta_archivo);*/

            /*Excel::filter('chunk')->load(storage_path('app/imports/archivo/'.$ruta_archivo))->chunk(100, function (
                $results
            ) {
                foreach ($results as $registro) {
                    if ($registro->placa == null || $registro->estado == null || $registro->clase == null || ! is_numeric($registro->estado) || ! is_numeric($registro->clase)) {
                        //nothing
                    } else {
                        $estado = archivo_carpeta_estado::find($registro->estado);
                        archivo_carpeta::findOrCreate([
                            'name' => $registro->placa,
                            'available' => $estado->estado_carpeta,
                            'estado_id' => $registro->estado,
                            'vehiculo_clase_id' => $registro->clase,
                        ]);
                    }
                }
            });*/
            /*try{
                Excel::import(new ArchivoCarpetaImport(), $request->file('registros'));
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han importado los registros.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso.'],
                    'encabezado' => 'Error en la importación',
                ], 200);
            }
        }*/
    }

    public function realizarCancelacionCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carpetaCancelacion' => 'integer|required|exists:archivo_Carpeta,id',
            'fecha_cancelacion_submit' => 'date|required',
            'nro_certificado_runt' => 'required|string',
            'nombre_funcionario' => 'string|required',
            'motivo_cancelacion' => 'integer|required|exists:archivo_carpeta_ca_mo,id',
        ], [
            'carpetaCancelacion.required' => 'No se ha especificado una carpeta.',
            'carpetaCancelacion.integer' => 'El ID de la carpeta no tiene un formato válido.',
            'carpetaCancelacion.exists' => 'La carpeta especificada no existe en la base de datos.',
            'fecha_cancelacion_submit.required' => 'No se ha especificado la fecha de cancelación.',
            'fecha_cancelacion_submit.date' => 'La fecha proporcionada no tiene un formato válido.',
            'nro_certificado_runt.required' => 'No se ha especificado el número de certificado de RUNT.',
            'nro_certificado_runt.string' => 'El número de certificado RUNT especificado no tiene un formato válido.',
            'nombre_funcionario.required' => 'No se ha especificado el nombre del funcionario que autorizó la cancelación.',
            'nombre_funcionario.string' => 'El nombre del funcionario especificado no tiene un formato válido.',
            'motivo_cancelacion.required' => 'No se ha especificado el motivo de la cancelación.',
            'motivo_cancelacion.integer' => 'El ID del motivo de cancelación especificado no tiene un formato válido.',
            'motivo_cancelacion.exists' => 'El motivo de cancelación especificado no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $estado = archivo_carpeta_estado::where('name', 'CANCELADA')->get()->first();//obtenemos el objeto que representa el estado "TRASLADADA" para obtener su id
            $carpeta = archivo_carpeta::with('couldHaveTraslado', 'couldHaveCancelacion')->find($request->carpetaCancelacion);
            $carpetaOld = $carpeta;
            if ($carpeta->hasSolicitudPendiente() == null) {
                if ($carpeta->couldHaveTraslado != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La carpeta ya tiene un registro de traslado.'],
                        'encabezado' => 'Error en el traslado',
                    ], 200);
                } elseif ($carpeta->couldHaveCancelacion != null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La carpeta ya tiene un registro de cancelada.'],
                        'encabezado' => 'Error en el traslado',
                    ], 200);
                } else {
                    $carpeta->available = $estado->estado_carpeta;//disponibilidad por defecto de acuerdo al estado que se le asigna
                    $carpeta->archivo_carpeta_estado_id = $estado->id;
                    if ($carpeta->save()) {
                        $cancelado = new archivo_carpeta_cancelacion();
                        $cancelado->fecha_cancelacion = $request->fecha_cancelacion_submit;
                        $cancelado->nro_certificado_runt = $request->nro_certificado_runt;
                        $cancelado->nombre_funcionario_autoriza = $request->nombre_funcionario;
                        $cancelado->motivo_id = $request->motivo_cancelacion;
                        $cancelado->archivo_carpeta_id = $carpeta->id;
                        if ($cancelado->save()) {
                            return response()->view('admin.mensajes.success', [
                                'mensaje' => 'Se ha registrado la cancelación de la carpeta.',
                                'encabezado' => '¡Completado!',
                            ], 200);
                        } else {
                            $carpetaOld->save();

                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                                'encabezado' => 'Error en el traslado',
                            ], 200);
                        }
                    } else {
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['No se ha podido realizar los cambios. Por favor contacte a un administrador.'],
                            'encabezado' => 'Error en el traslado',
                        ], 200);
                    }
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La carpeta esta siendo usada en un trámite o su estado actual no permite modificaciones.'],
                    'encabezado' => 'Error en el traslado',
                ], 200);
            }
        }
    }

    public function multipleEliminacionCarpeta(Request $request)
    {
        if (count($request->data) > 0) {
            foreach ($request->data as $carpeta) {
                $carpeta = archivo_carpeta::find($carpeta);
                if ($carpeta->hasSolicitudPendiente() == null) {
                    $carpeta->delete();
                }
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se han eliminado las carpetas especificadas.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La información suministrada no es válida.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function multipleCambioEstadoCarpetaF1()
    {
        $estadosCarpetas = archivo_carpeta_estado::pluck('name','id');
        return view('admin.archivo.multipleCambioEstado', ['estadosCarpetas'=>$estadosCarpetas])->render();
    }

    public function multipleCambioEstadoCarpetaF2(Request $request)
    {
        if (count($request->data) > 0) {
            foreach ($request->data as $carpeta) {
                $carpeta = archivo_carpeta::find($carpeta);
                if ($carpeta->hasSolicitudPendiente() == null) {
                    $carpeta->archivo_carpeta_estado_id = $request->id;
                    $carpeta->save();
                }
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha cambiado el estado de las carpetas especificadas.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La información suministrada no es válida.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function multipleCambioClaseCarpetaF1()
    {
        $clasesVehiculos = vehiculo_clase::pluck('name','id');
        return view('admin.archivo.multipleCambioClaseCarpeta', ['clasesVehiculos'=>$clasesVehiculos])->render();
    }

    public function multipleCambioClaseCarpetaF2(Request $request)
    {
        if (count($request->data) > 0) {
            foreach ($request->data as $carpeta) {
                $carpeta = archivo_carpeta::find($carpeta);
                $carpeta->vehiculo_clase_id = $request->id;
                $carpeta->save();
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha cambiado la clase de vehículo a las carpetas especificadas.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La información suministrada no es válida.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function obtenerMotivosCancelacion()
    {
        $motivos = archivo_carpeta_ca_mo::paginate(15);

        return view('admin.archivo.listadoMotivosCancelacion', ['motivos' => $motivos])->render();
    }

    public function nuevoMotivoCancelacion()
    {
        return view('admin.archivo.crearMotivoCancelacion')->render();
    }

    public function crearMotivoCancelacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_motivo' => 'required|string|unique:archivo_carpeta_ca_mo,name'
        ], [
            'name_motivo.required' => 'No se ha especificado el nombre.',
            'name_motivo.string' => 'El nombre especificado no tiene un formato válido.',
            'name_motivo.unique' => 'El nobmre especificado y está siendo usado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            archivo_carpeta_ca_mo::create([
                'name' => strtoupper($request->name_motivo)
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el motivo cancelación.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el motivo cancelación.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function editarMotivoCancelacion($id)
    {
        $motivo = archivo_carpeta_ca_mo::find($id);

        return view('admin.archivo.editarMotivoCancelacion', ['motivo' => $motivo])->render();
    }

    public function actualizarMotivoCancelacion(Request $request)
    {
        $motivo = archivo_carpeta_ca_mo::find($request->id_motivo);
        $motivo->name = strtoupper($request->name_motivo);
        if ($motivo->save()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el motivo cancelación especificado.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el motivo cancelación.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function obtenerEstadosCarpeta()
    {
        $estados = archivo_carpeta_estado::paginate(25);

        return view('admin.archivo.listadoEstadosCarpeta', ['estados' => $estados])->render();
    }

    public function nuevoEstadoCarpeta()
    {
        return view('admin.archivo.crearEstadoCarpeta')->render();
    }

    public function crearEstadoCarpeta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_estado' => 'required|string|unique:archivo_carpeta_estado,name',
            'estado_carpeta' => ['required', Rule::in(['SI','NO'])]
        ], [
            'name_estado.required' => 'No se ha especificado el nombre.',
            'name_estado.string' => 'El nombre especificado no tiene un formato válido.',
            'name_estado.unique' => 'El nobmre especificado y está siendo usado.',
            'estado_carpeta.required' => 'No se ha especificado el estado de la carpeta.',
            'estado_carpeta.in' => 'El valor proporcionado para el campo "Estado carpeta" no es el esperado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            archivo_carpeta_estado::create([
                'name' => strtoupper($request->name_estado),
                'estado_carpeta' => $request->estado_carpeta
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el estado carpeta.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el estado carpeta.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function editarEstadoCarpeta($id)
    {
        $estado = archivo_carpeta_estado::find($id);

        return view('admin.archivo.editarEstadoCarpeta', ['estado' => $estado])->render();
    }

    public function actualizarEstadoCarpeta(Request $request)
    {
        $estado = archivo_carpeta_estado::find($request->id_estado);
        $estado->name = strtoupper($request->name_estado);
        $estado->estado_carpeta = $request->estado_carpeta;
        if ($estado->save()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el estado de carpeta especificado.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el estado de carpeta.'],
                'encabezado' => 'Error en la operación',
            ], 200);
        }
    }

    public function trasladarCarpeta($id)
    {
        $departamentos = departamento::pluck('name', 'id');

        return view('admin.archivo.trasladarCarpeta', ['id' => $id, 'departamentos' => $departamentos])->render();
    }

    public function revertirTrasladoDeCarpeta($id)
    {
        $estadosCarpetas = archivo_carpeta_estado::pluck('name', 'id');

        return view('admin.archivo.revertirTrasladoCarpeta', [
            'id' => $id,
            'estadosCarpetas' => $estadosCarpetas,
        ])->render();
    }

    public function cancelarCarpeta($id)
    {
        $motivosCancelacion = archivo_carpeta_ca_mo::pluck('name', 'id');

        return view('admin.archivo.cancelarMatriculaCarpeta', [
            'id' => $id,
            'motivosCancelacion' => $motivosCancelacion,
        ])->render();
    }

    public function revertirCancelacionDeCarpeta($id)
    {
        $estadosCarpetas = archivo_carpeta_estado::pluck('name', 'id');

        return view('admin.archivo.revertirCancelacion', [
            'id' => $id,
            'estadosCarpetas' => $estadosCarpetas,
        ])->render();
    }

    public function cambiarEstadoDeCarpeta($id)
    {
        $estadosCarpetas = archivo_carpeta_estado::pluck('name', 'id');
        $carpeta = archivo_carpeta::find($id);

        return view('admin.archivo.cambiarEstadoCarpeta', [
            'carpeta' => $carpeta,
            'estadosCarpetas' => $estadosCarpetas,
        ])->render();
    }

    public function importarRegistros()
    {
        return view('admin.archivo.importarRegistros')->render();
    }

    public function nuevaCarpeta()
    {
        $clasesVehiculos = vehiculo_clase::pluck('name', 'id');
        $serviciosVehiculos = vehiculo_servicio::pluck('name', 'id');
        $estadosCarpetas = archivo_carpeta_estado::pluck('name', 'id');

        return view('admin.archivo.nuevaCarpeta', [
            'clasesVehiculos' => $clasesVehiculos,
            'serviciosVehiculos' => $serviciosVehiculos,
            'estadosCarpetas' => $estadosCarpetas,
        ])->render();
    }

    public function ingresarMultiplesCarpetas()
    {
        $clasesVehiculos = vehiculo_clase::pluck('name', 'id');
        $serviciosVehiculos = vehiculo_servicio::pluck('name', 'id');
        $estadosCarpetas = archivo_carpeta_estado::pluck('name', 'id');

        return view('admin.archivo.crearMultiplesCarpetas', [
            'clasesVehiculos' => $clasesVehiculos,
            'serviciosVehiculos' => $serviciosVehiculos,
            'estadosCarpetas' => $estadosCarpetas,
        ])->render();
    }

    public function verSolicitudPendiente($id)
    {
        $archivo_carpeta = archivo_carpeta::find($id);
        $solicitudPendiente = $archivo_carpeta->hasSolicitudPendiente();
        if ($solicitudPendiente != null) {
            return view('admin.archivo.verSolicitudPendiente', ['solicitud' => $solicitudPendiente])->render();
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carpeta '.$archivo_carpeta->name.' no tiene actualmente una solicitud pendiente.'],
                'encabezado' => 'Sin resultado',
            ], 200);
        }
    }
}
