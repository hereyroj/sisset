<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\tramiteSolicitudAsignado;
use App\Events\turnoAsignado;
use App\Events\turnoGenerado;
use App\tramite_solicitud;
use App\tramite_servicio_estado;
use App\tramite_solicitud_origen;
use App\tramite_solicitud_turno;
use App\tramite_solicitud_usuario;
use App\usuario_tipo_documento;
use App\ventanilla;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class TurnoController extends Controller
{
    public function llamarTurno()
    {
        if (auth()->user()->hasTurnoActivo() != null) {
            return view('admin.tramites.solicitudes.panelAtencionTurno', [
                'turno' => auth()->user()->hasTurnoActivo()
            ])->render();
        } else {
            $ventanilla = ventanilla::with('hasTramitesGruposAsignados')->whereHas('hasFuncionariosAsignados', function ($query) {
                $query->where('funcionario_id', auth()->user()->id)->where('libre', 'NO');
            })->first();

            if ($ventanilla != null) {
                $tramitesGrupos = $ventanilla->hasTramitesGruposAsignados->groupBy('pivot.prioridad');
                $turno = null;
                foreach ($tramitesGrupos as $group){
                    $turno = tramite_solicitud_turno::with('hasOrigen','hasSolicitud', 'hasSolicitud.hasServicios', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta.hasCarpetaPrestada', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta.hasCarpetaPrestada.hasCarpeta', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta.hasCarpetaPrestada.hasFuncionarioEntrega', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta.hasCarpetaPrestada.hasFuncionarioRecibe', 'hasSolicitud.hasServicios.hasSolicitudesCarpeta.hasCarpetaPrestada.hasFuncionarioAutoriza', 'hasUsuarioSolicitante', 'hasUsuarioSolicitante.hasTipoDocumentoIdentidad', 'hasUsuarioSolicitante.hasFuncionario')
                        ->where('fecha_llamado', null)
                        ->where('fecha_anulacion', null)
                        ->where('fecha_vencimiento', null)
                        ->whereHas('hasSolicitud', function ($query) use ($group) {
                            $query->whereHas('hasTramiteGrupo', function ($subQuery) use ($group) {
                                $subQuery->whereIn('id', $group->pluck('id'));
                            });
                        })
                        ->orderBy('preferente', 'desc')->orderBy('created_at')->first();
                    if($turno != null){
                        break;
                    }
                }

                if ($turno != null) {
                    $turno->fecha_llamado = date('Y-m-d H:i:s');
                    $turno->save();
                    $turno->asignarTurno($ventanilla->id);
                    event(new turnoAsignado($turno, $ventanilla));
                    event(new tramiteSolicitudAsignado(json_encode($turno->hasSolicitud)));
                    $estados = tramite_servicio_estado::all();

                    return view('admin.tramites.solicitudes.panelAtencionTurno', [
                        'turno' => $turno,
                        'estados' => $estados,
                    ])->render();
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No hay turnos disponibles en este momento.'],
                        'encabezado' => 'Libre',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No estas asignado a una ventanilla en este momento.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function nuevoTurno($solicitud_id)
    {
        $solicitud = tramite_solicitud::find($solicitud_id);
        if($solicitud->hasTurnoActivo() == null){
            if($solicitud->getEstadoSolicitud() === 'pendiente carpeta' || $solicitud->getEstadoSolicitud() === 'en pago' || $solicitud->getEstadoSolicitud() === 'por atender'){
                $tiposDocumentos = usuario_tipo_documento::pluck('name', 'id');
                $tramitesSolicitudOrigenes = tramite_solicitud_origen::pluck('name', 'id');
                return view('admin.tramites.solicitudes.nuevoTurno', ['solicitud_id'=>$solicitud_id, 'tiposDocumentos'=>$tiposDocumentos, 'tramitesSolicitudOrigenes'=>$tramitesSolicitudOrigenes])->render();
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede crear un nuevo turno a a la solicitud especificada, debido a que fue marcada como anulada o finalizada.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se puede crear un nuevo turno a a la solicitud especificada, debido a que actualmente tiene un turno activo.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function crearNuevoTurno(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'solicitud_id' => 'required|integer|exists:tramite_solicitud,id',
            'nombre_usuario' => 'required|string',
            'numero_documento' => 'required|numeric',
            'correo_electronico' => 'nullable|email',
            'numero_telefonico' => 'nullable|numeric',
            'tipo_documento_identidad' => 'integer|exists:usuario_tipo_documento,id',
            'tramite_solicitud_origen' => 'required|integer|exists:tramite_solicitud_origen,id',
            'preferente' => ['required','integer', Rule::in(['0','1'])]
        ], [
            'solicitud_id.required' => 'No se ha especificado la solicitud.',
            'solicitud_id.integer' => 'El ID de la solicitud especificada no tiene un formato válido.',
            'solicitud_id.exists' => 'La solicitud especificado no existe en el sistema.',
            'tipo_usuario.required' => 'No se ha especificado el tipo de usuario que realiza la solicitud.',
            'tipo_usuario.min' => 'El formato del tipo de usuario no tiene un formato válido. Debe tener mínimo :min caracteres.',
            'tipo_usuario.max' => 'El formato del tipo de usuario no tiene un formato válido. Debe tener máximo :max caracteres.',
            'nombre_usuario.required' => 'No se ha especificado el nombre del usuario.',
            'nombre_usuario.string' => 'El nombre del usuario especificado no tiene un formato válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad del usuario.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'correo_electronico.required' => 'No se ha especificado el correo electrónico del usuario.',
            'correo_electronico.email' => 'El correo electrónico del usuario especificado no tiene un formato válido.',
            'numero_telefonico.required' => 'No se ha especificado el número telefónico del usuario.',
            'numero_telefonico.numeric' => 'El número telefónico del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.required' => 'No se ha especificado el tipo de documento de identidad del usuario.',
            'tipo_documento_identidad.integer' => 'El ID del tipo de documento de identidad del usuario especificado no tiene un formato válido.',
            'tipo_documento_identidad.exists' => 'El tipo de documento de identidad del usuario especificado no existe en el sistema.',
            'tramite_solicitud_origen.required' => 'No se ha especificado el origen de la solicitud.',
            'tramite_solicitud_origen.integer' => 'El ID del origen de la solicitud especificado no tiene un formato válido.',
            'tramite_solicitud_origen.exists' => 'El origen de la solicitud especificado no existe en el sistema.',
            'preferente.required' => 'No se ha especificado si el turno es preferente.',
            'preferente.integer' => 'El valor especificado en el campo preferente no es válido.',
            'preferente.in' => 'El valor especificado en el campo preferente no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try {
                \DB::beginTransaction();
                $solicitud = tramite_solicitud::find($request->solicitud_id);
                $turno_activo = $solicitud->hasTurnoActivo();
                $turno_pendiente = $solicitud->hasTurnoPendiente();
                if ($turno_activo == null) {
                    if ($turno_pendiente == null) {
                        try{
                            $ultimo_turno_tramite = tramite_solicitud_turno::whereDate('created_at', date('Y-m-d'))->where('turno', 'like', $solicitud->hasTramiteGrupo->code.'%')->orderBy('created_at', 'desc')->first();
                            if ($ultimo_turno_tramite == null) {
                                $numero = '001';
                            } else {
                                //Establecemos el nuevo numero de radicado. Se eliminan los ceros de la izquierda primero y luego se aumenta en 1
                                $numero = preg_replace("/[^0-9,.]/", "", $ultimo_turno_tramite->turno);
                                $numero = ltrim($numero, "0");
                                ++$numero;
                                //Se vuelven a agregar los ceros a la izquierda
                                $numero = sprintf("%'.03d\n", $numero);
                            }
                            $turno = tramite_solicitud_turno::create([
                                'turno' => substr($solicitud->hasTramiteGrupo->code, 0, 3) . $numero,
                                'tramite_solicitud_id' => $solicitud->id,
                                'tramite_solicitud_origen_id' => $request->tramite_solicitud_origen,
                                'preferente' => $request->preferente
                            ]);

                            tramite_solicitud_usuario::create([
                                'tramite_solicitud_turno_id' => $turno->id,
                                'nombre_usuario' => strtoupper($request->nombre_usuario),
                                'numero_documento' => $request->numero_documento,
                                'correo_electronico' => $request->correo_electronico,
                                'numero_telefonico' => $request->numero_telefonico,
                                'tipo_documento_identidad_id' => $request->tipo_documento_identidad
                            ]);
                            \DB::commit();
                            event(new turnoGenerado($turno, $solicitud));
                            $this->imprimirTicket($turno);
                            return response()->view('admin.mensajes.success', [
                                'mensaje' => 'Se ha generado el turno '.$turno->turno,
                                'encabezado' => '¡Completado!',
                            ], 200);
                        }catch (\Exception $e){
                            \DB::rollBack();
                            return response()->view('admin.mensajes.errors', [
                                'errors' => ['Ha ocurrido un error en la impresión del ticket. Se ha generado el turno '.$turno->turno],
                                'encabezado' => '¡Error!',
                            ], 200);
                        }
                    } else {
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['No se ha podido generar el nuevo turno debido a que la solicitud de tramite ya tiene un turno pendiente: '.$turno_pendiente->turno],
                            'encabezado' => '¡Error!',
                        ], 200);
                    }
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['La solicitud de tramite ya tiene actualmente un turno activo: '.$turno_activo->turno],
                        'encabezado' => '¡Error!',
                    ], 200);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido generar el turno. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function reImprimirTurno($turnoId)
    {
        try{
            $turno = tramite_solicitud_turno::with('hasSolicitud', 'hasUsuarioSolicitante')->find($turnoId);
            if($this->imprimirTicket($turno)){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha re-impreso el turno '.$turno->turno,
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido re-imprimir el turno.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }catch (\Exception $e){
            return false;
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

    public function verTurno($id)
    {
        $turno = tramite_solicitud_turno::with('hasUsuarioSolicitante', 'hasAtencion', 'hasAtencion.hasFuncionario', 'hasFuncionarioReLlamado')->find($id);
        return view('admin.tramites.solicitudes.verTurno', ['turno'=>$turno])->render();
    }
}
