<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\notificacion_aviso;
use App\gd_pqr;
use App\Notifications\nuevaSolicitudPreAsignacion;
use App\solicitud_preasignacion;
use App\tramite_solicitud_turno;
use App\User;
use App\usuario_tipo_documento;
use App\vehiculo;
use App\vehiculo_base_gravable;
use App\vehiculo_clase;
use App\empresa_transporte;
use App\vehiculo_liquidacion;
use App\vehiculo_liquidacion_descuento;
use App\vehiculo_liquidacion_vigencia;
use App\vehiculo_marca;
use App\vehiculo_nivel_servicio;
use App\vehiculo_propietario;
use App\vehiculo_radio_operacion;
use App\vehiculo_carroceria;
use App\tarjeta_operacion;
use App\CoactivoComparendo;
use App\vehiculo_servicio;
use Maatwebsite\Excel\Facades\Excel;
use App\CoactivoFotoMultas;
use App\normativa;
use PDF;
use PHPExcel_Worksheet_Drawing;
use Pusher\Pusher;
use Validator;

class ConsultasController extends Controller
{
    public function to_index()
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::pluck('name','id');
        return view('publico.tos.consultarto', ['tipocriterio' => null,'tiposDocumentosIdentidad'=>$tiposDocumentosIdentidad]);
    }
    /*
     * Recibe dos parámetos: placa o código
     *
     * Si recibe la placa se redirecciona a otra vista en donde se mostraran todas las tarjetas que han sido expedidas a esa placa y el usuario podrá descargar cualquiera de las que se enlisten.
     * De lo contrario si especifica un código se procederá a generar inmediatamente el pdf de esa TO y se descargará.
     */
    public function consultarTo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipo' => 'string|required',
            'numero' => 'string|required',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'tipo.string' => 'El formato de criterio de búsqueda no es válido.',
            'tipo.required' => 'No se ha especificado el criterio de búsqueda.',
            'numero.string' => 'El formato de número o placa no es válido.',
            'numero.required' => 'No se ha especificado el número o placa.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->all());
        }else{
            if ($request->tipo == 'Placa') {
                $tos = tarjeta_operacion::with('hasEmpresaTransporte', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->where('placa', $request->numero)->orderBy('created_at', 'desc')->get();

                return view('publico.tos.consultarto', [
                    'tos' => $tos,
                    'parametro' => $request->numero,
                    'tipocriterio' => $request->tipo
                ]);
            } elseif ($request->tipo == 'Codigo') {
                $to = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->where('id', $request->numero)->first();

                if($to == null){
                    return back()->withErrors(['Los datos suministrados no existen o no están relacionados.'])->withInput($request->all());
                }

                return view('publico.tos.consultarto', [
                    'tos' => [$to],
                    'parametro' => $request->numero,
                    'tipocriterio' => $request->tipo
                ]);
            } else {
                return back()->withErrors(['error' => 'No se seleccionó un criterio de búsqueda válido.']);
            }
        }
    }

    /*
     * @param id = codigo de la TO a imprimir. Este parámetro viene de la vista publico.consultarto
     */
    public function imprimirTo($id)
    {
        $to = tarjeta_operacion::with('hasTipoVehiculo', 'hasTipoCarroceria', 'hasEmpresaTransporte', 'hasClaseCombustible', 'hasMarca', 'hasNivelServicio', 'hasRadioOperacion')->where('id', $id)->get();

        // Generamos el PDF
        return $this->generarPDF($to);
    }

    private function generarPDF(Collection $to)
    {
        /*
         * Configuramos las fechas para renderizarlas en la plantilla blade
         */
        $fecha = explode("-", $to[0]['fecha_vencimiento']);
        /*
         * Enviamos la TO a la vista y renderizamos el pdf
         */
        $pdf = PDF::loadView('publico.tos.imprimirTo', [
            'to' => $to,
            'dia' => $fecha[2],
            'mes' => $fecha[1],
            'año' => $fecha[0],
        ])->setOption('no-outline', true)->setOption('margin-bottom', 0)->setOption('margin-left', 1)->setOption('margin-right', 1)->setOption('margin-top', 1)->setOption('page-width', 85)->setOption('page-height', 51)->setOption('enable-smart-shrinking', true);

        //return view('publico.consultas.to', ['to'=>$to, 'dia'=>$fecha[2], 'mes'=>$fecha[1], 'año'=>$fecha[0]]);

        return $pdf->download('tarjeta_operacion_'.$to[0]['placa'].'_'.$to[0]['id'].'_copia_no_controlada.pdf');
    }

    public function consultarNotificacionesComparendos(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipoCriterio' => 'string|required',
            'criterio' => 'string|required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'tipoCriterio.string' => 'El formato de criterio de búsqueda no es válido.',
            'tipoCriterio.required' => 'No se ha especificado el criterio de búsqueda.',
            'criterio.string' => 'El formato del nombre o número de documento no es válido.',
            'criterio.required' => 'No se ha especificado el nombre o número de documento.'
        ]);

        if ($validator->fails()) {
            $request->flashOnly(['tipo_documento', 'numero_documento', 'radicado']);
            return back()->withErrors($validator->errors()->all());
        }else{
            if ($request->tipoCriterio == 'cc') {
                $edictosNotificados = CoactivoComparendo::where('cc', '=', $request->criterio)->orderBy('publication_date', 'desc')->paginate(15);

                return view('publico.edictos.comparendos.consultarNotificados', [
                    'edictos' => $edictosNotificados,
                    'parametro' => $request->criterio,
                ]);
            } elseif ($request->tipoCriterio == 'name') {
                $edictosNotificados = CoactivoComparendo::where('name', 'like', '%'.$request->criterio.'%')->orderBy('publication_date', 'desc')->paginate(15);

                return view('publico.edictos.comparendos.consultarNotificados', [
                    'edictos' => $edictosNotificados,
                    'parametro' => $request->criterio,
                ]);
            } else {
                return redirect()->to('back')->withErrors(['No ha especificado un criterio de búsqueda válido.']);
            }
        }
    }

    public function exportarNotificacionesComparendos($parametro)
    {
        if (is_numeric($parametro)) {
            $edictosNotificados = CoactivoComparendo::where('cc', '=', $parametro)->orderBy('created_at', 'desc')->get();
        } else {
            $edictosNotificados = CoactivoComparendo::where('name', 'like', '%'.$parametro.'%')->orderBy('publication_date', 'desc')->get();
        }

        $pdf = PDF::loadView('publico.edictos.comparendos.historialEdictosNotificados', [
            'edictos' => $edictosNotificados,
            'documento' => 'COMPARENDOS',
            'parametro' => $parametro,
        ])->setPaper('letter')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-right', 0)->setOption('margin-left', 0);

        return $pdf->download('Edictos notificados a '.$parametro.'.pdf');
    }

    public function consultarNotificacionesFotoMultas(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipoCriterio' => 'string|required',
            'criterio' => 'string|required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'tipoCriterio.string' => 'El formato de criterio de búsqueda no es válido.',
            'tipoCriterio.required' => 'No se ha especificado el criterio de búsqueda.',
            'criterio.string' => 'El formato del nombre o número de documento no es válido.',
            'criterio.required' => 'No se ha especificado el nombre o número de documento.'
        ]);

        if ($validator->fails()) {
            $request->flashOnly(['tipo_documento', 'numero_documento', 'radicado']);
            return back()->withErrors($validator->errors()->all());
        }else{
            if ($request->tipoCriterio == 'cc') {
                $fotoMultasNotificados = CoactivoFotoMultas::where('cc', '=', $request->criterio)->orderBy('publication_date', 'desc')->paginate(15);

                return view('publico.edictos.FotoMultas.consultarNotificados', [
                    'fotoMultas' => $fotoMultasNotificados,
                    'parametro' => $request->criterio,
                    'tipocriterio' => $request->tipoCriterio,
                ]);
            } elseif ($request->tipoCriterio == 'name') {
                $fotoMultasNotificados = CoactivoFotoMultas::where('name', 'like', '%'.$request->criterio.'%')->orderBy('publication_date', 'desc')->paginate(15);

                return view('publico.edictos.FotoMultas.consultarNotificados', [
                    'fotoMultas' => $fotoMultasNotificados,
                    'parametro' => $request->criterio,
                    'tipocriterio' => $request->tipoCriterio,
                ]);
            } else {
                return redirect()->to('back')->withErrors(['No ha especificado un criterio de búsqueda válido.']);
            }
        }
    }

    public function exportarNotificacionesFotoMultas($parametro)
    {
        if (is_numeric($parametro)) {
            $fotoMultasNotificados = CoactivoFotoMultas::where('cc', '=', $parametro)->orderBy('created_at', 'desc')->get();
        } else {
            $fotoMultasNotificados = CoactivoFotoMultas::where('name', 'like', '%'.$parametro.'%')->orderBy('publication_date', 'desc')->get();
        }

        $pdf = PDF::loadView('publico.edictos.comparendos.historialEdictosNotificados', [
            'edictos' => $fotoMultasNotificados,
            'documento' => 'FOTO MULTAS',
            'parametro' => $parametro,
        ])->setPaper('letter')->setOption('margin-bottom', 0)->setOption('margin-top', 0)->setOption('margin-right', 0)->setOption('margin-left', 0);

        return $pdf->download('Edictos notificados a '.$parametro.'.pdf');
    }

    public function consultarEstadoPQR()
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::orderBy('name', 'asc')->pluck('name', 'id');

        return view('publico.pqr.consultarEstado', ['tiposDocumentosIdentidad' => $tiposDocumentosIdentidad]);
    }

    public function consultarProcesoPQR(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipo_documento' => 'integer|exists:usuario_tipo_documento,id|required',
            'numero_documento' => 'numeric|required',
            'radicado' => 'string|required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'tipo_documento.integer' => 'El tipo de documento de identificación no tiene un formato válido.',
            'tipo_documento.exists' => 'El ID de documento de identificación especificado no existe en el sistema.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identidad.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad.',
            'radicado.string' => 'El radicado de entrada especificado no tiene un formato válido.',
            'radicado.required' => 'No se ha especificado el radicado de entrada.',
        ]);

        if ($validator->fails()) {
            $request->flashOnly(['tipo_documento', 'numero_documento', 'radicado']);
            return back()->withErrors($validator->errors()->all());
        } else {
            $radicado = explode('-', $request->radicado);
            $numero_documento = $request->numero_documento;
            $tipo_documento = $request->tipo_documento;
            if (is_array($radicado) && count($radicado) == 4) {
                $radicado = $request->radicado;
                $pqr = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas','hasAnulacion')->where('tipo_pqr', 'CoEx')->whereHas('getRadicadoEntrada', function (
                        $query
                    ) use ($radicado) {
                        $query->where('numero', $radicado);
                    })->whereHas('hasPeticionario', function ($query) use ($numero_documento, $tipo_documento) {
                        $query->where('numero_documento', $numero_documento)->where('tipo_documento_id', $tipo_documento);
                    })->first();
                if ($pqr != null) {
                    return view('publico.pqr.estadoPQR', ['pqr' => $pqr])->render();
                } else {
                    return back()->withErrors(['No se ha encontrado proceso alguno con los datos suministrados']);
                }
            } else {
                return back()->withErrors(['El radicado suministrado no tiene un formato válido. Por favor digite nuevamente el radicado con el siguiente formato: '.\anlutro\LaravelSettings\Facade::get('empresa-sigla').'-AÑO-100-NUMERO RADICADO']);
            }
        }
    }

    public function consultarNotificacionesAviso(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipoCriterio' => 'string|required',
            'criterio' => 'string|required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'tipoCriterio.string' => 'El formato de criterio de búsqueda no es válido.',
            'tipoCriterio.required' => 'No se ha especificado el criterio de búsqueda.',
            'criterio.string' => 'El formato del nombre o número de documento no es válido.',
            'criterio.required' => 'No se ha especificado el nombre o número de documento.'
        ]);

        if ($validator->fails()) {
            $request->flashOnly(['tipo_documento', 'numero_documento', 'radicado']);
            return back()->withErrors($validator->errors()->all());
        }else{
            if ($request->tipoCriterio == 'cc') {
                $notificacionAviso = notificacion_aviso::with('hasTipoNotificacion')->where('numero_documento', '=', $request->criterio)->orderBy('fecha_publicacion', 'desc')->get();

                return view('publico.notificacionesAviso.consultarNotificacionesAviso', [
                    'notificacionesAviso' => $notificacionAviso,
                    'parametro' => $request->criterio,
                ]);
            } elseif ($request->tipoCriterio == 'name') {
                $notificacionAviso = notificacion_aviso::with('hasTipoNotificacion')->where('nombre_notificado', 'like', '%'.$request->criterio.'%')->orderBy('fecha_publicacion', 'desc')->get();

                return view('publico.notificacionesAviso.consultarNotificacionesAviso', [
                    'notificacionesAviso' => $notificacionAviso,
                    'parametro' => $request->criterio,
                ]);
            } else {
                return redirect()->to('back')->withErrors(['No ha especificado un criterio de búsqueda válido.']);
            }
        }
    }

    public function getDocumentoNotificacionAviso($id)
    {
        $notificacionAviso = notificacion_aviso::find($id);
        $name = explode('/', $notificacionAviso->documento_notificacion);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/notificacionesAviso/'.$notificacionAviso->documento_notificacion), array_last($name), $headers);
    }

    public function exportarNotificacionesAviso($parametro)
    {
        if (is_numeric($parametro)) {
            $notificacionAviso = notificacion_aviso::with('hasTipoNotificacion')->where('numero_documento', '=', $parametro)->orderBy('fecha_publicacion', 'desc')->get();
        } else {
            $notificacionAviso = notificacion_aviso::with('hasTipoNotificacion')->where('nombre_notificado', 'like', '%'.$parametro.'%')->orderBy('fecha_publicacion', 'desc')->get();
        }

        $headerHtml = View()->make('plantillas.header')->render();
        $footerHtml = View()->make('plantillas.footer')->render();
        
        $pdf = PDF::loadView('publico.notificacionesAviso.historialNotificacionesAviso', [
            'parametro' => $parametro,
            'notificacionesAviso' => $notificacionAviso,
            'documento' => 'Notificaciones por aviso',
        ])->setPaper('letter')->setOption('margin-bottom', 28)->setOption('margin-top', 35)->setOption('margin-right', 2)->setOption('margin-left', 2)->setOption('header-html', $headerHtml)->setOption('footer-html', $footerHtml);

        return $pdf->download('Notificaciones por aviso a '.$parametro.'.pdf');
    }

    public function solicitarPreAsignacion()
    {
        $clases_vehiculos = vehiculo_clase::where('pre_asignable','SI')->pluck('name', 'id');
        $tipos_documentos_identidad = usuario_tipo_documento::pluck('name', 'id');

        return view('publico.tramites.pre_asignaciones.solicitud', [
            'clases_vehiculos' => $clases_vehiculos,
            'tipos_documentos' => $tipos_documentos_identidad,
        ]);
    }

    public function getServiciosPorClaseVehiculo($clase_id)
    {
        $clase_vehiculo = vehiculo_clase::with('hasServicios')->find($clase_id);

        return $clase_vehiculo->hasServicios->toJson();
    }

    public function crearSolicitudPreAsignacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_documento' => 'integer|exists:usuario_tipo_documento,id|required',
            'numero_documento' => 'numeric|required',
            'nombre_solicitante' => 'string|required',
            'telefono_solicitante' => 'numeric',
            'correo_solicitante' => 'required|email',
            'clase_vehiculo' => 'integer|exists:vehiculo_clase,id|required',
            'servicio_vehiculo' => 'integer|exists:vehiculo_servicio,id|required',
            'numero_motor' => 'string|required',
            'numero_chasis' => 'string|required',
            'manifiesto_importacion' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'factura_compra' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'observaciones' => 'string',
            'g-recaptcha-response' => 'required|captcha',
            'tipo_documento_propietario' => 'integer|exists:usuario_tipo_documento,id|required',
            'nombre_propietario' => 'required|string',
            'numero_documento_propietario' => 'numeric|required',
            'cedula_propietario' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
        ], [
            'tipo_documento.integer' => 'El formato del documento de identificación del solicitante no es válido.',
            'tipo_documento.exists' => 'El documento de identificación del solicitante especificado no existe en el sistema.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identificación del solicitante.',
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
            'manifiesto_importacion.mimes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png)',
            'manifiesto_importacion.mimetypes' => 'El manifiesto de importación no tiene un formato válido. (jpeg, jpg o png.)',
            'manifiesto_importacion.max' => 'El manifiesto de importación supera el tamaño máximo permitido de 2MB',
            'manifiesto_importacion.required' => 'No se ha suministrado el manifiesto de importación',
            'factura_compra.mimes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png)',
            'factura_compra.mimetypes' => 'La factura de compra no tiene un formato válido. (jpeg, jpg o png.)',
            'factura_compra.max' => 'La factura de compra supera el tamaño máximo permitido de 2MB',
            'factura_compra.required' => 'No se ha suministrado la factura de compra',
            'observaciones.string' => 'Las observaciones no tiene un formato válido. (Sólo número y letras)',
            'nombre_propietario.required' => 'No se ha especificado el nombre del propietario del vehículo.',
            'nombre_propietario.string' => 'El nombre del propietario del vehículo especificado no tiene un formato válido.',
            'numero_documento_propietario.numeric' => 'El número de documento de identidad del propietario especificado no tiene un formato válido.',
            'numero_documento_propietario.required' => 'No se ha especificado el número de documento de identidad del propietario.',
            'cedula_propietario.mimes' => 'La cedula del propietario suministrada no tiene un formato válido.',
            'cedula_propietario.mimetypes' => 'La cedula del propietario suministrada no tiene un formato válido.',
            'cedula_propietario.max' => 'La cedula del propietario suministrada excede el tamaño máximo permitivo de :max MB.',
            'cedula_propietario.required' => 'Se debe suministrar la cedula del propietario.',
            'tipo_documento_propietario.integer' => 'El ID del tipo de documento de identidad del propietario no tiene un formato válido.',
            'tipo_documento_propietario.required' => 'No se ha especificado el tipo de documento de identidad del propietario.',
            'tipo_documento_propietario.exists' => 'El tipo de documento de identidad del propietaro especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->all())->withInput($request->all());
        } else {
            try {
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
                if ($solicitud != null) {
                    $solicitud->manifiesto_importacion = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('manifiesto_importacion'));
                    $solicitud->factura_compra = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('factura_compra'));
                    $solicitud->cedula_propietario = \Storage::disk('local')->putFile('tramites/preAsignaciones/'.$solicitud->id, $request->file('cedula_propietario'));
                    $solicitud->save();
                    $usuariosNotificacion = User::whereHas('hasRoles', function ($query) {
                        $query->name = 'Administrador';
                    })->get();
                    if ($usuariosNotificacion != null) {
                        foreach ($usuariosNotificacion as $usuario) {
                            $usuario->notify(new nuevaSolicitudPreAsignacion($solicitud));
                        }
                    }
                    event(new \App\Events\nuevaSolicitudPreAsignacion($solicitud));
                    \Session::flash('mensaje', 'Se ha creado la solicitud correctamente. Cuando sea resuelta recibirá un correo electrónico.');

                    return back();
                } else {
                    return back()->withErrors(['Se ha presentado un error en el proceso. Por favor inténtalo nuevamente y si el problema persiste contacte a un administrador.']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['Se ha presentado un error en el proceso. Por favor inténtalo nuevamente y si el problema persiste contacte a un administrador.']);
            }
        }
    }

    public function consultarPreAsignacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motor' => 'string|required',
            'numero_documento' => 'required|numeric',
            'g-recaptcha-response' => 'required|captcha',
        ],[
            'motor.required' => 'No ha especificado el número de motor para la consulta.',
            'motor.string' => 'El número de motor especificado no tiene un formato válido.',
            'numero_documento.required' => 'Debe especificar el número de documento del propietario.',
            'numero_documento.numeric' => 'El número de documento especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->all());
        }

        $preAsignaciones = solicitud_preasignacion::with('hasVehiculoClase','hasVehiculoServicio')->where('numero_motor',$request->motor)->where('numero_documento_propietario',$request->numero_documento)->orderBy('created_at', 'desc')->get();
        return view('publico.tramites.pre_asignaciones.consultarPreAsignacion', ['preAsignaciones'=>$preAsignaciones])->render();
    }

    public function obtenerTurnosllamados()
    {
        $turnos = \DB::table('tramite_solicitud_turno')->join('tramite_solicitud_asignacion', 'tramite_solicitud_turno.id', '=', 'tramite_solicitud_asignacion.tramite_solicitud_turno_id')->join('ventanilla', 'tramite_solicitud_asignacion.ventanilla_id', '=', 'ventanilla.id')->select('tramite_solicitud_turno.turno as turno', 'ventanilla.codigo as ventanilla')->where('tramite_solicitud_turno.fecha_llamado', '!=', null)->whereDate('tramite_solicitud_turno.created_at', date('Y-m-d'))->orderBy('tramite_solicitud_turno.created_at', 'desc')->get()->take(5);

        return $turnos->toJson();
    }

    public function verEdictoComparendo($id)
    {
        $edicto = CoactivoComparendo::find($id);
        if(\File::exists( $image=storage_path('app/edictos/'. $edicto->pathArchive) )){            
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="'.$edicto->cc.'.pdf"',
            ];
            return Response()->download(storage_path('app/edictos/'.$edicto->pathArchive), $edicto->cc.'.pdf', $headers);
        }else{
            abort(404);
        }
    }

    public function verEdictoFotoMulta($id)
    {
        $edicto = CoactivoFotoMultas::find($id);
        if(\File::exists( $image=storage_path('app/edictos/'. $edicto->pathArchive) )){            
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="'.$edicto->cc.'.pdf"',
            ];
            return Response()->download(storage_path('app/edictos/'.$edicto->pathArchive), $edicto->cc.'.pdf', $headers);
        }else{
            abort(404);
        }
    }

    public function pqr_pqr_getAnexos($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        $file = explode('/', $pqr->anexos);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($file).'"',
        ];

        return Response()->download(storage_path('app/pqr/'.$pqr->anexos), array_last($file), $headers);
    }

    public function pqr_getDocumento($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        $file = explode('/', $pqr->documento_radicadoa);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($file).'"',
        ];

        return Response()->download(storage_path('app/pqr/'.$pqr->documento_radicado), array_last($file), $headers);
    }

    public function pqr_getPDF($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        $file = explode('/', $pqr->pdf);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($file).'"',
        ];

        return Response()->download(storage_path('app/pqr/radicado/'.$pqr->pdf), array_last($file), $headers);
    }

    public function liquidacionesServicioPublico_index()
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::pluck('name','id');
        return view('publico.liquidaciones.servicio_publico.index',['tiposDocumentosIdentidad'=>$tiposDocumentosIdentidad]);
    }

    public function liquidacionesServicioPublico_consultar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placa' => 'string|required',
            'numero_documento' => 'required|numeric',
            'tipo_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'g-recaptcha-response' => 'required|captcha',
        ],[
            'placa.required' => 'No ha especificado la placa del vehículo para la consulta.',
            'placa.string' => 'La placa especificado no tiene un formato válido.',
            'numero_documento.required' => 'Debe especificar el número de documento del propietario.',
            'numero_documento.numeric' => 'El número de documento especificado no tiene un formato válido.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identidad.',
            'tipo_documento.integer' => 'El tipo de documento de identidad no tiene un formato válido.',
            'tipo_documento.exists' => 'El tipo de documento de identidad especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->all());
        }
        
        $propietario = vehiculo_propietario::whereHas('hasVehiculosActivos',function ($query) use ($request){
            $query->where('placa',$request->placa);
        })->with('hasVehiculosActivos')->where('tipo_documento_id', $request->tipo_documento)->where('numero_documento',$request->numero_documento)->first();

        if($propietario == null){
            return back()->withErrors(['Los datos suministrados no existen o no están relacionados.'])->withInput($request->all());
        }

        $vehiculo = $propietario->hasVehiculosActivos->filter(function ($q) use ($request){
            return $q->placa = $request->placa;
        });

        return view('publico.liquidaciones.servicio_publico.consulta',['vehiculo'=>$vehiculo->first()]);
    }

    public function liquidacionesServicioPublico_nuevaLiquidacion(Request $request)
    {
        $vehiculo = vehiculo::find($request->id);
        if($vehiculo->hasPropietariosActivos()->count() == 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El vehículo especificado no tiene propietarios vinculados.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
        $vigencias = vehiculo_liquidacion_vigencia::pluck('vigencia', 'id');
        $descuentos = vehiculo_liquidacion_descuento::pluck('concepto', 'id');
        return view('publico.liquidaciones.servicio_publico.nuevaLiquidacion', ['vehiculoId'=>$vehiculo->id,'vigencias'=>$vigencias,'descuentos'=>$descuentos])->render();
    }

    public function liquidacionesServicioPublico_calcularValores(Request $request)
    {
        return json_encode($this->liquidacionesServicioPublico_calcularValor($request->vehiculoId, $request->vigenciaId));
    }

    private function liquidacionesServicioPublico_calcularValor($vehiculo, $vigenciafrm)
    {
        try{
            $vehiculo = vehiculo::find($vehiculo);
            $vigencia_liquidacion = vehiculo_liquidacion_vigencia::find($vigenciafrm);
            $base_gravable = vehiculo_base_gravable::where('vehiculo_linea_id', $vehiculo->vehiculo_linea_id)->where('modelo', $vehiculo->modelo)->where('vigencia',$vigencia_liquidacion->vigencia)->first();
            $avaluo = $base_gravable->avaluo * 1000;
            $intereses = 0;
            $descuentos = 0;
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
                                $intereses += abs(round($impuesto * ((array_first($mes)['pivot']['porcentaje_interes'] / 100) / 366) * $this->liquidacionesServicioPublico_calcularDias($vigencia->vigencia, array_pluck($mes, 'id')), -3));
                            }else{
                                $intereses += abs(round($impuesto * (($mes['pivot']['porcentaje_interes'] / 100) / 366) * $this->liquidacionesServicioPublico_calcularDias($vigencia->vigencia, [$mes->id]), -3));
                            }
                        }
                    }
                    return ['impuesto'=>$impuesto, 'intereses'=>$intereses,'descuentos'=>$descuentos, 'valor_total'=>($impuesto+$intereses)-$descuentos,'avaluo'=>$avaluo];
                }elseif(date('Y') == $vigencia_liquidacion->vigencia){//Si la vigencia es la actual
                    return ['impuesto'=>$impuesto, 'intereses'=>$intereses,'descuentos'=>$descuentos, 'valor_total'=>($impuesto+$intereses)-$descuentos,'avaluo'=>$avaluo];
                }else{//La vigencia es mayor a la actual. Error
                    return 'Error de vigencia';
                }
            }else{
                return 'Sin Base Gravable';
            }
        }catch (\Exception $e){
            return 'Error de cálculo.';
        }
    }

    private function liquidacionesServicioPublico_calcularDias($vigencia, $meses)
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

    public function liquidacionesServicioPublico_crearLiquidacion(Request $request)
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
                    $consultaLiquidacion = $this->liquidacionesServicioPublico_calcularValor($request->vehiculo, $request->vigencia);
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

    public function liquidacionesServicioPublico_obtenerLiquidaciones(Request $request)
    {
        $vehiculo = vehiculo::with('hasLiquidaciones')->find($request->id);
        return view('publico.liquidaciones.servicio_publico.liquidaciones', ['vehiculo'=>$vehiculo])->render();
    }

    public function liquidacionesServicioPublico_imprimirLiquidacion($id)
    {
        $liquidacion = vehiculo_liquidacion::find($id);
        $pdf = \PDF::loadView('admin.sistema.vehiculos.liquidaciones.imprimirLiquidacion',['liquidacion'=>$liquidacion])->setPaper('letter')->setOption('enable-smart-shrinking', true)->setOption('no-outline', true);
        return $pdf->download();
    }

    public function consultarNormativas(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipoCriterio' => 'string|required',
            'criterio' => 'string|required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'tipoCriterio.string' => 'El formato de criterio de búsqueda no es válido.',
            'tipoCriterio.required' => 'No se ha especificado el criterio de búsqueda.',
            'criterio.string' => 'El formato del nombre o número de documento no es válido.',
            'criterio.required' => 'No se ha especificado el nombre o número de documento.'
        ]);

        if ($validator->fails()) {
            $request->flashOnly(['tipo_documento', 'numero_documento', 'radicado']);
            return back()->withErrors($validator->errors()->all());
        }else{
            if ($request->tipoCriterio == 'numero') {
                $normativas = normativa::with('hasTipo')->where('numero', '=', $request->criterio)->orderBy('fecha_expedicion', 'desc')->get();

                return view('publico.normativas.consultarNormativas', [
                    'normativas' => $normativas,
                    'parametro' => $request->criterio,
                ]);
            } elseif ($request->tipoCriterio == 'objeto') {
                $normativas = normativa::with('hasTipo')->where('objeto', 'like', '%'.$request->criterio.'%')->orderBy('fecha_expedicion', 'desc')->get();

                return view('publico.normativas.consultarNormativas', [
                    'normativas' => $normativas,
                    'parametro' => $request->criterio,
                ]);
            }  elseif ($request->tipoCriterio == 'fecha') {
                $normativas = normativa::with('hasTipo')->where('fecha_expedicion', $request->criterio)->orderBy('fecha_expedicion', 'desc')->get();

                return view('publico.normativas.consultarNormativas', [
                    'normativas' => $normativas,
                    'parametro' => $request->criterio,
                ]);
            } else {
                return redirect()->to('back')->withErrors(['No ha especificado un criterio de búsqueda válido.']);
            }
        }
    }

    public function getDocumentoNormativa($id)
    {
        $normativa = normativa::find($id);
        $name = explode('/', $normativa->documento);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/normativas/'.$normativa->documento), array_last($name), $headers);
    }
}
