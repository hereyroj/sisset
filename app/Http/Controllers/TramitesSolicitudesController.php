<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\archivo_carpeta;
use App\archivo_carpeta_prestamo;
use App\archivo_solicitud;
use App\archivo_solicitud_va_ve;
use App\departamento;
use App\Events\nuevaSolicitudCarpeta;
use App\Events\TramiteFinalizado;
use App\Events\turnoGenerado;
use App\Exports\SustratosAnulados;
use App\Exports\SustratosConsumidos;
use App\funcionario_descanso;
use App\funcionario_descanso_motivo;
use App\licencia_categoria;
use App\Mail\TramiteServicioEstado;
use App\Mail\TramiteServicioRecibos;
use App\placa;
use App\sustrato;
use App\tipo_sustrato;
use App\tramite;
use App\tramite_licencia;
use App\tramite_solicitud_atencion;
use App\tramite_servicio_finalizacion;
use App\tramite_solicitud_origen;
use App\tramite_solicitud_recibo;
use App\tramite_solicitud_turno;
use App\User;
use App\usuario_tipo_documento;
use App\vehiculo_carroceria;
use App\vehiculo_clase;
use App\vehiculo_combustible;
use App\vehiculo_marca;
use App\vehiculo_servicio;
use App\ventanilla;
use App\empresa_transporte;
use Maatwebsite\Excel\Facades\Excel;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\GdEscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mockery\Exception;
use PDF;
use Validator;
use App\tramite_solicitud;
use App\tramite_solicitud_estado;
use App\tramite_solicitud_radicado;
use App\tramite_solicitud_usuario;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\Printer;
use App\tramite_grupo;
use App\tramite_servicio;
use App\tramite_servicio_estado;
use App\tramite_servicio_recibo;
use App\sustrato_anulacion_motivo;
use App\sustrato_anulacion;
use App\Events\tramiteSolicitudAsignado;
use App\Events\turnoAsignado;

class TramitesSolicitudesController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Número documento',
            '2' => 'Placa',
            '3' => 'Turno',
        ];
        $sFiltro = null;

        return view('admin.tramites.solicitudes.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    private function obtenerComplementos()
    {
        $tramitesGrupos = tramite_grupo::has('hasTramites')->orderBy('name', 'asc')->pluck('name', 'id');
        $tramitesSolicitudOrigenes = tramite_solicitud_origen::orderBy('name', 'asc')->pluck('name', 'id');
        $tiposDocumentos = usuario_tipo_documento::orderBy('name', 'asc')->pluck('name', 'id');

        return [
            'tiposDocumentos' => $tiposDocumentos,
            'tramitesSolicitudOrigenes' => $tramitesSolicitudOrigenes,
            'tramitesGrupos' => $tramitesGrupos
        ];
    }

    public function nuevaSolicitud()
    {
        $complementos = $this->obtenerComplementos();
        return view('admin.tramites.solicitudes.nuevaSolicitud', $complementos)->render();
    }

    public function moduloVentanilla()
    {
        $filtros = [
            '1' => 'Número documento',
            '2' => 'Placa',
            '3' => 'Turno',
        ];
        $sFiltro = null;

        return view('admin.tramites.solicitudes.ventanilla', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function crearSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'servicios' => 'required|numeric|min:1|max:10',
            'tramites' => 'required|array',
            'tramite_solicitud_origen' => 'required|integer|exists:tramite_solicitud_origen,id',
            'nombre' => 'required|string',
            'numero_documento' => 'required|numeric',
            'correo' => 'email|nullable',
            'telefono' => 'numeric|nullable',
            'tipo_documento' => 'integer|exists:usuario_tipo_documento,id',
            'preferente' => ['required', 'integer', Rule::in(['0', '1'])],
            'observacion' => 'string|nullable'
        ], [
            'servicios.required' => 'No se ha especificado la cantidad de servicios.',
            'servicios.numeric' => 'La cantidad de servicios no tiene un formato válido.',
            'servicios.min' => 'La cantidad de servicios debe como mínimo ser :min.',
            'servicios.max' => 'La catidad de servicios no debe ser mayor de :max',
            'tramites.required' => 'No se ha especificado los tramites de la solicitud.',
            'tramites.array' => 'El valor de los tramites de la solicitud no tiene un formato válido.',
            'tramite_solicitud_origen.required' => 'No se ha especificado el origen de la solicitud.',
            'tramite_solicitud_origen.integer' => 'El ID del origen de la solicitud especificado no tiene un formato válido.',
            'tramite_solicitud_origen.exists' => 'El origen de la solicitud especificado no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre del usuario.',
            'nombre.string' => 'El nombre del usuario especificado no tiene un formato válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad del usuario.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'correo.email' => 'El correo electrónico del usuario especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el número telefónico del usuario.',
            'telefono.numeric' => 'El número telefónico del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.required' => 'No se ha especificado el tipo de documento de identidad del usuario.',
            'tipo_documento_identidad.integer' => 'El ID del tipo de documento de identidad del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.exists' => 'El tipo de documento de identidad del usuario especificado no existe en el sistema.',
            'preferente.required' => 'No se ha especificado si el turno es preferente.',
            'preferente.integer' => 'El valor especificado en el campo preferente no es válido.',
            'preferente.in' => 'El valor especificado en el campo preferente no es válido.',
            'observacion.string' => 'EL valor especificado para la observación no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.tramites.solicitudes.nuevaSolicitud', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
        } else {
            $tramite_radicado = null;
            $solicitud = null;
            $usuario = null;
            $turno = null;

            try {
                \DB::beginTransaction();
                $solicitud = tramite_solicitud::create([
                    'servicios' => $request->servicios,
                    'observacion' => $request->observacion,
                    'tramite_grupo_id' => $request->grupo
                ]);

                $solicitud->hasTramites()->sync($request->tramites);

                $turno = $this->generarTurno($solicitud, $request);

                \DB::commit();
                $success = true;
            } catch (\Exception $e) {
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                event(new turnoGenerado($turno, $solicitud));

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha completado el registro de la solicitud con el turno ' . $turno->turno,
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear la solicitud. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    private function generarRadicado($id)
    {
        //Se obtiene el último radicado en la bd
        $ultimoRadicado = tramite_solicitud_radicado::where('vigencia', date('Y'))->orderBy('created_at', 'desc')->first();

        //validamos si ya hay algún registro de la vigencia actual. Si no lo hay, se genera el predeterminado
        if ($ultimoRadicado == null) {
            $numero = '000001';
        } else {
            //Establecemos el nuevo numero de radicado. Se eliminan los ceros de la izquierda primero y luego se aumenta en 1
            $numero = ltrim($ultimoRadicado->consecutivo, "0");
            ++$numero;
            //Se vuelven a agregar los ceros a la izquierda
            $numero = sprintf("%'.06d\n", $numero);
        }
        //se crea el nuevo consecutivo
        $radicado = tramite_solicitud_radicado::create([
            'vigencia' => date('Y'),
            'consecutivo' => $numero,
            'tramite_solicitud_id' => $id,
        ]);

        return $radicado;
    }

    public function obtenerSolicitudes()
    {
        $solicitudes = tramite_solicitud::with('hasTramite', 'hasVehiculoClase', 'hasVehiculoServicio', 'hasRadicados', 'hasEstados')->whereIn('tramite_id', auth()->user()->hasTramitesAsignados())->get();
    }

    public function obtenerSolicitud($id)
    {
        $solicitud = tramite_solicitud::with('hasTramite', 'hasVehiculoClase', 'hasVehiculoServicio', 'hasRadicados', 'hasEstados')->find($id);
    }

    public function asignarEstadoServicio($servicio_id)
    {
        $estados = tramite_servicio_estado::pluck('name', 'id');
        return view('admin.tramites.solicitudes.asignarEstado', [
            'id' => $servicio_id,
            'estados' => $estados,
        ])->render();
    }

    public function asignarEstado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tramite_servicio' => 'required|integer|exists:tramite_servicio,id',
            'tramite_servicio_estado' => 'required|integer|exists:tramite_servicio_estado,id',
            'observacion' => 'string',
        ], [
            'tramite_servicio.integer' => 'El ID del servicio del trámite especificado no tiene un formato válido.',
            'tramite_servicio.exists' => 'El servicio del trámite especificado no existe en el sistema.',
            'tramite_servicio.required' => 'No se ha especificado el servicio del trámite.',
            'tramite_servicio_estado.integer' => 'El ID del estado especificado no tiene un formato válido.',
            'tramite_servicio_estado.exists' => 'El estado especificado no existe en el sistema.',
            'tramite_servicio_estado.required' => 'No se ha especificado el estado.',
            'observacion.string' => 'La observación especificada no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $tramite_servicio = tramite_servicio::with('hasEstados')->find($request->tramite_servicio);
                $tramite_servicio->hasEstados()->attach($request->tramite_servicio_estado, [
                    'tramite_servicio_id' => $request->tramite_servicio,
                    'tramite_servicio_estado_id' => $request->tramite_servicio_estado,
                    'observacion' => $request->observacion,
                    'funcionario_id' => auth()->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $usuario = tramite_solicitud_usuario::where('tramite_solicitud_turno_id', $tramite_servicio->hasSolicitud->hasTurnoActivo()->id)->first();
                if ($usuario->correo_electronico != null) {
                    \Mail::send(new TramiteServicioEstado($tramite_servicio->hasEstados()->orderBy('created_at', 'desc')->first(), $usuario->nombre_usuario, $usuario->correo_electronico, $tramite_servicio->hasSolicitud->hasTurnoActivo()->turno));
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado el nuevo estado en la solicitud',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido asignar el estado. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    private function generarTurno($solicitud, $request)
    {
        try {
            $ultimo_turno_tramite = tramite_solicitud_turno::whereDate('created_at', date('Y-m-d'))->where('turno', 'like', $solicitud->hasTramiteGrupo->code.'%')->orderBy('created_at', 'desc')->first();

            if ($ultimo_turno_tramite == null) {
                $numero = '001';
            } else {
                //Establecemos el nuevo numero de radicado. Se eliminan los ceros de la izquierda primero y luego se aumenta en 1
                $numero = preg_replace("/[^0-9,.]/", "", $ultimo_turno_tramite->turno);
                $numero = ltrim($numero, "0");
                ++$numero;
                //Se vuelven a agregar los ceros a la izquierda
                $numero = sprintf("%'.03d", $numero);
            }
            
            $turno = tramite_solicitud_turno::create([
                'turno' => substr($solicitud->hasTramiteGrupo->code, 0, 3) . $numero,
                'tramite_solicitud_id' => $solicitud->id,
                'tramite_solicitud_origen_id' => $request->tramite_solicitud_origen,
                'preferente' => $request->preferente
            ]);

            $usuario = tramite_solicitud_usuario::create([
                'tramite_solicitud_turno_id' => $turno->id,
                'nombre_usuario' => strtoupper($request->nombre),
                'numero_documento' => $request->numero_documento,
                'correo_electronico' => $request->correo,
                'numero_telefonico' => $request->telefono,
                'tipo_documento_identidad_id' => $request->tipo_documento
            ]);

            $this->imprimirTicket($turno);
            return $turno;
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'No se ha podido generar el turno. Si el problema persiste, por favor comunicarse con soporte.',
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    private function imprimirTicket($turno)
    {
        try {
            $profile = CapabilityProfile::load("simple");
            $connector = new NetworkPrintConnector("192.168.1.23", 9100);
            $printer = new Printer($connector, $profile);
            $printer->setTextSize(1, 2);

            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(\anlutro\LaravelSettings\Facade::get('empresa-nombre'));
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(false);
            $printer->feed();
            $printer->feed();

            $printer->setTextSize(2, 3);
            $printer->setEmphasis(true);
            $printer->text('Turno: ');                         
            $printer->text($turno->turno);
            $printer->setTextSize(1, 2);
            $printer->feed();

            $printer->text('Nombre: ');
            $printer->setEmphasis(false);
            $printer->text($turno->hasUsuarioSolicitante->nombre_usuario);
            $printer->feed();

            $printer->setEmphasis(true);
            $printer->text($turno->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name.': ');
            $printer->setEmphasis(false);
            $printer->text($turno->hasUsuarioSolicitante->numero_documento);
            $printer->feed();

            $printer->setEmphasis(true);
            $printer->text('Hora: ');
            $printer->setEmphasis(false);
            $printer->text($turno->created_at->format('H:i:s'));
            $printer->feed(2);

            $printer->cut();
            $printer->pulse();
            $printer->close();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function asignarFuncionarioEnVentanilla(Request $request)
    {
        $success = false;
        try {
            \DB::beginTransaction();
            $ventanilla = ventanilla::find($request->ventanilla);
            $funcionario = $ventanilla->hasFuncionarioActivo();
            if ($funcionario != null) {
                if ($funcionario->id !== auth()->user()->id) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Ya hay un funcionario ocupando esta ventanilla. Por favor verifica e intente nuevamente.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }
            } else {
                if (auth()->user()->asignarVentanilla($ventanilla->id)) {
                    \DB::commit();
                    $success = true;
                }
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No hemos podido asignarlo a la ventanilla especificada. Si el problema persiste, por favor comunicarse con soporte.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        if ($success) {
            return auth()->user()->hasVentanillaAsignacionActiva()->toJson();
        } else {
            return false;
        }
    }

    public function obtenerCarpetasServicio($servicioId)
    {
        $servicio = tramite_servicio::with('hasSolicitudesCarpeta')->find($servicioId);

        return view('admin.tramites.solicitudes.listadoServiciosCarpetas', ['solicitudes' => $servicio->hasSolicitudesCarpeta, 'id' => $servicio->id])->render();
    }

    public function obtenerEstadosServicio($servicioId)
    {
        $servicio = tramite_servicio::with('hasEstados')->find($servicioId);

        return view('admin.tramites.solicitudes.listadoServiciosEstados', ['estados' => $servicio->hasEstados, 'id' => $servicio->id])->render();
    }

    public function obtenerMisTramites()
    {
        $misTramites = tramite_solicitud::whereHas('hasFuncionariosAsignados', function ($query) {
            $query->where('id', auth()->user()->id);
        })->with('hasRadicados', 'hasTramites', 'hasTramiteGrupo', 'hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasTurnos.hasOrigen')->orderBy('created_at','desc')->paginate();

        return view('admin.tramites.solicitudes.listadoMisTramites', ['misTramites' => $misTramites])->render();
    }

    public function finalizarTurnoF1($turno_id, $solicitud_id, $ventanilla_id)
    {
        try {
            return view('admin.tramites.solicitudes.finalizarTurnoF1', [
                'turno_id' => $turno_id,
                'solicitud_id' => $solicitud_id,
                'ventanilla_id' => $ventanilla_id,
            ])->render();
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No hemos podido asignarlo a la ventanilla especificada. Si el problema persiste, por favor comunicarse con soporte.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function verTurnos($solicitud_id)
    {
        $solicitud = tramite_solicitud::with('hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasTurnos.hasFuncionarioReLlamado', 'hasTurnos.hasAtencion')->find($solicitud_id);

        return view('admin.tramites.solicitudes.verTurnos', ['tramite' => $solicitud])->render();
    }

    public function verRadicados($solicitud_id)
    {
        $solicitud = tramite_solicitud::with('hasRadicados')->find($solicitud_id);

        return view('admin.tramites.solicitudes.verRadicados', ['tramite' => $solicitud])->render();
    }

    public function verAsignaciones($solicitud_id)
    {
        $asignaciones = \DB::table('tramite_solicitud_asignacion')->select('*')->where('tramite_solicitud_id', $solicitud_id)->orderBy('created_at')->get();

        return view('admin.tramites.solicitudes.verAsignaciones', ['asignaciones' => $asignaciones])->render();
    }

    public function obtenerTramites()
    {
        $tramites = tramite_solicitud::with('hasTramiteGrupo', 'hasTramites', 'hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasRadicados', 'hasTurnos.hasOrigen')->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.tramites.solicitudes.listadoTramites', ['tramites' => $tramites])->render();
    }

    public function obtenerVentanillasAsignacion()
    {
        $ventanillas = ventanilla::all();
        $ventanillas = $ventanillas->filter(function ($item) {
            return $item->hasFuncionarioActivo() == null;
        });
        $ventanillas = $ventanillas->pluck('name', 'id');

        return view('admin.tramites.solicitudes.ventanillasAsignacion', ['ventanillas' => $ventanillas])->render();
    }

    public function editarSolicitud($id)
    {
        $solicitud = tramite_solicitud::find($id);
        if ($solicitud->getEstadoSolicitud() == 'finalizado') {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El tramite ya tiene un registro de finalización, por lo que no es permitido modificación alguna.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        $tramitesGrupos = tramite_grupo::has('hasTramites')->orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.tramites.solicitudes.editarSolicitud', [
            'tramitesGrupos' => $tramitesGrupos,
            'solicitud' => $solicitud
        ]);
    }

    public function actualizarSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tramite_solicitud,id',
            'servicios' => 'required|numeric|min:1|max:10',
            'tramites' => 'required|array',
            'observacion' => 'string|nullable'
        ], [
            'id.integer' => 'EL ID de la solicitud a amodificar no tiene un formato válido.',
            'id.required' => 'No se ha especificado la solicitud a modificar.',
            'id.exists' => 'La solicitud especificada no existe en el sistema.',
            'servicios.required' => 'No se ha especificado la cantidad de servicios.',
            'servicios.numeric' => 'La cantidad de servicios no tiene un formato válido.',
            'servicios.min' => 'La cantidad de servicios debe como mínimo ser :min.',
            'servicios.max' => 'La catidad de servicios no debe ser mayor de :max',
            'tramites.required' => 'No se ha especificado los tramites de la solicitud.',
            'tramites.array' => 'El valor de los tramites de la solicitud no tiene un formato válido.',
            'observacion.string' => 'EL valor especificado para la observación no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.tramites.solicitudes.nuevaSolicitud', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
        }

        $solicitud = tramite_solicitud::find($request->id);
        if ($solicitud->getEstadoSolicitud() == 'finalizado') {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El tramite ya tiene un registro de finalización, por lo que no es permitido modificación alguna.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        try {
            \DB::beginTransaction();
            $solicitud->servicios = $request->servicios;
            $solicitud->observacion = $request->observacion;
            $solicitud->save();

            $solicitud->hasTramites()->sync($request->tramites);

            \DB::commit();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la solicitud.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la solicitud. Si el problema persiste, por favor comunicarse con soporte.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function finalizarTramiteF1($servicio_id)
    {
        $placa = null;
        $sustratos = null;
        $servicio = tramite_servicio::with('hasTramites')->find($servicio_id);
        /*if (count($servicio->hasTramites->filter(function ($data) {
            return $data->requiere_placa == 'SI';
        })) > 0) {
            $placa = \DB::table('placa')->select('placa.*')->select('placa_preasignacion.*')
                ->join('placa_preasignacion', 'placa.id', '=', 'placa_preasignacion.placa_id')
                ->where('placa_preasignacion.fecha_liberacion', '!=', null)
                ->where('placa_preasignacion.fecha_matricula', '!=', null)
                ->where('placa.vehiculo_clase_id', $servicio->vehiculo_clase_id)
                ->where('placa.vehiculo_servicio_id', $servicio->vehiculo_servicio_id)
                ->where('placa.name', $servicio->placa)
                ->first();
        }*/
        return view('admin.tramites.solicitudes.registrarFinalizacion', ['placa' => $placa, 'servicio_id' => $servicio->id])->render();
    }

    public function finalizarTramiteF2(Request $request)
    {
        $success = false;
        $tramite = null;
        $sustrato = null;
        try{
            $servicio = tramite_servicio::with('hasFinalizacion', 'hasEstados', 'hasRecibos')->find($request->servicio_id);
            if ($servicio->hasRecibos->count() <= 0) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede registrar una finalización, sin antes haber registrado los recibos.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }

            if($servicio->hasFinalizacion != null){

            }

            if ($servicio->hasSolicitudesCarpeta()->whereHas('hasCarpetaPrestada', function ($query) {
                    $query->where('fecha_entrega', null);
                })->doesntHave('hasDenegacion')->get()->count() > 0) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede registrar una finalización, sin antes haber recibido la carpeta solicitada.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }

            if($servicio->hasFinalizacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El servicio ya cuenta con una finalización.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }

            if ($servicio->hasEstados->count() > 0) {
                try {
                    \DB::beginTransaction();
                    /*if (isset($request->placa)) {
                        $placa = placa::with('hasSolicitudesPreAsignaciones')->find($request->placa);
                        if ($placa->hasPreAsignacionActiva()) {
                            $placa->hasSolicitudesPreAsignaciones->pivot->fecha_matricula = date('Y-m-d H:i:s');
                            $placa->hasSolicitudesPreAsignaciones->pivot->save();
                        } else {
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['El trámite a realizar requiere que haya una pre-matricula con antelación.'],
                                'encabezado' => '¡Error!',
                            ], 200);
                        }
                    }*/

                    $tramitesSustratos = $servicio->hasTramites->filter(function ($data) {
                        return $data->requiere_sustrato == 'SI';
                    });

                    if (count($tramitesSustratos) > 0) {
                        $sustrato = sustrato::where('consumido', 'NO')->where('tipo_sustrato_id', $tramitesSustratos->first()->tipo_sustrato_id)->doesntHave('hasAnulacion')->first();
                        if ($sustrato == null) {
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['No hay sustratos disponibles.'],
                                'encabezado' => '¡Error!',
                            ], 200);
                        }
                    }

                    if ($sustrato != null) {
                        $tramite = tramite_servicio_finalizacion::create([
                            'tramite_servicio_id' => $request->servicio_id,
                            'sustrato_id' => $sustrato->id,
                            'placa_id' => $request->placa,
                            'observacion' => strtoupper($request->observacion),
                            'created_at' => date('Y-m-d H:i:s'),
                            'funcionario_id' => auth()->user()->id
                        ]);
                        $sustrato->proceso_id = $tramite->id;
                        $sustrato->proceso_type = 'App\\tramite_servicio_finalizacion';
                        $sustrato->consumido = 'SI';
                        $sustrato->save();
                    } else {
                        $tramite = tramite_servicio_finalizacion::create([
                            'tramite_servicio_id' => $request->servicio_id,
                            'sustrato_id' => null,
                            'placa_id' => $request->placa,
                            'observacion' => strtoupper($request->observacion),
                            'created_at' => date('Y-m-d H:i:s'),
                            'funcionario_id' => auth()->user()->id
                        ]);
                    }
                    \DB::commit();
                    $success = true;
                } catch (\Exception $e) {
                    \DB::rollBack();
                }
                if ($success == true) {
                    event(new TramiteFinalizado($tramite));
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha registrado la finalización correctamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido registrar la finalización. Si el problema persiste, por favor comunicarse con soporte.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se han registrado estados en el servicio.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }catch (\Exception $j){

        }
    }

    public function obtenerFinalizacionServicio($id)
    {
        $servicio = tramite_servicio::find($id);
        return view('admin.tramites.solicitudes.obtenerFinalizacionServicio', ['finalizacion' => $servicio->hasFinalizacion, 'id'=>$id])->render();
    }

    public function finalizarTurnoF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'solicitud' => 'required|integer|exists:tramite_solicitud,id',
            'turno' => 'required|integer|exists:tramite_solicitud_turno,id',
            'ventanilla' => 'required|integer|exists:ventanilla,id',
            'observacion_tramite' => 'string|nullable',
            'estado_tramite' => ['required','integer', Rule::in([1,2,3,4,5])],
            'usuario_asistencia' => ['required', Rule::in(['SI','NO','NOP'])]
        ], [
            'solicitud.required' => 'No se ha especificado la solicitud de tramite a finalizar.',
            'solicitud.integer' => 'La solicitud de tramite especificada no tiene un formato válido.',
            'solicitud.exists' => 'La solicitud de tramite especificada no existe en el sistema.',
            'turno.required' => 'No se ha especificado el turno a finalizar.',
            'turno.integer' => 'El turno especificado no tiene un formato válido.',
            'turno.exists' => 'EL turno especificado no existe en el sistema.',
            'ventanilla.required' => 'No se ha especificado la ventanilla a finalizar.',
            'ventanilla.integer' => 'La ventanilla especificada no tiene un formato válido.',
            'ventanilla.exists' => 'La ventanilla especificada no existe en el sistema.',
            'observacion_tramite.string' => 'La observación proporcionada no tiene un formato válido.',
            'observacion_tramite.required' => 'No se ha especifcado observación.',
            'estado_tramite.required' => 'No se ha especificado el estado en que finaliza el tramite.',
            'estado_tramite.integer' => 'El estado de finalización del tramite especificado no tiene un formato válido.',
            'estado_tramite.in' => 'El valor del estado de finalización del trámite no es válido.',
            'usuario_asistencia.required' => 'No se ha especificado si el usuario se presentó a la ventanilla.',
            'usuario_asistencia.in' => 'El valor especificado para determinar si el usuario se presentó en la ventanilla no es válido.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.tramites.solicitudes.finalizarTurnoF1', [
                'turno_id' => $request->turno,
                'solicitud_id' => $request->solicitud,
                'ventanilla_id' => $request->ventanilla,
                'errors' => $validator->errors()->all()
            ])->render();
        }

        $success = false;
        try{
            \DB::beginTransaction();
        /*
         * Se comprueba si esta finalización responde a un estado pendiente y se modifica la información
         */
            $tramite_solicitud_atencion = tramite_solicitud_atencion::where('tramite_solicitud_turno_id', $request->turno)->where('tramite_solicitud_id', $request->solicitud)->where('terminacion', 2)->first();
            if($tramite_solicitud_atencion != null){
                $tramite_solicitud_atencion->terminacion = 1;
                $tramite_solicitud_atencion->save();
            }
            $turno = tramite_solicitud_turno::find($request->turno);
            $turno->fecha_atencion = date('Y-m-d H:i:s');
            $turno->save();
            tramite_solicitud_atencion::create([
                'tramite_solicitud_id' => $request->solicitud,
                'tramite_solicitud_turno_id' => $request->turno,
                'ventanilla_id' => $request->ventanilla,
                'funcionario_id' => auth()->user()->id,
                'observacion' => $request->observacion_tramite,
                'terminacion' => $request->estado_tramite,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
        }

        if($success == true){
            return view('admin.tramites.solicitudes.finalizarTurnoF2')->render();
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error al registrar la evaluación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function obtenerListadoOrigenes()
    {
        $origenes = tramite_solicitud_origen::paginate(15);
        return view('admin.tramites.solicitudes.listadoOrigenes', ['origenes' => $origenes])->render();
    }

    public function nuevoOrigen()
    {
        return view('admin.tramites.solicitudes.nuevoOrigen')->render();
    }

    public function crearOrigen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tramite_solicitud_origen'
        ], [
            'name.required' => 'No se ha especificado el nombre para el origen de la solicitud.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                tramite_solicitud_origen::create(['name' => strtoupper($request->name)]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Origen ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Origen.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarOrigen($id)
    {
        $origen = tramite_solicitud_origen::find($id);
        return view('admin.tramites.solicitudes.editarOrigen', ['origen' => $origen])->render();
    }

    public function actualizarOrigen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tramite_solicitud_origen',
            'name' => ['required', 'string', Rule::unique('tramite_solicitud_origen', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el origen de la solicitud a modificar.',
            'id.integer' => 'El ID del origen especificado no tiene un formato válido.',
            'id.exists' => 'El origen especificado a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre para el origen de la solicitud.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $origen = tramite_solicitud_origen::find($request->id);
                $origen->name = strtoupper($request->name);
                $origen->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Origen ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el origen.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function solicitarCarpeta($servicioId)
    {
        try {
            $servicio = tramite_servicio::with('hasTramites', 'hasEstados')->find($servicioId);

            $archivo_carpeta = archivo_carpeta::where('name', $servicio->placa)->first();

            if($archivo_carpeta != null){
                $solicitudPrioritaria = archivo_solicitud::whereHas('hasSolicitudFuncionario', function ($query) use ($servicio){
                    $query->where('placa', $servicio->placa)->whereHas('hasMotivo', function ($query2){
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
                        if ($archivo_carpeta->hasSolicitudPendiente()->id != $servicio->hasSolicitud->id) {
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['La carpeta solicitada tiene un trámite en curso diferente al que se le solicita.'],
                                'encabezado' => 'Error en la aprobación',
                            ], 200);
                        }
                    }
                }
            }

            if (count($servicio->hasTramites->filter(function ($item) {
                return $item->solicita_carpeta == 'NO';
            }))) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede solicitar la carpeta debido a que el tramite (o tramites) a realizar no lo requiere.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
            if ($servicio->hasEstados->count() <= 0) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede solicitar la carpeta, sin antes haber registrado un estado.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }

            if($servicio->hasSolicitudCarpetaPendiente() != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede solicitar la carpeta debido a que tiene ya una solicitud pendiente.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }

            archivo_solicitud::create([
                'request_date' => date('Y-m-d H:i:s'),
                'origen_id' => $servicioId,
                'origen_type' => 'App\tramite_servicio',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            event(new nuevaSolicitudCarpeta($servicio->hasSolicitud));
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha realizado la solicitud correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido realizar la solicitud.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function obtenerListadoEstados()
    {
        $estados = tramite_servicio_estado::paginate(15);
        return view('admin.tramites.solicitudes.listadoEstados', ['estados' => $estados])->render();
    }

    public function nuevoEstado()
    {
        return view('admin.tramites.solicitudes.nuevoEstado')->render();
    }

    public function crearEstado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tramite_servicio_estado',
            'finaliza_servicio' => ['required', 'string', Rule::in(['SI', 'NO'])],
            'requiere_observacion' => ['required', 'string', Rule::in(['SI', 'NO'])],
        ], [
            'name.required' => 'No se especificó un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'finaliza_servicio.required' => 'No se especificó si finaliza el servicio.',
            'finaliza_servicio.string' => 'El valor especificado para la finalización del servicio no tiene un formato válido.',
            'finaliza_servicio.in' => 'El valor especificado para la finalización del servicio no es válido.',
            'requiere_observacion.required' => 'No se ha especificado si se requiere de una observación.',
            'requiere_observacion.string' => 'El valor especificado para el requerimiento de una observación no tiene un formato válido.',
            'requiere_observacion.in' => 'El valor especificado para el requerimiento de una observación no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                tramite_servicio_estado::create([
                    'name' => strtoupper($request->name),
                    'finaliza_servicio' => $request->finaliza_servicio,
                    'requiere_observacion' => $request->requiere_observacion,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Estado ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Estado.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarEstado($id)
    {
        $estado = tramite_servicio_estado::find($id);
        return view('admin.tramites.solicitudes.editarEstado', ['estado' => $estado])->render();
    }

    public function actualizarEstado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tramite_servicio_estado',
            'name' => ['required', 'string', Rule::unique('tramite_servicio_estado', 'name')->ignore($request->id)],
            'finaliza_servicio' => ['required', 'string', Rule::in(['SI', 'NO'])],
            'requiere_observacion' => ['required', 'string', Rule::in(['SI', 'NO'])]
        ], [
            'id.required' => 'No se ha especificado el estado a modificar.',
            'id.integer' => 'El ID del estado especificado no tiene un formato válido.',
            'id.exists' => 'El estado a modificar no existe en el sistema.',
            'name.required' => 'No se especificó un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'finaliza_servicio.required' => 'No se especificó si finaliza el servicio.',
            'finaliza_servicio.string' => 'El valor especificado para la finalización del servicio no tiene un formato válido.',
            'finaliza_servicio.in' => 'El valor especificado para la finalización del servicio no es válido.',
            'requiere_observacion.required' => 'No se ha especificado si se requiere de una observación.',
            'requiere_observacion.string' => 'El valor especificado para el requerimiento de una observación no tiene un formato válido.',
            'requiere_observacion.in' => 'El valor especificado para el requerimiento de una observación no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $estado = tramite_servicio_estado::find($request->id);
                $estado->name = strtoupper($request->name);
                $estado->finaliza_servicio = $request->finaliza_servicio;
                $estado->requiere_observacion = $request->requiere_observacion;
                $estado->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Estado ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el estado.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function subirRecibos($servicioId)
    {
        return view('admin.tramites.solicitudes.subirRecibos', ['id' => $servicioId])->render();
    }

    public function vincularRecibos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cupl' => 'nullable|mimetypes:application/pdf|mimes:pdf|max:8000',
            'webservices' => 'nullable|mimetypes:application/pdf|mimes:pdf|max:8000',
            'consignacion' => 'nullable|mimetypes:application/pdf|mimes:pdf|max:8000',
            'observacion' => 'nullable|string',
            'tramite_servicio' => 'required|integer|exists:tramite_servicio,id',
            'numero_cupl' => 'required|numeric',
            'numero_sintrat' => 'required|numeric',
            'numero_consignacion' => 'required|numeric|unique:tramite_servicio_recibo,numero_consignacion'
        ], [
            'cupl.mimetypes' => 'El recibo CUPL no tiene un formato válido. Debe ser un archivo PDF.',
            'cupl.max' => 'El recibo CUPL no debe superar el tamaño máximo permitido de 8MB.',
            'cupl.mimes' => 'El recibo CUPL no tiene un formato válido. Debe ser un archivo PDF.',
            'webservices.mimetypes' => 'El recibo WEBSERVICES no tiene un formato válido. Debe ser un archivo PDF.',
            'webservices.max' => 'El recibo WEBSERVICES no debe superar el tamaño máximo permitido de 8MB.',
            'webservices.mimes' => 'El recibo WEBSERVICES no tiene un formato válido. Debe ser un archivo PDF.',
            'observacion.string' => 'La observación especificada no tiene un formato válido.',
            'tramite_servicio.required' => 'No se ha especificado el servicio al que se vincularán los recibos.',
            'tramite_servicio.integer' => 'El ID del servicio especificado no tiene un formato válido.',
            'tramite_servicio.exists' => 'El servicio especificado no existe en el sistema.',
            'consignacion.mimetypes' => 'La consignación no tiene un formato válido. Debe ser un archivo PDF.',
            'consignacion.max' => 'La consignación no debe superar el tamaño máximo permitido de 8MB.',
            'consignacion.mimes' => 'La consignación no tiene un formato válido. Debe ser un archivo PDF.',
            'numero_cupl.required' => 'No se ha especificado el número del CUPL.',
            'numero_cupl.numeric' => 'El número del CUPL no tiene un formato válido.',
            'numero_sintrat.required' => 'No se ha especificado el número del recibo WEBSERVICES.',
            'numero_sintrat.numeric' => 'El número del recibo WEBSERVICES no tiene un formato válido.',
            'numero_consignacion.required' => 'No se ha especificado el número de la consignación.',
            'numero_consignacion.numeric' => 'El número de la consignación especificada no tiene un formato válido.',
            'numero_consignaicon.unique' => 'El número de la consignación especificada ya está registrado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = false;
            try {
                $servicio = tramite_servicio::find($request->tramite_servicio);
                if ($servicio->hasEstados->count() <= 0) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede vincular recibos sin antes haber registrado un estado.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }
                /*if($servicio->hasFinalizacionActiva() != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede vincular recibos debido a que el servicio ya cuenta con un registro de finalización.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }*/
                \DB::beginTransaction();
                $cupl = null;
                $webservices = null;
                $consignacion = null;

                if($request->cupl != null){
                    $cupl = \Storage::disk('tramites')->putFile('/' . $request->tramite_servicio, $request->cupl);
                }

                if($request->webservices != null){
                    $webservices = \Storage::disk('tramites')->putFile('/' . $request->tramite_servicio, $request->webservices);
                }

                if($request->consignacion != null){
                    $consignacion = \Storage::disk('tramites')->putFile('/' . $request->tramite_servicio, $request->consignacion);
                }

                tramite_servicio_recibo::create([
                    'cupl' => $cupl,
                    'webservices' => $webservices,
                    'observacion' => $request->observacion,
                    'tramite_servicio_id' => $request->tramite_servicio,
                    'consignacion' => $consignacion,
                    'numero_consignacion' => $request->numero_consignacion,
                    'numero_sintrat' => $request->numero_sintrat,
                    'numero_cupl' => $request->numero_cupl,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                \DB::commit();
                $success = true;
            } catch (\Exception $e) {
                if($request->cupl != null){
                    \Storage::disk('tramites')->delete($cupl);
                }

                if($request->webservices != null){
                    \Storage::disk('tramites')->delete($webservices);
                }

                if($request->consignacion != null){
                    \Storage::disk('tramites')->delete($consignacion);
                }
                \DB::rollBack();
            }
            if ($success) {
                $servicio = tramite_servicio::find($request->tramite_servicio);
                $usuario = tramite_solicitud_usuario::where('tramite_solicitud_turno_id', $servicio->hasTramiteSolicitud->hasTurnoActivo()->id)->first();
                if ($usuario->correo_electronico != null) {
                    //\Mail::send(new TramiteServicioRecibos($servicio->hasSolicitud, $servicio, $usuario->correo_electronico));
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han vinculado los recibos.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar el proceso.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function obtenerRecibosServicio($id)
    {
        $recibos = tramite_servicio_recibo::where('tramite_servicio_id', $id)->get();
        return view('admin.tramites.solicitudes.listadoServiciosRecibos', ['recibos' => $recibos, 'id' => $id])->render();
    }

    public function obtenerServiciosSolicitud($solicitud_id)
    {
        $servicios = tramite_servicio::where('tramite_solicitud_id', $solicitud_id)->get();
        return view('admin.tramites.solicitudes.listadoServiciosSolicitud', ['servicios' => $servicios])->render();
    }

    public function agregarServicioSolicitud($solicitud_id)
    {
        $clasesVehiculo = vehiculo_clase::pluck('name', 'id');
        $tramites = tramite_solicitud::find($solicitud_id)->hasTramites;
        $serviciosVehiculo = vehiculo_servicio::pluck('name', 'id');
        return view('admin.tramites.solicitudes.agregarServicioSolicitud', ['clasesVehiculo' => $clasesVehiculo, 'serviciosVehiculo' => $serviciosVehiculo, 'id' => $solicitud_id, 'tramites' => $tramites])->render();
    }

    public function vincularServicioSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tramite_solicitud,id',
            'servicio_vehiculo' => 'required|integer|exists:vehiculo_servicio,id',
            'clase_vehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'placa' => 'required|string|min:6|max:6',
            'documento_propietario' => 'required|numeric',
            'tramites' => 'required|array'
        ], [
            'id.required' => 'No se ha especificado la solicitud a la que se le vinculará el servicio.',
            'id.integer' => 'El ID de la solicitud especificado no tiene un formato válido.',
            'id.exists' => 'La solicitud a la que se le vinculará el servicio no existe.',
            'servicio_vehiculo.required' => 'No se ha especificado el servicio del vehículo.',
            'servicio_vehiculo.integer' => 'El ID del servicio del vehículo especificado no tiene un formato válido.',
            'servicio_vehiculo.exists' => 'El servicio del vehículo especificado no existe en el sistema.',
            'clase_vehiculo.required' => 'No se ha especificado la clase del vehículo.',
            'clase_vehiculo.integer' => 'El ID de la clase del vehículo especificada no tiene un formato válido.',
            'clase_vehiculo.exists' => 'La clase del vehículo especificada no existe en el sistema.',
            'placa.required' => 'No se ha especificado la placa.',
            'placa.string' => 'La placa no tiene un formato válido.',
            'placa.min' => 'La placa no tiene una longitud mínima de :min caracteres.',
            'placa.max' => 'La placa no tiene una longitud máxima de :max caracteres.',
            'documento_propietario.required' => 'No se ha especificado el número de documento del propietario.',
            'documento_propietario.numeric' => 'El número de documento del propietario especificado no tiene un formato válido.',
            'tramites.required' => 'No se han especificado los tramites del servicio.',
            'tramites.array' => 'Los tramites del servicio no tienen un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try {
            $solicitud = tramite_solicitud::find($request->id);
            if(($solicitud->hasServicios->count() + $solicitud->hasLicencias->count()) >= $solicitud->servicios){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ya se han registrado la cantidad máxima de servicios.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
            if (count($solicitud->hasServicios->filter(function ($data) use ($request) {
                return $data->placa == $request->placa;
            })) > 0) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede registrar dos servicios con la misma placa en la misma solicitud.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
            $servicio = tramite_servicio::create([
                'tramite_solicitud_id' => $request->id,
                'vehiculo_servicio_id' => $request->servicio_vehiculo,
                'vehiculo_clase_id' => $request->clase_vehiculo,
                'placa' => $request->placa,
                'documento_propietario' => $request->documento_propietario,
                'funcionario_id' => auth()->user()->id
            ]);
            $servicio->hasTramites()->sync($request->tramites);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se han agregado el servicio.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido realizar el proceso.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function verCUPL($id)
    {
        $recibo = tramite_servicio_recibo::find($id);
        if($recibo->hasTramiteServicio->funcionario_id == auth()->user()->id){
            $name = explode('/', $recibo->cupl);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="' . array_last($name) . '"',
            ];

            return response()->download(storage_path('app/tramites/' . $recibo->cupl), array_last($name), $headers);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No está autorizado su acceso a este elemento.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function verSINTRAT($id)
    {
        $recibo = tramite_servicio_recibo::find($id);
        if ($recibo->hasTramiteServicio->funcionario_id == auth()->user()->id) {
            $name = explode('/', $recibo->webservices);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="' . array_last($name) . '"',
            ];

            return response()->download(storage_path('app/tramites/' . $recibo->webservices), array_last($name), $headers);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No está autorizado su acceso a este elemento.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function verCONSIGNACION($id)
    {
        $recibo = tramite_servicio_recibo::find($id);
        if ($recibo->hasTramiteServicio->funcionario_id == auth()->user()->id) {
            $name = explode('/', $recibo->consginacion);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="' . array_last($name) . '"',
            ];

            return response()->download(storage_path('app/tramites/' . $recibo->consginacion), array_last($name), $headers);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No está autorizado su acceso a este elemento.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function verServicios($id)
    {
        $solicitud = tramite_solicitud::find($id);
        return view('admin.tramites.solicitudes.verServicios',['solicitud'=>$solicitud])->render();
    }

    public function verEstadosServicio($servicioId)
    {
        $servicio = tramite_servicio::with('hasEstados')->find($servicioId);
        return view('admin.tramites.solicitudes.verEstadosServicio', ['estados' => $servicio->hasEstados])->render();
    }

    public function verCarpetasServicio($servicioId)
    {
        $servicio = tramite_servicio::with('hasSolicitudesCarpeta')->find($servicioId);
        return view('admin.tramites.solicitudes.verCarpetasServicio', ['solicitudes' => $servicio->hasSolicitudesCarpeta, 'id' => $servicio->id])->render();
    }

    public function verRecibosServicio($servicioId)
    {
        $recibos = tramite_servicio_recibo::where('tramite_servicio_id',$servicioId)->get();
        return view('admin.tramites.solicitudes.verRecibosServicio', ['recibos' => $recibos])->render();
    }

    public function verFinalizacionServicio($servicioId)
    {
        $servicio = tramite_servicio::with('hasFinalizacion')->find($servicioId);
        return view('admin.tramites.solicitudes.verFinalizacionServicio', ['servicio' => $servicio])->render();
    }

    public function anularSustratoF1($finalizacionId, $sustratoId)
    {
        $motivos = sustrato_anulacion_motivo::pluck('name','id');
        return view('admin.tramites.solicitudes.anularSustrato', ['motivos'=>$motivos, 'finalizacionId'=>$finalizacionId, 'sustratoId'=>$sustratoId])->render();
    }

    public function anularSustratoF2(Request $request)
    {
        $sustratoAnterior = sustrato::find($request->sustratoId);

        $proceso = $sustratoAnterior->hasConsumo;

        if($sustratoAnterior->proceso_type == 'App\tramite_servicio_finalizacion'){
            $tramitesSustratos = $proceso->hasTramiteServicio->hasTramites->filter(function ($data) {
                return $data->requiere_sustrato == 'SI';
            });

            $sustrato = sustrato::where('consumido', 'NO')->where('tipo_sustrato_id', $tramitesSustratos->first()->tipo_sustrato_id)->doesntHave('hasAnulacion')->first();
        }else{
            $sustrato = sustrato::whereHas('hasTipoSustrato', function($query){
                $query->where('name', 'LICENCIA DE CONDUCCION');
            })->where('consumido', 'NO')->doesntHave('hasAnulacion')->orderBy('numero')->first();
        }

        if ($sustrato == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No hay sustratos disponibles.'],
                'encabezado' => '¡Error!',
            ], 200);
        }else{
            try{
                \DB::beginTransaction();
                $sustrato->consumido = ('SI');
                $sustrato->proceso_id = $proceso->id;
                $sustrato->proceso_type = $sustratoAnterior->proceso_type;
                $sustrato->save();
                $proceso->sustrato_id = $sustrato->id;
                $proceso->save();
                $sustratoAnterior->consumido = 'NO';
                $sustratoAnterior->proceso_id = null;
                $sustratoAnterior->proceso_type = null;
                $sustratoAnterior->save();
                sustrato_anulacion::create([
                    'sustrato_id' => $sustratoAnterior->id,
                    'sustrato_anulacion_motivo_id' => $request->motivo_anulacion,
                    'observacion' => $request->observacion
                ]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha anulado el sustrato.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch(\Exception $e){
                \DB::rollback();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido anular el sustrato.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }            
        }
    }

    public function reLlamarTurnoF1($ventanilla)
    {
        $criterios = [
            1 => 'Placa',
            2 => 'Turno',
            3 => 'Número documento'
        ];

        return view('admin.tramites.solicitudes.reLlamarTurno', ['criterios'=>$criterios, 'ventanilla'=>$ventanilla])->render();
    }

    public function reLlamarTurnoF2(Request $request)
    {
        $turno = null;
        if($request->criterio == 1){
            /*$turno = tramite_solicitud_turno::whereHas('hasSolicitud', function($query) use ($request){
                $query->whereHas('hasServicios', function($query2) use ($request){
                    $query2->where('placa', $request->valor);
                });
            })
            ->whereHas('hasAtencion', function($query3){
                $query3->where('terminacion', 2)->orWhere('terminacion', 3);
            })
            ->where('fecha_llamado', '!=', null)
            ->where('fecha_anulacion', null)
            ->where('fecha_vencimiento', null)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();*/
        }elseif($request->criterio == 2){
            $turno = tramite_solicitud_turno::where('turno', $request->valor)
            ->has('hasAtencion')
            ->whereHas('hasSolicitud', function($query3){
                $query3->doesntHave('hasServicios')->doesntHave('hasLicencias');
            })            
            ->where('fecha_llamado', '!=', null)
            ->where('fecha_anulacion', null)
            ->where('fecha_vencimiento', null)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
        }elseif($request->criterio == 3){
            /*$turno = tramite_solicitud_turno::whereHas('hasUsuarioSolicitante', function($query) use ($request){
                $query->where('numero_documento', $request->valor);
            })
            ->whereHas('hasAtencion', function($query3){
                $query3->where('terminacion', 2)->orWhere('terminacion', 3);
            })
            ->where('fecha_llamado', '!=', null)
            ->where('fecha_anulacion', null)
            ->where('fecha_vencimiento', null)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();*/
        }

        if($turno != null){
            $ventanilla = ventanilla::find($request->ventanilla);
            $turno->fecha_rellamado = date('Y-m-d H:i:s');
            $turno->funcionario_rellamado_id = auth()->user()->id;
            $turno->save();
            $turno->asignarTurno($ventanilla->id);
            event(new turnoAsignado($turno, $ventanilla));
            event(new tramiteSolicitudAsignado(json_encode($turno->hasSolicitud)));
            $estados = tramite_servicio_estado::all();

            return view('admin.tramites.solicitudes.panelAtencionTurno', [
                'turno' => $turno,
                'estados' => $estados,
            ])->render();
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El turno a llamar no existe o está indisponible.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function filtrarSolicitudes($valor, $filtro)
    {
        $tramites = [];
        switch ($filtro){
            case 1: $tramites = tramite_solicitud::whereHas('hasTurnos', function($query) use ($valor){
                $query->whereHas('hasUsuarioSolicitante', function($query2) use ($valor){
                    $query2->where('numero_documento', $valor);
                });
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 2: $tramites = tramite_solicitud::whereHas('hasServicios', function($query) use ($valor){
                $query->where('placa', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 3: $tramites = tramite_solicitud::whereHas('hasTurnos', function($query) use ($valor){
                $query->where('turno', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
        }

        return view('admin.tramites.solicitudes.listadoTramites', ['tramites' => $tramites])->render();
    }

    public function filtrarMisTramites($valor, $filtro)
    {
        $misTramites = [];
        switch ($filtro){
            case 1: $misTramites = tramite_solicitud::whereHas('hasFuncionariosAsignados', function ($query) {
                $query->where('id', auth()->user()->id);
            })->whereHas('hasTurnos', function($query) use ($valor){
                $query->whereHas('hasUsuarioSolicitante', function($query2) use ($valor){
                    $query2->where('numero_documento', $valor);
                });
            })->with('hasRadicados', 'hasTramites', 'hasTramiteGrupo', 'hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasTurnos.hasOrigen')->orderBy('created_at', 'desc')->paginate(50);
            break;
            case 2: $misTramites = tramite_solicitud::whereHas('hasFuncionariosAsignados', function ($query) {
                $query->where('id', auth()->user()->id);
            })->whereHas('hasServicios', function($query) use ($valor){
                $query->where('placa', $valor);
            })->with('hasRadicados', 'hasTramites', 'hasTramiteGrupo', 'hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasTurnos.hasOrigen')->orderBy('created_at', 'desc')->paginate(50);
            break;
            case 3: $misTramites = tramite_solicitud::whereHas('hasFuncionariosAsignados', function ($query) {
                $query->where('id', auth()->user()->id);
            })->whereHas('hasTurnos', function($query) use ($valor){
                $query->where('turno', $valor);
            })->with('hasRadicados', 'hasTramites', 'hasTramiteGrupo', 'hasTurnos', 'hasTurnos.hasUsuarioSolicitante', 'hasTurnos.hasOrigen')->orderBy('created_at', 'desc')->paginate(50);
            break;
        }
        
        return view('admin.tramites.solicitudes.listadoMisTramites', ['misTramites' => $misTramites])->render();
    }

    public function solicitarDescanso()
    {
        $motivos = funcionario_descanso_motivo::pluck('name','id');
        return view('admin.tramites.solicitudes.solicitarDescanso', ['motivos'=>$motivos])->render();
    }

    public function registrarDescanso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motivo' => 'required|integer|exists:funcionario_descanso_motivo,id'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $descansosRegistrados = null;
        if(Carbon::now() > date('Y-m-d 12:00:00')){
            $descansosRegistrados = funcionario_descanso::whereDate('created_at', '>=', date('Y-m-d 07:00:00'))->whereDate('created_at', '<=', date('Y-m-d 12:00:00'))->where('funcionario_id', auth()->user()->id)->get();
        }else{
            $descansosRegistrados = funcionario_descanso::whereDate('created_at', '>=', date('Y-m-d 12:00:00'))->whereDate('created_at', '<=', date('Y-m-d 17:00:00'))->where('funcionario_id', auth()->user()->id)->get();
        }

        if($descansosRegistrados == null){
            $descanso = $this->registrarDescansoFuncionario($request);
        }elseif(count($descansosRegistrados) < 2){
            $descanso = $this->registrarDescansoFuncionario($request);
        }else{
            return json_encode('NO');
        }

        return json_encode($descanso->hasMotivo->minutes);
    }

    private function registrarDescansoFuncionario($request)
    {
        return funcionario_descanso::create([
            'funcionario_id' => auth()->user()->id,
            'fun_descanso_motivo_id' => $request->motivo,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function obtenerLicenciasSolicitud($id)
    {
        $solicitud = tramite_solicitud::find($id);
        return view('admin.tramites.solicitudes.listadoLicencias', ['licencias'=>$solicitud->hasLicencias])->render();
    }

    public function nuevaLicenciaSolicitud($id)
    {
        $categorias = licencia_categoria::all();
        return view('admin.tramites.solicitudes.registrarLicencia', ['categorias'=>$categorias, 'id'=>$id])->render();
    }

    public function registrarLicenciaSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categorias' => 'required|array',
            'solicitud' => 'required|integer|exists:tramite_solicitud,id',
            'consignacion' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'cupl' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'webservices' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'numero_consignacion' => 'required|string|unique:tramite_licencia,numero_consignacion,NULL,id,deleted_at,NULL',
            'numero_cupl' => 'required|string|unique:tramite_licencia,numero_cupl,NULL,id,deleted_at,NULL',
            'numero_sintrat' => 'required|string|unique:tramite_licencia,numero_sintrat,NULL,id,deleted_at,NULL'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $tramiteSolicitud = tramite_solicitud::find($request->solicitud);

        if(($tramiteSolicitud->hasServicios->count() + $tramiteSolicitud->hasLicencias->count()) >= $tramiteSolicitud->servicios){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ya ha registrado la cantidad máxima de servicios especificada en la solicitud.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $sustratosDisponibles = sustrato::whereHas('hasTipoSustrato', function($query){
            $query->where('name', 'LICENCIA DE CONDUCCION');
        })->where('consumido', 'NO')->doesntHave('hasAnulacion')->orderBy('numero')->get();

        if(count($sustratosDisponibles) == 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No hay sustratos disponibles para las licencias de conducción.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            \DB::beginTransaction();
            $licencia = tramite_licencia::create([
                'tramite_solicitud_id' => $request->solicitud,
                'sustrato_id' => $sustratosDisponibles->first()->id,
                'funcionario_id' => auth()->user()->id,
                'turno_id' => $tramiteSolicitud->hasTurnos->last()->id,                
                'numero_consignacion' => $request->numero_consignacion,
                'numero_cupl' => $request->numero_cupl,
                'numero_sintrat' => $request->numero_sintrat
            ]);

            $sustrato = $sustratosDisponibles->first();
            $sustrato->proceso_id = $licencia->id;
            $sustrato->proceso_type = 'App\\tramite_licencia';
            $sustrato->consumido = 'SI';
            $sustrato->save();

            $licencia->hasCategorias()->sync($request->categorias);

            $cupl = null;
            $webservices = null;
            $consignacion = null;

            if($request->cupl != null){
                $cupl = \Storage::disk('tramites')->putFile('/' . $request->solicitud, $request->cupl);
            }

            if($request->webservices != null){
                $webservices = \Storage::disk('tramites')->putFile('/' . $request->solicitud, $request->webservices);
            }

            if($request->consignacion != null){
                $consignacion = \Storage::disk('tramites')->putFile('/' . $request->solicitud, $request->consignacion);
            }

            $licencia->cupl = $cupl;
            $licencia->webservices = $webservices;
            $licencia->consignacion = $consignacion;
            $licencia->save();

            \DB::commit();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha registrado la licencia.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            \DB::rollBack();
            if($request->cupl != null){
               \Storage::disk('tramites')->delete($cupl);
            }

            if($request->webservices != null){
                \Storage::disk('tramites')->delete($webservices);
            }

            if($request->consignacion != null){
                \Storage::disk('tramites')->delete($consignacion);
            }
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error al procesar la solicitud.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function obtenerListadoMotivosDescanso()
    {
        $motivosDescanso = funcionario_descanso_motivo::paginate(15);
        return view('admin.tramites.solicitudes.listadoMotivosDescanso', ['motivos'=>$motivosDescanso])->render();
    }

    public function nuevoMotivoDescanso()
    {
        return view('admin.tramites.solicitudes.nuevoMotivoDescanso')->render();
    }

    public function crearMotivoDescanso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:funcionario_descanso_motivo'
        ], [
            'name.required' => 'No se ha especificado el nombre para el origen de la solicitud.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            funcionario_descanso_motivo::create([
                'name' => $request->name,
                'minutes' => $request->minutos
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el motivo descanso.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el motivo descanso.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function editarMotivoDescanso($id)
    {
        $motivoDescanso = funcionario_descanso_motivo::find($id);
        return view('admin.tramites.solicitudes.editarMotivoDescanso', ['motivo'=>$motivoDescanso])->render();
    }

    public function actualizarMotivoDescanso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:funcionario_descanso_motivo,id',
            'name' => ['required','string',Rule::unique('funcionario_descanso_motivo','name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el motivo a modificar.',
            'id.integer' => 'El ID del motivo a modificar no tiene un formato válido.',
            'id.exists' => 'El motivo especificado no existe.',
            'name.required' => 'No se ha especificado el nombre para el origen de la solicitud.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $motivoDescanso = funcionario_descanso_motivo::find($request->id);
            $motivoDescanso->name = $request->name;
            $motivoDescanso->minutes = $request->minutos;
            $motivoDescanso->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el motivo descanso.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el motivo descanso.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function generarDevolucionTramite(Request $request)
    {
        $headerHtml = View()->make('plantillas.header')->render();
        $footerHtml = View()->make('plantillas.footer')->render();
        $datos = json_decode($request->data);
        $servicio = tramite_servicio::find($datos->servicio);
        $pdf = PDF::loadView('admin.tramites.solicitudes.plantillaDevolucionTramite', ['servicio'=>$servicio,'estados'=>$servicio->hasEstados->whereIn('id', $datos->estados)])->setOption('margin-bottom', 28)->setOption('margin-top', 35)->setOption('margin-right', 2)->setOption('margin-left', 2)->setOption('header-html', $headerHtml)->setOption('footer-html', $footerHtml)->setPaper('letter');
        return $pdf->download('file.pdf');
    }

    public function verLicencias($id)
    {
        $servicioSolicitud = tramite_solicitud::find($id);
        return view('admin.tramites.solicitudes.verLicencias', ['licencias'=>$servicioSolicitud->hasLicencias])->render();
    }

    public function editarSolicitante($id)
    {
        $tiposDocumentos = usuario_tipo_documento::pluck('name','id');
        $usuario = tramite_solicitud_usuario::where('tramite_solicitud_turno_id', $id)->first();
        return view('admin.tramites.solicitudes.editarSolicitante', ['usuario'=>$usuario, 'tiposDocumentos'=>$tiposDocumentos])->render();
    }

    public function actualizarSolicitante(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'solicitante' => 'required|integer|exists:tramite_solicitud_usuario,id',
            'nombre' => 'required|string',
            'numero_documento' => 'required|numeric',
            'correo' => 'email|nullable',
            'telefono' => 'numeric|nullable',
            'tipo_documento' => 'integer|exists:usuario_tipo_documento,id'
        ], [
            'nombre.required' => 'No se ha especificado el nombre del usuario.',
            'nombre.string' => 'El nombre del usuario especificado no tiene un formato válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad del usuario.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'correo.email' => 'El correo electrónico del usuario especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el número telefónico del usuario.',
            'telefono.numeric' => 'El número telefónico del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.required' => 'No se ha especificado el tipo de documento de identidad del usuario.',
            'tipo_documento_identidad.integer' => 'El ID del tipo de documento de identidad del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.exists' => 'El tipo de documento de identidad del usuario especificado no existe en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }     

        try{
            $usuario = tramite_solicitud_usuario::find($request->solicitante);
            $usuario->nombre_usuario = strtoupper($request->nombre);
            $usuario->numero_documento = $request->numero_documento;
            $usuario->correo_electronico = $request->correo;
            $usuario->numero_telefonico = $request->telefono;
            $usuario->tipo_documento_identidad_id = $request->tipo_documento;
            $usuario->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el solicitante.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el solicitante.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }        
    }

    public function editarLicenciaSolicitud($id)
    {
        $categorias = licencia_categoria::all();
        $licencia = tramite_licencia::find($id);
        return view('admin.tramites.solicitudes.editarLicencia', ['categorias'=>$categorias, 'licencia'=>$licencia])->render();
    }

    public function actualizarLicenciaSolicitud(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'licencia' => 'required|integer|exists:tramite_licencia,id',
            'categorias' => 'required|array',
            'consignacion' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'cupl' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'webservices' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'numero_consignacion' => 'required|string|unique:tramite_licencia,numero_consignacion,NULL,id,deleted_at,NULL',
            'numero_cupl' => 'required|string|unique:tramite_licencia,numero_cupl,NULL,id,deleted_at,NULL',
            'numero_sintrat' => 'required|string|unique:tramite_licencia,numero_sintrat,NULL,id,deleted_at,NULL'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            \DB::beginTransaction();
            $licencia = tramite_licencia::find($request->licencia);
            $licencia->numero_consignacion = $request->numero_consignacion;
            $licencia->numero_cupl = $request->numero_cupl;
            $licencia->numero_sintrat = $request->numero_sintrat;

            $licencia->hasCategorias()->sync($request->categorias);

            if($request->cupl != null){
                $licencia->cupl = \Storage::disk('tramites')->putFile('/' . $licencia->tramite_solicitud_id, $request->cupl);
            }

            if($request->webservices != null){
                $licencia->webservices = \Storage::disk('tramites')->putFile('/' . $licencia->tramite_solicitud_id, $request->webservices);
            }

            if($request->consignacion != null){
                $licencia->consignacion = \Storage::disk('tramites')->putFile('/' . $licencia->tramite_solicitud_id, $request->consignacion);
            }

            $licencia->save();

            \DB::commit();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la licencia.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            if($request->cupl != null){
               \Storage::disk('tramites')->delete($licencia->cupl);
            }

            if($request->webservices != null){
                \Storage::disk('tramites')->delete($licencia->webservices);
            }

            if($request->consignacion != null){
                \Storage::disk('tramites')->delete($licencia->consignacion);
            }

            \DB::rollback();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error al procesar la solicitud.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }
}