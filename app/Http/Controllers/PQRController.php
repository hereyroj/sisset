<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\departamento;
use App\dependencia;
use App\empresa_mensajeria;
use App\Events\FuncionarioPQR;
use App\Events\nuevoCoEx;
use App\gd_medio_traslado;
use App\gd_pqr_anulacion;
use App\gd_pqr_anulacion_motivo;
use App\gd_pqr_asignacion;
use App\gd_pqr_clasificacion;
use App\gd_pqr_entrega;
use App\gd_pqr_envio;
use App\gd_pqr_modalidad_envio;
use App\gd_pqr_peticionario;
use App\gd_radicado_entrada;
use App\gd_radicado_salida;
use App\Mail\anulacionProceso;
use App\Mail\anulacionRespuestaPQR;
use App\Mail\cambioFechaLimitePQR;
use App\Mail\DesvinculacionRadicadoRespuesta;
use App\Mail\radicadoPQR;
use App\Notifications\AsignacionPQR;
use App\Notifications\NuevoWebPQR;
use App\Notifications\ReasignacionPQR;
use App\report;
use App\trd_documento_serie;
use App\User;
use App\usuario_tipo_documento;
use App\sistema_parametros_vigencia;
use Validator;
use Illuminate\Validation\Rule;
use App\gd_pqr;
use App\gd_pqr_clase;
use Carbon\Carbon;
use App\calendario;
use Storage;
use App\Mail\RespuestaPQR;
use Webpatser\Uuid\Uuid;

class PQRController extends Controller
{
    public function radicar()
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::orderBy('name', 'asc')->pluck('name', 'id');
        $departamentos = departamento::orderBy('name', 'asc')->pluck('name', 'id');
        $clasesPQR = gd_pqr_clase::orderBy('name', 'asc')->pluck('name', 'id');

        return view('publico.pqr.frmRadicar', [
            'tiposDocumentosIdentidad' => $tiposDocumentosIdentidad,
            'departamentos' => $departamentos,
            'clasesPQR' => $clasesPQR,
        ]);
    }

    public function administrar()
    {
        return view('admin.gestion_documental.pqr.administrar');
    }

    private function complementosCoEx()
    {
        $departamentos = departamento::orderBy('name', 'asc')->pluck('name', 'id');
        $clasesPQR = gd_pqr_clase::orderBy('name', 'asc')->pluck('name', 'id');
        $mediosTraslado = gd_medio_traslado::orderBy('name', 'asc')->pluck('name', 'id');
        $tiposDocumentosIdentidad = usuario_tipo_documento::orderBy('name', 'asc')->pluck('name', 'id');
        return [
            'tiposDocumentosIdentidad' => $tiposDocumentosIdentidad,
            'departamentos' => $departamentos,
            'clasesPQR' => $clasesPQR,
            'mediosTraslado' => $mediosTraslado,
        ];
    }

    public function nuevoCoEx()
    {
        return view('admin.gestion_documental.pqr.nuevoCoEx', $this->complementosCoEx())->render();
    }

    private function complementosCoIn()
    {
        $funcionarios = User::where('lock_session', 'no')->orderBy('name', 'asc')->pluck('name', 'id');
        $clasesPQR = gd_pqr_clase::orderBy('name', 'asc')->pluck('name', 'id');
        $mediosTraslado = gd_medio_traslado::orderBy('name', 'asc')->pluck('name', 'id');
        return [
            'funcionarios' => $funcionarios,
            'clasesPQR' => $clasesPQR,
            'mediosTraslado' => $mediosTraslado,
        ];
    }

    public function nuevoCoIn()
    {
        return view('admin.gestion_documental.pqr.nuevoCoIn', $this->complementosCoIn())->render();
    }

    private function complementosCoSa()
    {
        $funcionarios = User::where('lock_session', 'no')->orderBy('name', 'asc')->pluck('name', 'id');
        $clasesPQR = gd_pqr_clase::orderBy('name', 'asc')->pluck('name', 'id');
        $mediosTraslado = gd_medio_traslado::orderBy('name', 'asc')->pluck('name', 'id');
        $empresasEnvios = empresa_mensajeria::orderBy('name', 'asc')->pluck('name', 'id');
        $modalidadesEnvios = gd_pqr_modalidad_envio::orderBy('name', 'asc')->pluck('name', 'id');
        return [
            'funcionarios' => $funcionarios,
            'clasesPQR' => $clasesPQR,
            'mediosTraslado' => $mediosTraslado,
            'modalidadesEnvios' => $modalidadesEnvios,
            'empresasEnvios' => $empresasEnvios
        ];
    }

    public function nuevoCoSa()
    {
        return view('admin.gestion_documental.pqr.nuevoCoSa', $this->complementosCoSa())->render();
    }

    public function asignar($id)
    {
        $dependencias = dependencia::with('hasFuncionarios')->orderBy('name', 'asc')->get();
        return view('admin.gestion_documental.pqr.asignarPQR', [
            'dependencias' => $dependencias,
            'id' => $id,
        ])->render();
    }

    public function reAsignar($id)
    {
        $dependencias = dependencia::with('hasFuncionarios')->orderBy('name', 'asc')->get();
        $pqr = gd_pqr::find($id);

        return view('admin.gestion_documental.pqr.reAsignar', [
            'dependencias' => $dependencias,
            'id' => $id,
            'pqr' => $pqr
        ])->render();
    }

    public function clasificar($id)
    {
        $series = trd_documento_serie::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.gestion_documental.pqr.clasificarPQR', ['series' => $series, 'id' => $id])->render();
    }

    public function crearCoEx(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'string|required',
            'pqr_clase' => 'integer|exists:gd_pqr_clase,id|required',
            'tipo_documento' => 'integer|required|exists:usuario_tipo_documento,id',
            'numero_documento' => 'numeric',
            'numero_oficio' => 'string',
            'responde_oficio' => 'string',
            'direccion' => 'string|required',
            'numero_telefono' => 'string|nullable',
            'departamento' => 'integer|required|exists:departamento,id',
            'municipio' => 'integer|required|exists:municipio,id',
            'correo_electronico' => 'email|nullable',
            'correo_electronico_notificacion' => 'email|nullable',
            'asunto' => 'string|required',
            'anexos' => 'mimetypes:application/zip|mimes:zip|max:80000',
            'descripcion' => 'required|string|max:1500|',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'descripcion.required' => 'No ha dado una descripción de su solicitud.',
            'descripcion.max' => 'El asunto debe tener un máximo de :max caracteres.',
            'nombre.required' => 'No se especificado un nombre.',
            'pqr_clase.integer' => 'El tipo de PQR dado no es válido.',
            'pqr_clase.exists' => 'El tipo de PQR dado no existe.',
            'pqr_clase.required' => 'No seleccionó un tipo de PQR.',
            'tipo_documento.integer' => 'El tipo de documento dado no es válido.',
            'tipo_documento.required' => 'El seleccionó un tipo de documento.',
            'tipo_documento.exists' => 'El tipo de documento dado no existe.',
            'numero_documento.numeric' => 'El número de documento dado no es válido.',
            'direccion.required' => 'No proporcionó una dirección.',
            'numero_telefono.string' => 'El número telefónico dado no es válido.',
            'departamento.integer' => 'El formato de ID del departamento no es válido.',
            'departamento.required' => 'No seleccionó un departamento.',
            'departamento.exists' => 'El departamento proporcionado no existe.',
            'municipio.integer' => 'El formato de ID del municipio no es válido.',
            'municipio.required' => 'No seleccionó un municipio.',
            'municipio.exists' => 'El municipio proporcionado no existe.',
            'correo_electronico.email' => 'El correo electrónico proporcionado no cumple con el formato de correo electrónico.',
            'correo_electronico_notificacion.email' => 'El correo electrónico para notificación proporcionado no cumple con el formato de correo electrónico',
            'asunto.required' => 'Debe proporcionar información en el asunto.',
            'anexos.mimetypes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
            'anexos.max' => 'El anexo no debe superar el tamaño máximo permitido de 20MB.',
            'anexos.mimes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors()->all());
        } else {
            $tipoDocumento = usuario_tipo_documento::find($request->tipo_documento);
            if($tipoDocumento->requiere_numero == 'SI' && $request->numero_documento == null){
                return back()->withErrors(['El tipo de documento seleccionado requiere que se especifique un número de documento.'])->withInput($request->all());
            }
            $success = null; //determina si la transacción resulta bien
            $pqr = null;
            $radicado = null;
            $peticionario = null;
            $pdf = null;
            \DB::beginTransaction();
            try {
                /*
                 * Creación del pqr
                 */
                $uuid = Uuid::generate(5, str_random(5).'PQR-CoEx-'.date('Y-m-d H:i:s'), Uuid::NS_DNS);
                $pqr = new gd_pqr();
                $pqr->gd_pqr_clase_id = $request->pqr_clase;
                $pqr->asunto = $request->asunto;
                $pqr->descripcion = $request->descripcion;
                $pqr->numero_oficio = $request->numero_oficio;
                $pqr->gd_medio_traslado_id = $request->medio;
                $pqr->limite_respuesta = $this->fechaLimiteRespuesta(gd_pqr_clase::find($request->pqr_clase));
                $pqr->tipo_pqr = 'CoEx';
                $pqr->uuid = $uuid->string;
                $pqr->save();
                /*
                 * Creación del peticionario
                 */
                $peticionario = new gd_pqr_peticionario();
                $peticionario->gd_pqr_id = $pqr->id;
                $peticionario->funcionario_id = $request->funcionario;
                $peticionario->dependencia_id = $request->dependencia;
                $peticionario->tipo_documento_id = $request->tipo_documento;
                $peticionario->departamento_id = $request->departamento;
                $peticionario->municipio_id = $request->municipio;
                $peticionario->correo_notificacion = $request->correo_electronico_notificacion;
                $peticionario->correo_electronico = $request->correo_electronico;
                $peticionario->numero_telefono = $request->numero_telefono;
                $peticionario->direccion_residencia = $request->direccion;
                $peticionario->numero_documento = $request->numero_documento;
                $peticionario->nombre_completo = strtoupper($request->nombre);
                $peticionario->tipo_usuario = 'P';
                $peticionario->save();

                /*
                 * Se crea el radicado
                 */
                $radicado = $this->generarRadicadoEntrada($pqr->id);

                if($radicado == false || $radicado == null){
                    $success = false;
                    \DB::rollBack();
                }else{
                    /*
                     * Se gestiona el consecutivo
                     */
                    /*if ($request->responde_oficio != null) {
                        $this->vincularPorNumeroOficioResponde($pqr->id, $request->responde_oficio);
                    }*/
                    /*
                     * Se genera el PDF de radicado
                     */
                    $this->generarPDF($pqr, $radicado);
                    /*
                     * Anexos
                     */
                    if (isset($request->anexos)) {
                        $this->guardarAnexos($request->anexos, $pqr, $pqr->tipo_pqr);
                    }

                    \DB::commit();
                    $success = true;
                }
            } catch (\Exception $e) {
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                /*
                 * Se notifica el nuevo PQR a los administradores
                 */
                $administradoresPQR = User::whereHas('hasRoles', function ($query) {
                    $query->where('name', '=', 'Administrador PQR');
                })->get();
                if ($administradoresPQR->count() > 0) {
                    \Notification::send($administradoresPQR, new NuevoWebPQR($pqr));
                    event(new nuevoCoEx($pqr));
                }
                /*
                 * Se envía constancia de radicado al usuario, si este suministró un correo electrónico en el campo correo de notificación del formulario
                 */
                if(!empty($request->correo_electronico_notificacion)){
                    try{
                        \Mail::send(new radicadoPQR($pqr, $request->correo_electronico_notificacion));
                    }catch (\Exception $e){

                    }
                }
                /*
                 * Se envía respuesta positiva al usuario
                 */
                return view('publico.pqr.radicacion', [
                    'pqr' => $pqr,
                    'radicado' => $radicado,
                ]);
            } else {
                return back()->withErrors(['No se ha podido radicar el PQR. Si el problema persiste, por favor comunicarse con soporte.']);
            }
        }
    }

    public function crearCoExAd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'string|required',
            'pqr_clase' => 'integer|exists:gd_pqr_clase,id|required',
            'tipo_documento' => 'integer|required|exists:usuario_tipo_documento,id',
            'numero_documento' => 'numeric',
            'numero_oficio' => 'string|nullable',
            'responde_oficio' => 'string|nullable',
            'direccion' => 'string|required',
            'numero_telefono' => 'string|nullable',
            'departamento' => 'integer|required|exists:departamento,id',
            'municipio' => 'integer|required|exists:municipio,id',
            'correo_electronico' => 'email|nullable',
            'correo_electronico_notificacion' => 'email|nullable',
            'asunto' => 'string|required',
        ], [
            'nombre.required' => 'No se especificado un nombre.',
            'pqr_clase.integer' => 'El tipo de PQR dado no es válido.',
            'pqr_clase.exists' => 'El tipo de PQR dado no existe.',
            'pqr_clase.required' => 'No seleccionó un tipo de PQR.',
            'tipo_documento.integer' => 'El tipo de documento dado no es válido.',
            'tipo_documento.required' => 'El seleccionó un tipo de documento.',
            'tipo_documento.exists' => 'El tipo de documento dado no existe.',
            'numero_documento.numeric' => 'El número de documento dado no es válido.',
            'direccion.required' => 'No proporcionó una dirección.',
            'numero_telefono.string' => 'El número telefónico dado no es válido.',
            'departamento.integer' => 'El formato de ID del departamento no es válido.',
            'departamento.required' => 'No seleccionó un departamento.',
            'departamento.exists' => 'El departamento proporcionado no existe.',
            'municipio.integer' => 'El formato de ID del municipio no es válido.',
            'municipio.required' => 'No seleccion´+o un municipio.',
            'municipio.exists' => 'El municipio proporcionado no existe.',
            'correo_electronico.email' => 'El correo electrónico proporcionado no cumple con el formato de correo electrónico.',
            'correo_electronico_notificacion.email' => 'El correo electrónico para notificación proporcionado no cumple con el formato de correo electrónico',
            'asunto.required' => 'Debe proporcionar información en el asunto.',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.gestion_documental.pqr.nuevoCoEx', $this->complementosCoEx())->withErrors($validator->errors()->all())->render();
        } else {
            $tipoDocumento = usuario_tipo_documento::find($request->tipo_documento);
            if($tipoDocumento->requiere_numero == 'SI' && $request->numero_documento == null){
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['El tipo de documento seleccionado requiere que se especifique un número de documento.'],
                    'encabezado' => '¡Error!'
                ], 200);
            }
            $success = null; //determina si la transacción resulta bien
            $pqr = null;
            $radicado = null;
            $peticionario = null;
            $pdf = null;
            try {
                \DB::beginTransaction();
                /*
                 * Creación del pqr
                 */
                $uuid = Uuid::generate(5, str_random(5).'PQR-CoExAd-'.date('Y-m-d H:i:s'), Uuid::NS_DNS);
                $pqr = new gd_pqr();
                $pqr->gd_pqr_clase_id = $request->pqr_clase;
                $pqr->asunto = $request->asunto;
                $pqr->numero_oficio = $request->numero_oficio;
                $pqr->gd_medio_traslado_id = $request->medio;
                $pqr->limite_respuesta = $this->fechaLimiteRespuesta(gd_pqr_clase::find($request->pqr_clase));
                $pqr->tipo_pqr = 'CoEx';
                $pqr->uuid = $uuid->string;
                $pqr->save();
                /*
                 * Creación del peticionario
                 */
                $peticionario = new gd_pqr_peticionario();
                $peticionario->gd_pqr_id = $pqr->id;
                $peticionario->funcionario_id = $request->funcionario;
                $peticionario->dependencia_id = $request->dependencia;
                $peticionario->tipo_documento_id = $request->tipo_documento;
                $peticionario->departamento_id = $request->departamento;
                $peticionario->municipio_id = $request->municipio;
                $peticionario->correo_notificacion = $request->correo_electronico_notificacion;
                $peticionario->correo_electronico = $request->correo_electronico;
                $peticionario->numero_telefono = $request->numero_telefono;
                $peticionario->direccion_residencia = $request->direccion;
                $peticionario->numero_documento = $request->numero_documento;
                $peticionario->nombre_completo = title_case($request->nombre);
                $peticionario->tipo_usuario = 'P';
                $peticionario->save();

                /*
                 * Se crea el radicado
                 */
                $radicado = $this->generarRadicadoEntrada($pqr->id);

                if($radicado == false || $radicado == null){
                    $success = false;
                    \DB::rollBack();
                }else{
                    /*
                     * Se gestiona el consecutivo
                     */
                    /*if ($request->responde_oficio != null) {
                        $this->vincularPorNumeroOficioResponde($pqr->id, $request->responde_oficio);
                    }*/

                    \DB::commit();
                    $success = true;
                }
            } catch (\Exception $e) {
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                /*
                 * Se notifica el nuevo PQR a los administradores
                 */
                $administradoresPQR = User::whereHas('hasRoles', function ($query) {
                    $query->where('name', '=', 'Administrador PQR');
                })->get();
                if ($administradoresPQR->count() > 0) {
                    \Notification::send($administradoresPQR, new NuevoWebPQR($pqr));
                }
                /*
                 * Se envía constancia de radicado al usuario, si este suministró un correo electrónico en el campo correo de notificación del formulario
                 */
                if(!empty($request->correo_electronico_notificacion)){
                    try{
                        \Mail::send(new radicadoPQR($pqr, $request->correo_electronico_notificacion));
                    }catch (\Exception $e){

                    }
                }
                /*
                 * Se envía respuesta positiva al usuario
                 */
                return view('admin.gestion_documental.pqr.uploadFileRadicado', ['tipoRadicado'=>'entrada', 'idRadicado'=>$radicado->id, 'idPqr'=>$pqr->id, 'numeroRadicado'=>$radicado->numero])->render();
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido radicar el PQR. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!'
                ], 200);
            }
        }
    }

    public function crearCoIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pqr_clase' => 'integer|exists:gd_pqr_clase,id|required',
            'asunto' => 'string|required',
            'anexos' => 'mimetypes:application/zip|mimes:zip|max:80000',
            'funcionario' => 'required|integer|exists:users,id',
            'numero_oficio' => 'string|nullable',
            'responde_oficio' => 'string|nullable',
        ], [
            'radicado.in' => 'El valor dado para el radicado no es correcto.',
            'pqr_clase.integer' => 'El tipo de PQR dado no es válido.',
            'pqr_clase.exists' => 'El tipo de PQR dado no existe.',
            'pqr_clase.required' => 'No seleccionó un tipo de PQR.',
            'asunto.required' => 'Debe proporcionar información en el asunto.',
            'anexos.mimetypes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
            'anexos.max' => 'El anexo no debe superar el tamaño máximo permitido de 20MB.',
            'anexos.mimes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
            'funcionario.required' => 'No se ha especificado el ID del funcionario.',
            'funcionario.integer' => 'El ID del funcionario especificado no tiene un formato válido.',
            'funcionario.exists' => 'El ID del funcionario especificado no existe en el sistema.',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.gestion_documental.pqr.nuevoCoIn', $this->complementosCoIn())->withErrors($validator->errors()->all())->render();
        } else {
            $success = null; //determina si la transacción resulta bien
            $pqr = null;
            $radicado = null;
            $peticionario = null;
            $pdf = null;
            $funcionario = null;
            \DB::beginTransaction();
            try {
                $uuid = Uuid::generate(5, str_random(5).'PQR-CoIn-'.date('Y-m-d H:i:s'), Uuid::NS_DNS);
                $pqr = new gd_pqr();
                $pqr->gd_pqr_clase_id = $request->pqr_clase;
                $pqr->asunto = $request->asunto;
                $pqr->gd_medio_traslado_id = $request->medio;
                $pqr->numero_oficio = $request->numero_oficio;
                $pqr->limite_respuesta = $this->fechaLimiteRespuesta(gd_pqr_clase::find($request->pqr_clase));
                $pqr->tipo_pqr = 'CoIn';//correspondencia interna
                $pqr->uuid = $uuid->string;
                $pqr->save();

                $funcionario = User::with('hasDependencia')->find($request->funcionario);
                $peticionario = new gd_pqr_peticionario();
                $peticionario->gd_pqr_id = $pqr->id;
                $peticionario->funcionario_id = $funcionario->id;
                $peticionario->dependencia_id = $funcionario->hasDependencia->id;
                $peticionario->tipo_documento_id = null;
                $peticionario->departamento_id = null;
                $peticionario->municipio_id = null;
                $peticionario->correo_notificacion = null;
                $peticionario->correo_electronico = null;
                $peticionario->numero_telefono = null;
                $peticionario->direccion_residencia = null;
                $peticionario->numero_documento = null;
                $peticionario->nombre_completo = null;
                $peticionario->tipo_usuario = 'F';
                $peticionario->save();

                if (isset($request->anexos)) {
                    $this->guardarAnexos($request->anexos, $pqr, $pqr->tipo_pqr);
                }

                /*if ($request->responde_oficio != null) {
                    $this->vincularPorNumeroOficioResponde($pqr->id, $request->responde_oficio);
                }*/

                $radicado = $this->generarRadicadoEntrada($pqr->id);

                if($radicado == false || $radicado == null){
                    $success = false;
                    \DB::rollBack();
                }else{
                    \DB::commit();
                    $success = true;
                }
            } catch (\Exception $e) {
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                event(new FuncionarioPQR($funcionario->id));
                return view('admin.gestion_documental.pqr.uploadFileRadicado', ['tipoRadicado'=>'entrada', 'idRadicado'=>$radicado->id, 'idPqr'=>$pqr->id, 'numeroRadicado'=>$radicado->numero])->render();
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido radicar el PQR. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function crearCoSa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pqr_clase' => 'integer|exists:gd_pqr_clase,id|required',
            'asunto' => 'string|required',
            'anexos' => 'mimetypes:application/zip|mimes:zip|max:80000',
            'funcionario' => 'required|integer|exists:users,id',
            'radicados_respuesta' => 'array'
        ], [
            'pqr_clase.integer' => 'El tipo de PQR dado no es válido.',
            'pqr_clase.exists' => 'El tipo de PQR dado no existe.',
            'pqr_clase.required' => 'No seleccionó un tipo de PQR.',
            'asunto.required' => 'Debe proporcionar información en el asunto.',
            'anexos.mimetypes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
            'anexos.max' => 'El anexo no debe superar el tamaño máximo permitido de 20MB.',
            'anexos.required' => 'Se debe adjuntar el anexo correspondiente.',
            'anexos.mimes' => 'El anexo no tiene un formato válido. Debe ser un archivo comprimido zip.',
            'funcionario.required' => 'No se ha especificado el ID del funcionario.',
            'funcionario.integer' => 'El ID del funcionario especificado no tiene un formato válido.',
            'funcionario.exists' => 'El ID del funcionario especificado no existe en el sistema.',
            'radicados_respuesta.array' => 'Los radicados de respuesta no tienen un formato válido.',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.gestion_documental.pqr.nuevoCoSa', $this->complementosCoSa())->withErrors($validator->errors()->all())->render();
        } else {
            $success = null; //determina si la transacción resulta bien
            $pqr = null;
            $radicado = null;
            $peticionario = null;
            $pdf = null;
            $funcionario = null;
            \DB::beginTransaction();
            try{
                $uuid = Uuid::generate(5, str_random(5).'PQR-CoSa-'.date('Y-m-d H:i:s'), Uuid::NS_DNS);
                $pqr = new gd_pqr();
                $pqr->gd_pqr_clase_id = $request->pqr_clase;
                $pqr->asunto = $request->asunto;
                $pqr->gd_medio_traslado_id = $request->medio;
                $pqr->numero_oficio = $request->numero_oficio;
                $pqr->tipo_pqr = 'CoSa';//correspondencia interna
                $pqr->uuid = $uuid->string;
                $pqr->save();

                /*
                 * Se registra la información del peticionario
                 */
                $funcionario = User::with('hasDependencia')->find($request->funcionario);
                $peticionario = new gd_pqr_peticionario();
                $peticionario->gd_pqr_id = $pqr->id;
                $peticionario->funcionario_id = $funcionario->id;
                $peticionario->dependencia_id = $funcionario->hasDependencia->id;
                $peticionario->tipo_documento_id = null;
                $peticionario->departamento_id = null;
                $peticionario->municipio_id = null;
                $peticionario->correo_notificacion = null;
                $peticionario->correo_electronico = null;
                $peticionario->numero_telefono = null;
                $peticionario->direccion_residencia = null;
                $peticionario->numero_documento = null;
                $peticionario->nombre_completo = null;
                $peticionario->tipo_usuario = 'F';
                $peticionario->save();

                /*
                 * Se crea el radicado
                 */
                $radicado = $this->generarRadicadoSalida($pqr->id);

                if($radicado == false || $radicado == null){
                    $success = false;
                    \DB::rollBack();
                }else{
                    /*
                     * Contestación de los distintos procesos PQR especificados
                     */
                    if(isset($request->radicados_respuesta)){
                        $radicados_respuesta_cadena = null;
                        foreach ($request->radicados_respuesta as $radicado_respuesta){
                            $radicado_respuesta = str_replace(' ', '', $radicado_respuesta);
                            if($radicados_respuesta_cadena != ''){
                                $radicados_respuesta_cadena = $radicados_respuesta_cadena.','.strtoupper($radicado_respuesta);
                            }else{
                                $radicados_respuesta_cadena = strtoupper($radicado_respuesta);
                            }
                            $pqr_respondido = gd_pqr::with('getRadicadoEntrada', 'hasPeticionario', 'hasRespuestas', 'hasPeticionario.couldHaveFuncionario')
                                ->whereHas('getRadicadoEntrada', function($query) use ($radicado_respuesta) {
                                    $query->where('numero', strtoupper($radicado_respuesta));
                                })->first();
                            if($pqr_respondido != null){
                                $pqr_respondido->hasRespuestas()->attach($pqr->id);
                                if($pqr_respondido->getAsignacionesActivas() == null){
                                    gd_pqr_asignacion::create([
                                        'funcionario_id' => \Auth::user()->id,
                                        'dependencia_id' => $funcionario->hasDependencia->id,
                                        'usuario_asignado_id' => $funcionario->id,
                                        'gd_pqr_id' => $pqr_respondido->id,
                                        'estado' => 1,
                                        'responsable' => 1,
                                    ]);
                                }
                                $this->notificarRespuestaPQR($pqr_respondido);
                            }
                        }
                        $pqr->radicados_respuesta = $radicados_respuesta_cadena;
                        $pqr->save();
                    }

                    //Finalización del proceso
                    \DB::commit();
                    $success = true;
                }
            }catch (\Exception $e){                
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                event(new FuncionarioPQR($funcionario->id));
                return view('admin.gestion_documental.pqr.uploadFileRadicado', ['tipoRadicado'=>'salida', 'idRadicado'=>$radicado->id, 'idPqr'=>$pqr->id, 'numeroRadicado'=>$radicado->numero])->render();
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido radicar el PQR. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    private function guardarAnexos($anexos, $gd_pqr, $destino)
    {
        try {
            $ruta_archivo = Storage::disk('pqr')->putFile('/'.$destino.'/'.$gd_pqr->id.'/anexos', $anexos);
            if ($ruta_archivo != null && $ruta_archivo != false) {
                $gd_pqr->anexos = $ruta_archivo;
                $gd_pqr->save();

                return true;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerAnexos($pqr_id)
    {
        $pqr = gd_pqr::where('uuid', $pqr_id)->first();
        $name = explode('/', $pqr->anexos);
        $headers = [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/pqr/'.$pqr->anexos), array_last($name), $headers);
    }

    private function generarRadicadoEntrada($gd_pqr_id)
    {
        $ultimoRadicado = null;
        try {
            $pqr = gd_pqr::with('getRadicadoEntrada')->find($gd_pqr_id);
            if ($pqr->getRadicadoEntrada == null) {
                $ultimaVigencia = sistema_parametros_vigencia::with('hasGD')->orderBy('vigencia', 'desc')->get()->first();
                $ultimoRadicado = gd_radicado_entrada::whereYear('created_at', $ultimaVigencia->vigencia)->orderBy('id', 'desc')->first();
                if ($ultimoRadicado == null) {
                    $numero = $ultimaVigencia->hasGD->radicado_entrada_consecutivo;
                } else {
                    $ultimoRadicado = explode('-', $ultimoRadicado->numero);
                    //Establecemos el nuevo numero de radicado. Se eliminan los ceros de la izquierda primero y luego se aumenta en 1
                    $numero = ltrim(array_last($ultimoRadicado), "0");
                    $numero += 1;
                    //Se vuelven a agregar los ceros a la izquierda
                    $numero = sprintf("%'.06d", $numero);
                }

                //se crea el nuevo consecutivo
                $radicado = gd_radicado_entrada::create([
                    'numero' => \anlutro\LaravelSettings\Facade::get('empresa-sigla').'-'.$ultimaVigencia->vigencia.'-100-'.$numero,
                    'origen_id' => $gd_pqr_id,
                    'origen_type' => 'App\\gd_pqr',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if($radicado != null){
                    return $radicado;
                }else{
                    return null;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generarRadicadoSalida($gd_pqr_id)
    {
        $ultimoRadicado = null;
        try {
            $pqr = gd_pqr::with('getRadicadoSalida')->find($gd_pqr_id);
            if ($pqr->getRadicadoSalida == null) {
                $ultimaVigencia = sistema_parametros_vigencia::with('hasGD')->orderBy('vigencia', 'desc')->first();
                $ultimoRadicado = gd_radicado_salida::whereYear('created_at', $ultimaVigencia->vigencia)->orderBy('id', 'desc')->first();
                if ($ultimoRadicado == null) {
                    $numero = $ultimaVigencia->hasGD->radicado_salida_consecutivo;
                } else {
                    $ultimoRadicado = explode('-', $ultimoRadicado->numero);
                    //Establecemos el nuevo numero de radicado. Se eliminan los ceros de la izquierda primero y luego se aumenta en 1
                    $numero = ltrim(array_last($ultimoRadicado), "0");
                    $numero += 1;
                    //Se vuelven a agregar los ceros a la izquierda
                    $numero = sprintf("%'.06d", $numero);
                }

                //se crea el nuevo consecutivo
                $radicado = gd_radicado_salida::create([
                    'numero' => \anlutro\LaravelSettings\Facade::get('empresa-sigla').'-'.$ultimaVigencia->vigencia.'-100-'.$numero,
                    'origen_id' => $gd_pqr_id,
                    'origen_type' => 'App\\gd_pqr',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if($radicado != null){
                    return $radicado;
                }else{
                    return null;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generarPDF(gd_pqr $pqr, $radicado)
    {
        try {
            $pdf = \PDF::loadView('publico.pqr.imprimirRadicado', [
                'pqr' => $pqr,
                'radicado' => $radicado,
            ])->setOption('margin-bottom', 20)->setOption('margin-left', 30)->setOption('margin-right', 20)->setOption('margin-top', 20)->setOption('images', true)->setOption('page-size', 'letter')->setOption('no-outline', true)->setOption('enable-smart-shrinking', true);

            $url = '/CoEx/'.$pqr->id.'/radicado/'.$pqr->created_at->format('Y-m-d_H-i-s').'.pdf';
            Storage::disk('pqr')->put($url, $pdf->download($pqr->id.'-'.$pqr->created_at->format('Y-m-d_H-i-s') . '.pdf'));
            $pqr->pdf = $url;
            $pqr->save();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerClasesPQR()
    {
        $clases = gd_pqr_clase::all()->pluck('name', 'id');
        if ($clases != null) {
            return $clases->toJson();
        } else {
            return null;
        }
    }

    public function obtenerPQR($id)
    {
        $pqr = gd_pqr::find($id);

        return $pqr->toJson();
    }

    public function misProcesos()
    {
        $series = trd_documento_serie::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.gestion_documental.pqr.misProcesos', ['series' => $series]);
    }

    public function obtenerMisProcesosCoEx()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado',
            '3' => 'Consecutivo',
            '4' => 'Asunto',
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')->whereHas('hasAsignaciones', function (
            $query
        ) {
            $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
        })->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.gestion_documental.pqr.listadoMisProcesosCoEx', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    public function obtenerMisProcesosCoIn()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado',
            '3' => 'Consecutivo',
            '4' => 'Asunto',
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'hasPeticionario.couldHaveFuncionario','hasPeticionario.couldHaveFuncionario.hasDependencia','getMedioTraslado', 'getRadicadoEntrada', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
            ->where('tipo_pqr', 'CoIn')
            ->whereHas('hasPeticionario', function ($query){
                $query->where('funcionario_id', auth()->user()->id);
            })
            ->orWhereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('estado','1')->whereHas('hasPQR', function ($query){
                    $query->where('tipo_pqr', 'CoIn');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.gestion_documental.pqr.listadoMisProcesosCoIn', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    public function obtenerMisProcesosCoSa()
    {
        $filtros = [
            '1' => 'Radicado',
            '2' => 'Consecutivo',
            '3' => 'Asunto',
            '4' => 'Radicado respuesta',
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
            ->where('tipo_pqr', 'CoSa')
            ->whereHas('hasPeticionario', function ($query1) {
                $query1->where('funcionario_id', auth()->user()->id);
            })->orWhereHas('getRespondidos', function($query2){
                $query2->whereHas('hasAsignaciones', function($query3){
                    $query3->where('usuario_asignado_id', auth()->user()->id)->where('estado',1)->whereHas('hasPQR', function ($query4){
                        $query4->where('tipo_pqr', '!=', 'CoSa');
                    });
                });
            })->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.gestion_documental.pqr.listadoMisProcesosCoSa', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    public function obtenerAllCoEx()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado',
            '3' => 'Consecutivo',
            '4' => 'Asunto',
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.gestion_documental.pqr.listadoAllCoEx', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    public function obtenerAllCoIn()
    {
        $filtros = [
            '1' => 'Nombre funcionario',
            '2' => 'Radicado entrada',
            '3' => 'Consecutivo',
            '4' => 'Asunto',
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.gestion_documental.pqr.listadoAllCoIn', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    public function obtenerAllCoSa()
    {
        $filtros = [
            '1' => 'Nombre funcionario',
            '2' => 'Radicado',
            '3' => 'Consecutivo',
            '4' => 'Asunto',
            '5' => 'Radicado respuesta'
        ];
        $sFiltro = null;
        $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasEnvio', 'hasEntrega')->where('tipo_pqr', 'CoSa')->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.gestion_documental.pqr.listadoAllCoSa', [
            'pqrs' => $pqrs,
            'filtros' => $filtros,
            'sFiltro' => $sFiltro,
        ])->render();
    }

    private function fechaLimiteRespuesta(gd_pqr_clase $clase)
    {
        $dias = null;
        if ($clase->required_answer != 'SI') {
            return null;
        } else {
            if ($clase->dia_clase == 'HABIL') {
                $dias = calendario::whereDate('fecha', '>=', Carbon::now()->format('Y-m-d'))->where('laboral', '1')->take($clase->dia_cantidad)->get();
            } else {
                $dias = calendario::whereDate('fecha', '>=', Carbon::now()->format('Y-m-d'))->take($clase->dia_cantidad)->get();
            }

            return $dias->last()->fecha;
        }
    }

    public function getPDF($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        if(auth()->user()->hasAnyRoles(['Administrador','Administrador PQR']) || $pqr->comprobarUsuarioAsignacion(auth()->user()->id) || $pqr->comprobarFuncionarioPeticionario($pqr->id, auth()->user()->id)){
            if ($pqr->pdf != null && $pqr != '') {
                $name = explode('/', $pqr->pdf);
                $headers = [
                    'Content-Type: application/pdf',
                    'Content-Disposition: attachment; filename="'.array_last($name).'"',
                ];

                return Response()->download(storage_path('app/pqr'.$pqr->pdf), array_last($name), $headers);
            } else {
                return back()->withErrors(['El proceso no tiene un PDF generado.']);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No tienes permiso para acceder a este elemento.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function registrarAsignacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pqrid' => 'required|integer|exists:gd_pqr,id',
            'funcionarios' => 'required|array|min:1',
            'responsable' => 'required|integer|exists:users,id'
        ], [
            'pqrid.required' => 'No se ha especificado el ID de PQR.',
            'pqrid.integer' => 'El ID especificado de PQR no tiene un formato válido.',
            'pqrid.exists' => 'El ID especificado de PQR no existe en la base de datos.',
            'funcionarios.required' => 'No se han especificado los funcionarios a asignar.',
            'funcionarios.array' => 'Los ID´s especificados de los funcionarios no tiene un formato válido.',
            'funcionarios.min' => 'Se debe seleccionar al menos un funcionario.',
            'responsable.required' => 'No se ha especificado el funcionario que será responsable del proceso.',
            'responsable.integer' => 'El valor especificado para el responsable del proceso no tiene un formato válido.',
            'responsable.exists' => 'El funcionario responsable especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            \DB::beginTransaction();
            $success = false;
            try{
                $pqr = gd_pqr::with('hasAsignaciones')->find($request->pqrid);
                if($pqr->hasAnulacion != null){
                    \DB::rollBack();
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede realizar la asignación porque el proceso está anulado.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
                if ($pqr->hasAsignaciones->count() == 0) {
                    foreach ($request->funcionarios as $funcionario){
                        $funcionario = User::with('hasDependencia')->find($funcionario);
                        //Comprobamos si este usuario va a ser el responsable
                        if($funcionario->id == $request->responsable){
                            $asignacion = gd_pqr_asignacion::create([
                                'funcionario_id' => \Auth::user()->id,
                                'dependencia_id' => $funcionario->hasDependencia->id,
                                'usuario_asignado_id' => $funcionario->id,
                                'gd_pqr_id' => $request->pqrid,
                                'estado' => 1,
                                'responsable' => 1,
                            ]);
                        }else{
                            $asignacion = gd_pqr_asignacion::create([
                                'funcionario_id' => \Auth::user()->id,
                                'dependencia_id' => $funcionario->hasDependencia->id,
                                'usuario_asignado_id' => $funcionario->id,
                                'gd_pqr_id' => $request->pqrid,
                                'estado' => 1,
                                'responsable' => null,
                            ]);
                        }
                        \Notification::send($funcionario, new AsignacionPQR($asignacion));
                        event(new FuncionarioPQR($funcionario->id));
                    }
                    \DB::commit();
                    $success = true;                    
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Este PQR ya tiene una asignación activa. Para realizar cambios se debe re-asignar.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            }catch (\Exception $e){
                \DB::rollBack();
            }
            if ($success) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha realizado la(s) asignación(es) correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Se presentó un problema en la(s) asignación(es). Por favor inténtelo nuevamente.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
        }
    }

    public function historialAsignaciones($id)
    {
        $pqr = gd_pqr::with('hasAsignaciones')->find($id);

        return view('admin.gestion_documental.pqr.historialAsignaciones', ['asignaciones' => $pqr->hasAsignaciones])->render();
    }

    public function registrarReAsignacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pqrid' => 'required|integer|exists:gd_pqr,id',
            'funcionarios' => 'required|array|min:1',
            'motivo' => 'string|required',
            'responsable' => 'required|integer|exists:users,id'
        ], [
            'pqrid.required' => 'No se ha especificado el ID de PQR.',
            'pqrid.integer' => 'El ID especificado de PQR no tiene un formato válido.',
            'pqrid.exists' => 'El ID especificado de PQR no existe en la base de datos.',
            'funcionarios.required' => 'No se han especificado los funcionarios a asignar.',
            'funcionarios.array' => 'los ID´s especificado de los funcionarios no tiene un formato válido.',
            'funcionarios.min' => 'Se debe seleccionar al menos un funcionario.',
            'motivo.required' => 'No ha proporcionado el motivo por el que realiza la re-asignación.',
            'motivo.string' => 'El motivo de la re-asignación proporcionado no tiene un formato válido.',
            'responsable.required' => 'No se ha especificado el funcionario que será responsable del proceso.',
            'responsable.integer' => 'El valor especificado para el responsable del proceso no tiene un formato válido.',
            'responsable.exists' => 'El funcionario responsable especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $pqr = gd_pqr::find($request->pqrid);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede realizar la re-asignación porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            \DB::beginTransaction();
            $success = false;
            try {
                /* Se valida que el usuario responsable haya sido seleccionado en funcionarios asignados */
                if(!in_array($request->responsable, $request->funcionarios, true)){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El funcionario responsable seleccionado no ha sido establecido como funcionario asignado al proceso.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
                /* Obtenemos las asignaciones que estan activas y cuyos ID de funcionario asignado no esta en el nuevo Array de asignaciones, y se procede a desactivarlas */
                $asignacionesADesactivar = gd_pqr_asignacion::with('hasUsuarioAsignado')->where('estado', 1)->where('gd_pqr_id', $request->pqrid)->whereNotIn('usuario_asignado_id', $request->funcionarios)->get();
                foreach ($asignacionesADesactivar as $asignacionDesactivar){
                    $asignacionDesactivar->fecha_reasignacion = date('Y-m-d H:i:s');
                    $asignacionDesactivar->descripcion_reasignacion = $request->motivo;
                    $asignacionDesactivar->estado = 0;
                    $asignacionDesactivar->save();
                    \Notification::send($asignacionDesactivar->hasUsuarioAsignado, new ReasignacionPQR($asignacionDesactivar));
                    event(new FuncionarioPQR($asignacionDesactivar->usuario_asignado_id));
                }
                /* Se procede a realizar las nuevas asignaciones.
                   Primer paso: verificar si esta activo el ID, en caso positivo ignorar.
                   Segundo paso: realizar asignación y notificar.
                */
                foreach ($request->funcionarios as $funcionario){
                    $asignacionActiva = gd_pqr_asignacion::where('estado', 1)->where('gd_pqr_id', $request->pqrid)->where('usuario_asignado_id', $funcionario)->first();
                    if($asignacionActiva == null){
                        //no está asignado, o actualmente no tiene una asignación activa.
                        $funcionario = User::with('hasDependencia')->find($funcionario);
                        //Comprobamos si este usuario va a ser el responsable del proceso de acuerdo a su ID (checkbox) y el valor del campo responsable (radio)
                        if($funcionario->id == $request->responsable){
                            $asignacion = gd_pqr_asignacion::create([
                                'funcionario_id' => \Auth::user()->id,
                                'dependencia_id' => $funcionario->hasDependencia->id,
                                'usuario_asignado_id' => $funcionario->id,
                                'gd_pqr_id' => $request->pqrid,
                                'estado' => 1,
                                'responsable' => 1,
                            ]);
                        }else{
                            $asignacion = gd_pqr_asignacion::create([
                                'funcionario_id' => \Auth::user()->id,
                                'dependencia_id' => $funcionario->hasDependencia->id,
                                'usuario_asignado_id' => $funcionario->id,
                                'gd_pqr_id' => $request->pqrid,
                                'estado' => 1,
                                'responsable' => null,
                            ]);
                        }
                        \Notification::send($funcionario, new AsignacionPQR($asignacion));
                        event(new FuncionarioPQR($funcionario->id));
                    }else{
                        //verificamos si ya no es el responsable o si ahora lo es
                        if($asignacionActiva->responsable != null && $asignacionActiva->usuario_asignado_id != $request->responsable){
                            $asignacionActiva->responsable = null;
                            $asignacionActiva->save();
                        }else{
                            $asignacionActiva->responsable = 1;
                            $asignacionActiva->save();
                        }
                    }
                }
                \DB::commit();
                $success = true;
            } catch (\Exception $e) {
                \DB::rollBack();
            }
            if ($success == true) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado las nuevas asignaciones.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Se presentó un problema en las nuevas asignaciones. Por favor inténtelo nuevamente.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
        }
    }

    public function verAsunto($id)
    {
        $pqr = gd_pqr::with('getMedioTraslado')->where('id', $id)->first();
        if ($pqr->anexos != null) {
            $pqr->anexos = url('/pqr/anexos/'.$pqr->uuid);
        }

        return view('admin.gestion_documental.pqr.verAsunto', ['pqr' => $pqr])->render();
    }

    public function nuevaClase()
    {
        return view('admin.gestion_documental.pqr.nuevaClase')->render();
    }

    public function nuevoMedio()
    {
        return view('admin.gestion_documental.pqr.nuevoMedio')->render();
    }

    public function registrarClasificacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pqr_id' => 'required|integer|exists:gd_pqr,id|unique:gd_pqr_clasificacion,gd_pqr_id',
            'tiposdocumentos' => 'required|integer|exists:trd_documento_tipo,id',
        ], [
            'pqr_id.required' => 'Debe especificar el PQR al que se clasifica.',
            'pqr_id.integer' => 'El ID del PQR especificado no tiene un formato válido.',
            'pqr_id.exists' => 'El PQR especificado no existe en el sistema.',
            'pqr_id.unique' => 'El PQR especificado ya cuenta con una clasificación',
            'tiposdocumentos.required' => 'Debe especificar el tipo documental de clasificación.',
            'tiposdocumentos.integer' => 'El ID del tipo documento especificado no tiene un formato válido.',
            'tiposdocumentos.exists' => 'El tipo de documento especificado no existe en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $pqr = gd_pqr::with('hasClasificacion')->find($request->pqr_id);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede realizar la clasificación porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            if ($pqr->hasClasificacion != null) {
                $clasificacion = $pqr->hasClasificacion;
                $clasificacion->trd_documento_tipo_id = $request->tiposdocumentos;
                if ($clasificacion->save()) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha realizado la clasificación correctamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la clasificación.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            } else {
                $clasificacion = gd_pqr_clasificacion::create([
                    'gd_pqr_id' => $request->pqr_id,
                    'trd_documento_tipo_id' => $request->tiposdocumentos,
                ]);
                if ($clasificacion != null) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha realizado la clasificación correctamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la clasificación.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            }
        }
    }

    public function verClasificacion($id)
    {
        $series = trd_documento_serie::orderBy('name', 'asc')->pluck('name', 'id');
        $clasificacion = gd_pqr_clasificacion::with('getDocumentoTipo')->find($id);

        return view('admin.gestion_documental.pqr.clasificacionPQR', [
            'clasificacion' => $clasificacion,
            'series' => $series,
        ])->render();
    }

    public function editarClasificacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'm_clasificacion_id' => 'required|integer|exists:gd_pqr_clasificacion,id',
            'm_tiposdocumentos' => 'required|integer|exists:trd_documento_tipo,id',
            'm_pqr_id' => [
                'required',
                'integer',
                'exists:gd_pqr,id',
                Rule::unique('gd_pqr_clasificacion', 'gd_pqr_id')->ignore($request->m_clasificacion_id),
            ],
        ], [
            'm_clasificacion_id.required' => 'Debe especificar el ID de la clasificación a modificar.',
            'm_clasificacion_id.integer' => 'El ID de la clasificación especificada no tiene un formato válido.',
            'm_clasificacion_id.exists' => 'La clasificación especificada no existe en el sistema.',
            'm_pqr_id.unique' => 'El PQR especificado ya cuenta con una clasificación',
            'm_pqr_id.required' => 'Debe especificar el PQR al que se clasifica.',
            'm_pqr_id.integer' => 'El ID del PQR especificado no tiene un formato válido.',
            'm_pqr_id.exists' => 'El PQR especificado no existe en el sistema.',
            'm_tiposdocumentos.required' => 'Debe especificar el tipo documental de clasificación.',
            'm_tiposdocumentos.integer' => 'El ID del tipo documento especificado no tiene un formato válido.',
            'm_tiposdocumentos.exists' => 'El tipo de documento especificado no existe en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $pqr = gd_pqr::find($request->m_pqr_id);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede realizar el cambio de clasificación porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            $clasificacion = gd_pqr_clasificacion::find($request->m_clasificacion_id);
            $clasificacion->trd_documento_tipo_id = $request->m_tiposdocumentos;
            if ($clasificacion->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha realizado la actualización correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la actualización.'],
                    'encabezado' => 'Errores en la actualización:',
                ], 200);
            }
        }
    }

    public function radicadoSalida($id)
    {
        $pqr = gd_pqr::with('getRadicadoSalida')->find($id);
        if ($pqr->getRadicadoSalida == null) {
            $radicado = $this->generarRadicadoSalida($id);
            if ($radicado != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el radicado de salida correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido realizar la solicitud. Ya existe una radicado de salida para este proceso.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function radicadoEntrada($id)
    {
        $pqr = gd_pqr::find($id);
        if ($pqr->getRadicadoEntrada() == null) {
            $radicado = $this->generarRadicadoEntrada($id);
            if ($radicado != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el radicado de entrada correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido realizar la solicitud. Ya existe una radicado de entrada para este proceso.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    private function notificarRespuestaPQR($pqr)
    {
        if ($pqr->hasPeticionario->tipo_usuario === 'P') {
            if ($pqr->hasPeticionario->correo_notificacion != null) {
                try{
                    \Mail::send(new RespuestaPQR($pqr));
                }catch (\Exception $e){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Se ha realizado la operación, pero ha ocurrido un error en la notificación por correo. Deberá notificar al anterior usuario por otros medios.'],
                        'encabezado' => 'Realizado, pero con errores:',
                    ], 200);
                }
            }
        } else {
            \Notification::send($pqr->hasPeticionario->couldHaveFuncionario, new \App\Notifications\RespuestaPQR($pqr));
        }
    }

    public function verRespuesta($id)
    {
        $pqr = gd_pqr::with('hasRespuestas', 'hasEnvio', 'hasEnvio.hasEmpresaMensajeria', 'hasEnvio.hasModalidadEnvio')->find($id);
        return view('admin.gestion_documental.pqr.verRespuesta', ['pqr' => $pqr])->render();
    }

    public function getDocumento($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        $file = explode('/', $pqr->documento_radicadoa);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($file).'"',
        ];

        return Response()->download(storage_path('app/pqr/'.$pqr->documento_radicado), array_last($file), $headers);
    }

    public function getAnexos($id)
    {
        $pqr = gd_pqr::where('uuid', $id)->first();
        if(auth()->user()->hasAnyRoles(['Administrador','Administrador PQR']) || $pqr->comprobarUsuarioAsignacion(auth()->user()->id) || $pqr->comprobarFuncionarioPeticionario($pqr->id, auth()->user()->id)){
            $file = explode('/', $pqr->anexos);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="'.array_last($file).'"',
            ];

            return Response()->download(storage_path('app/pqr/'.$pqr->anexos), array_last($file), $headers);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No tienes permiso para acceder a este elemento.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function cargarClases()
    {
        if (auth()->user()->hasRole('Administrador')) {
            $clases = gd_pqr_clase::withTrashed()->paginate(25);
        } else {
            $clases = gd_pqr_clase::paginate(25);
        }

        return view('admin.gestion_documental.pqr.listadoClases', ['clases' => $clases])->render();
    }

    public function crearClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clase_nombre' => 'unique:gd_pqr_clase,name|required|string',
            'dia_clase' => [Rule::in(['HABIL', 'CALENDARIO']), 'string'],
            'dia_cantidad' => 'integer|min:1',
            'required_answer' => ['string', Rule::in(['SI', 'NO']), 'required'],
        ], [
            'clase_nombre.unique' => 'El nombre de la clase especificada ya está en uso.',
            'clase_nombre.required' => 'No se ha especificado el nombre de la clase.',
            'clase_nombre.string' => 'El nombre de la clase no tiene un formato válido.',
            'dia_clase.required' => 'No se ha especificado la clase del día.',
            'dia_clase.in' => 'La clase de día especificada no es correcta: debe ser HABIL o LABORAL',
            'dia_cantidad.integer' => 'La cantidad especificada de día no tiene un formato válido.',
            'dia_cantidad.min' => 'La cantidad especificada de días no es correcta. Debe ser de mínimo uno.',
            'dia_cantidad.required' => 'No se ha especificado la cantidad de días.',
            'required_answer.required' => 'No se ha especificado si la clase requiere de contestación',
            'required_answer.in' => 'El valor del parámetro de contestación no es válido. Debe ser SI o NO.',
            'required_answer.string' => 'El formato del parámetro de contestación no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            if ($request->required_answer == 'SI') {
                $clase = gd_pqr_clase::create([
                    'name' => $request->clase_nombre,
                    'dia_clase' => $request->dia_clase,
                    'dia_cantidad' => $request->dia_cantidad,
                    'required_answer' => $request->required_answer,
                ]);
            } else {
                $clase = gd_pqr_clase::create([
                    'name' => $request->clase_nombre,
                    'dia_clase' => null,
                    'dia_cantidad' => null,
                    'required_answer' => $request->required_answer,
                ]);
            }
            if ($clase != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la clase correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        }
    }

    public function cargarClase($id)
    {
        $clase = gd_pqr_clase::find($id);

        return view('admin.gestion_documental.pqr.modificarClase', ['clase' => $clase])->render();
    }

    public function modificarClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'clase_id_m' => 'exists:gd_pqr_clase,id|integer|required',
            'clase_nombre_m' => [
                Rule::unique('gd_pqr_clase', 'name')->ignore($request->clase_id_m),
                'required',
                'string',
            ],
            'dia_clase_m' => [Rule::in(['HABIL', 'CALENDARIO']), 'string'],
            'dia_cantidad_m' => 'integer|min:1',
            'required_answer_m' => ['string', Rule::in(['SI', 'NO']), 'required'],
        ], [
            'clase_id_m.exists' => 'El ID de la clase especificada no existe en el sistema.',
            'clase_id_m.integer' => 'El ID de la clase especificada no tiene un formato válido.',
            'clase_id_m.required' => 'Debe especificar el ID de la clase a modificar.',
            'clase_nombre_m.unique' => 'El nombre de la clase especificada ya está en uso.',
            'clase_nombre_m.required' => 'No se ha especificado el nombre de la clase.',
            'clase_nombre_m.string' => 'El nombre de la clase no tiene un formato válido.',
            'dia_clase_m.required' => 'No se ha especificado la clase del día.',
            'dia_clase_m.in' => 'La clase de día especificada no es correcta: debe ser HABIL o CALENDARIO',
            'dia_cantidad_m.integer' => 'La cantidad especificada de día no tiene un formato válido.',
            'dia_cantidad_m.min' => 'La cantidad especificada de días no es correcta. Debe ser de mínimo uno.',
            'dia_cantidad_m.required' => 'No se ha especificado la cantidad de días.',
            'required_answer_m.required' => 'No se ha especificado si la clase requiere de contestación',
            'required_answer_m.in' => 'El valor del parámetro de contestación no es válido. Debe ser SI o NO.',
            'required_answer_m.string' => 'El formato del parámetro de contestación no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $clase = gd_pqr_clase::find($request->clase_id_m);
            $clase->name = $request->clase_nombre_m;
            if ($request->required_answer_m == 'SI') {
                $clase->dia_clase = $request->dia_clase_m;
                $clase->dia_cantidad = $request->dia_cantidad_m;
            } else {
                $clase->dia_clase = null;
                $clase->dia_cantidad = null;
            }
            $clase->required_answer = $request->required_answer_m;
            if ($clase->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la clase correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        }
    }

    public function cargarMediosTraslado()
    {
        if (auth()->user()->hasRole('Administrador')) {
            $medios = gd_medio_traslado::withTrashed()->paginate(25);
        } else {
            $medios = gd_medio_traslado::paginate(25);
        }

        return view('admin.gestion_documental.pqr.listadoMedios', ['medios' => $medios])->render();
    }

    public function crearMedio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medio_nombre' => 'unique:gd_medio_traslado,name|required|string',
        ], [
            'medio_nombre.unique' => 'El nombre del medio especificada ya está en uso.',
            'medio_nombre.required' => 'No se ha especificado el nombre del medio.',
            'medio_nombre.string' => 'El nombre del medio no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            if (gd_medio_traslado::create([
                'name' => $request->medio_nombre,
            ])) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el medio correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        }
    }

    public function cargarMedio($id)
    {
        $medio = gd_medio_traslado::find($id);

        return view('admin.gestion_documental.pqr.modificarMedio', ['medio' => $medio])->render();
    }

    public function modificarMedio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medio_id_m' => 'exists:gd_medio_traslado,id|integer|required',
            'medio_nombre_m' => [
                Rule::unique('gd_medio_traslado', 'name')->ignore($request->medio_id_m),
                'required',
                'string',
            ],
        ], [
            'medio_id_m.exists' => 'El ID del medio especificada no existe en el sistema.',
            'medio_id_m.integer' => 'El ID del medio especificada no tiene un formato válido.',
            'medio_id_m.required' => 'Debe especificar el ID del medio a modificar.',
            'medio_nombre_m.unique' => 'El nombre del medio especificada ya está en uso.',
            'medio_nombre_m.required' => 'No se ha especificado el nombre del medio.',
            'medio_nombre_m.string' => 'El nombre del medio no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $medio = gd_medio_traslado::find($request->medio_id_m);
            $medio->name = $request->medio_nombre_m;
            if ($medio->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el medio correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la solicitud.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            }
        }
    }

    public function filtrarCoEx($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')->whereHas('hasPeticionario', function (
                    $query
                ) use ($parametro) {
                    $query->where('numero_documento', 'like', '%'.$parametro.'%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
                ->where('tipo_pqr', 'CoEx')
                ->whereHas('getRadicadoEntrada', function ($query) use ($parametro) {
                    $query->where('numero', 'like', '%'.$parametro.'%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')->where(function (
                    $query
                ) use ($parametro) {
                    $query->whereHas('hasRespuestas', function ($query2) use ($parametro) {
                        $query2->where('numero_oficio', $parametro);
                    })->orWhere('numero_oficio', $parametro);
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')->where('asunto','like','%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Numero documento',
                '2' => 'Radicado',
                '3' => 'Consecutivo',
                '4' => 'Asunto',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoAllCoEx', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
                'parametro' => $parametro,
            ]);
        } else {
            return null;
        }
    }

    public function filtrarCoIn($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')->whereHas('hasPeticionario.couldHaveFuncionario', function (
                    $query
                ) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
                ->where('tipo_pqr', 'CoIn')
                ->whereHas('getRadicadoEntrada', function ($query) use ($parametro) {
                    $query->where('numero', 'like', '%' . $parametro . '%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')->where('numero_oficio', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')->where('asunto','like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Nombre funcionario',
                '2' => 'Radicado',
                '3' => 'Consecutivo',
                '4' => 'Asunto',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoAllCoIn', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
                'parametro' => $parametro,
            ]);
        } else {
            return null;
        }
    }

    public function filtrarCoSa($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')->whereHas('hasPeticionario.couldHaveFuncionario', function (
                    $query
                ) use ($parametro) {
                    $query->where('name', 'like', '%'.$parametro.'%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $radicado = explode('-', $parametro);
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
                ->where('tipo_pqr', 'CoSa')
                ->whereHas('getRadicadoSalida', function ($query) use ($parametro) {
                    $query->where('numero', 'like', '%' . $parametro . '%');
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')->where('numero_oficio', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')->where('asunto','like','%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 5:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')->where('radicados_respuesta','like','%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Nombre funcionario',
                '2' => 'Radicado',
                '3' => 'Consecutivo',
                '4' => 'Asunto',
                '5' => 'Radicado respuesta',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoAllCoSa', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
                'parametro' => $parametro,
            ]);
        } else {
            return null;
        }
    }

    public function registrarEntrega(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pqr_entrega' => 'required|integer|exists:gd_pqr,id',
            'fecha_entrega_submit' => 'required|date',
            'documento_entrega' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
        ], [
            'id_pqr_entrega.required' => 'No se ha especificado el ID del proceso PQR.',
            'id_pqr_entrega.integer' => 'El ID del proceso PQR especificado no tiene un formato válido.',
            'id_pqr_entrega.exists' => 'El ID del proceso PQR especificado no existe en el sistema.',
            'fecha_entrega_submit.required' => 'No se ha especificado la fecha de entrega.',
            'fecha_entrega_submit.date' => 'La fecha de entrega especificada no tiene un formato válido.',
            'documento_entrega.mimetypes' => 'El formato del documento entrega no es válido. Debe se PDF.',
            'documento_entrega.max' => 'El tamaño del documento entrega excede el límite permitido de :max Kilobytes.',
            'documento_entrega.mimes' => 'La extensión del documento entrega no es válida. Debe ser PDF.',
            'documento_entrega.required' => 'No se ha suministrado el documento entrega.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $pqr = gd_pqr::find($request->id_pqr_entrega);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede registrar la entrega porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            $url = $request->documento_entrega->storeAs('/CoSa/'.$request->id_pqr_entrega.'/entrega', 'registro_de_entrega.pdf', 'pqr');
            $gd_pqr_entrega = gd_pqr_entrega::create([
                'gd_pqr_id' => $request->id_pqr_entrega,
                'fecha_entrega' => $request->fecha_entrega_submit,
                'documento_entrega' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if($gd_pqr_entrega != null){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la entrega.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido registrar la entrega. Si el problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Error:',
                ], 200);
            }
        }
    }

    public function realizarEntrega($id_pqr)
    {
        return view('admin.gestion_documental.pqr.registrarEntrega', ['id_pqr' => $id_pqr])->render();
    }

    public function verEntrega($id)
    {
        $entrega = gd_pqr_entrega::find($id);
        if ($entrega != null) {
            return view('admin.gestion_documental.pqr.verEntrega', ['entrega' => $entrega])->render();
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El proceso PQR no cuenta con registro de entrega.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function obtenerDoRa($id)
    {
        $pqr = gd_pqr::with('hasPeticionario.couldHaveFuncionario')->where('uuid', $id)->first();
        if(auth()->user()->hasAnyRoles(['Administrador','Administrador PQR']) || $pqr->comprobarUsuarioAsignacion(auth()->user()->id) || $pqr->comprobarFuncionarioPeticionario(auth()->user()->id)){
            $file = explode('/', $pqr->documento_radicado);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="'.array_last($file).'"',
            ];

            return Response()->download(storage_path('app/pqr/'.$pqr->documento_radicado), array_last($file), $headers);
        }else{
            return response()->view('errors.403', ['message' => 'No tiene permitido acceder a este elemento.']);
        }
    }

    public function obtenerDoEn($id)
    {
        $pqr = gd_pqr_entrega::find($id);
        $file = explode('/', $pqr->documento_entrega);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($file).'"',
        ];

        return Response()->download(storage_path('app/pqr/'.$pqr->documento_entrega), array_last($file), $headers);
    }

    public function eliminarMedio($id)
    {
        $medio = gd_medio_traslado::find($id);
        if ($medio->delete()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el medio.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido eliminar el medio. Si el problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function eliminarClase($id)
    {
        $clase = gd_pqr_clase::find($id);
        if ($clase->delete()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado la clase.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido eliminar la clase. Si la problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function restaurarMedio($id)
    {
        $medio = gd_medio_traslado::withTrashed()->find($id);
        if ($medio->restore()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha restaurado el medio.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido restaurar el medio. Si el problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function restaurarClase($id)
    {
        $clase = gd_pqr_clase::withTrashed()->find($id);
        if ($clase->restore()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha restaurado la clase.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido restaurar la clase. Si la problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function obtenerRadicado($tipoRadicado, $idRadicado)
    {
        $radicado = null;
        $label = null;
        switch ($tipoRadicado) {
            case 'entrada':
                $radicado = gd_radicado_entrada::with('hasOrigen')->find($idRadicado);
                break;
            case 'salida':
                $radicado = gd_radicado_salida::with('hasOrigen')->find($idRadicado);
                break;
            default:
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido imprimir el radicado. El tipo de radicado especificado no es correcto.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
                break;
        }

        /*
         * Validación de impresión: Si ya ha sido impreso se le denegará la orden.
         */
        if($radicado->impreso === null){
            try{
                /*$pdf = \PDF::loadView('admin.gestion_documental.pqr.stickerRadicado', ['radicado'=>$radicado])
                    ->setOption('no-outline', true)
                    ->setOption('margin-bottom', '4.5mm')
                    ->setOption('margin-left', '7.5mm')
                    ->setOption('margin-right', '7.5mm')
                    ->setOption('margin-top', '4.5mm')
                    ->setOption('page-width', '105mm')
                    ->setOption('page-height', '30mm')
                    ->setOption('enable-smart-shrinking', true)
                    ->setOption('debug-javascript', true)
                    ->setOption('enable-javascript', true);
                $pdf = \PDF::loadView('admin.gestion_documental.pqr.radicadoImpresora', ['radicado'=>$radicado, 'tipoRadicado'=>$tipoRadicado])
                    ->setOption('no-outline', true)
                    ->setOption('page-size', 'letter')
                    ->setOption('margin-bottom', '5mm')
                    ->setOption('margin-left', '5mm')
                    ->setOption('margin-right', '5mm')
                    ->setOption('margin-top', '5mm')
                    ->setOption('enable-smart-shrinking', true)
                    ->setOption('enable-javascript', true)
                    ->setOption('encoding', 'utf-8');
                return new Response(
                    $pdf->download('sticker.pdf'),
                    200,
                    array(
                        'Content-Type'          => 'application/pdf',
                        'Content-Disposition'   => 'attach; filename="radicado.pdf"'
                    )
                );*/
                return view('admin.gestion_documental.pqr.radicadoImpresora', ['radicado'=>$radicado, 'tipoRadicado'=>$tipoRadicado])->render();
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido imprimir el radicado. Por favor intente nuevamente y si el problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Errores en validación:',
                ], 200);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido imprimir el radicado. EL radicado ya fue impreso con anterioridad, y por seguridad no se permite su re-impresión.'],
                'encabezado' => 'Errores en validación:',
            ], 200);
        }
    }

    public function uploadFileRadicado(Request $request)
    {
        $pqr = gd_pqr::find($request->idPqr);
        if($pqr->documento_radicado == null){
            try {
                $ruta_archivo = Storage::disk('pqr')->putFile('/'.$pqr->tipo_pqr.'/'.$pqr->id.'/radicado', $request->file);
                if ($ruta_archivo != null && $ruta_archivo != false) {
                    $pqr->documento_radicado = $ruta_archivo;
                    $pqr->save();
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha almacenado el archivo en el servidor.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Ha ocurrido un error inesperado cuando se almacenaba el archivo. Por favor intente nuevamente y si el problema persiste comuníquese con un administrador.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error inesperado. Por favor intente nuevamente y si el problema persiste comuníquese con un administrador.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El proceso PQR ya tiene un documento radicado.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function cargarModalidades()
    {
        if (auth()->user()->hasRole('Administrador')) {
            $modalidades = gd_pqr_modalidad_envio::withTrashed()->paginate(25);
        } else {
            $modalidades = gd_pqr_modalidad_envio::paginate(25);
        }

        return view('admin.gestion_documental.pqr.listadoModalidadesEnvios', ['modalidades' => $modalidades])->render();
    }

    public function nuevaModalidad()
    {
        return view('admin.gestion_documental.pqr.nuevaModalidad');
    }

    public function crearModalidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:gd_pqr_modalidad_envio,name',
            'requiere_empresa' => ['required','string',Rule::in(['SI', 'NO'])]
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la modalidad.',
            'nombre.string' => 'El formato del nombre especificado no es válido.',
            'nombre.unique' => 'El nombre especificado ya está registrado en el sistema.',
            'requiere_empresa.required' => 'No se ha especificado si se requiere una empresa de mensajería.',
            'requiere_empresa.string' => 'El valor especificado para el valor del campo requiere una empresa de mensajería no tiene un formato válido.',
            'requiere_empresa.in' => 'El valor especificado para el valor del campo requiere una empresa de mensajería no está dentro de los permitidos.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $modalidad = gd_pqr_modalidad_envio::create([
                'name' => $request->nombre,
                'requiere_empresa' => $request->requiere_empresa
            ]);

            if($modalidad != null){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la modalidad de envío correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error inesperado. Por favor intente nuevamente y si el problema persiste comuníquese con un administrador.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }
    }

    public function restaurarModalidad($id)
    {
        $modalidad = gd_pqr_modalidad_envio::withTrashed()->find($id);
        if ($modalidad->restore()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha restaurado la modalidad.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido restaurar la modalidad. Si la problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function eliminarModalidad($id)
    {
        $modalidad = gd_pqr_modalidad_envio::find($id);
        if ($modalidad->delete()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado la modalidad.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido eliminar la modalidad. Si la problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }

    public function editarModalidad($id)
    {
        $modalidad = gd_pqr_modalidad_envio::withTrashed()->find($id);
        return view('admin.gestion_documental.pqr.modificarModalidad', ['modalidad'=>$modalidad])->render();
    }

    public function actualizarModalidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modalidad_id' => 'required|integer|exists:gd_pqr_modalidad_envio,id',
            'nombre' => ['required','string', Rule::unique('gd_pqr_modalidad_envio', 'name')->ignore($request->modalidad_id)],
            'requiere_empresa' => ['required','string',Rule::in(['SI', 'NO'])]
        ], [
            'modalidad_id.required' => 'No se ha especificado la modalidad a actualizar.',
            'modalidad_id.integer' => 'El ID especificado de la modalidad no tiene un formato válido.',
            'modalidad_id.exists' => 'La modalidad especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre de la modalidad.',
            'nombre.string' => 'El formato del nombre especificado no es válido.',
            'nombre.unique' => 'El nombre especificado ya está registrado en el sistema.',
            'requiere_empresa.required' => 'No se ha especificado si se requiere una empresa de mensajería.',
            'requiere_empresa.string' => 'El valor especificado para el valor del campo requiere una empresa de mensajería no tiene un formato válido.',
            'requiere_empresa.in' => 'El valor especificado para el valor del campo requiere una empresa de mensajería no está dentro de los permitidos.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $modalidad = gd_pqr_modalidad_envio::withTrashed()->find($request->modalidad_id);
            $modalidad->name = $request->nombre;
            $modalidad->requiere_empresa = $request->requiere_empresa;
            if($modalidad->save()){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la modalidad.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar la modalidad. Si la problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Errores en activación:',
                ], 200);
            }
        }
    }

    public function cambiarFechaLimite($id)
    {
        return view('admin.gestion_documental.pqr.cambiarFechaLimite', ['id'=>$id])->render();
    }

    public function actualizarFechaLimite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr,id',
            'nuevaFecha_submit' => 'required|date',
        ], [
            'id.required' => 'No se ha especificado el proceso PQR a actualizar.',
            'id.integer' => 'El ID del proceso a actualizar no tiene un formato válido.',
            'id.exists' => 'El proceso PQR a actualizar especificado no existe en el sistema.',
            'nuevaFecha_submit.required' => 'No se ha especificado la nueva fecha límite.',
            'nuevaFecha_submit.date' => 'El valor especificado para la nueva fecha no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = false;
            $pqr = null;
            try{
                $pqr = gd_pqr::with('hasPeticionario','hasPeticionario.couldHaveFuncionario', 'getRadicadoEntrada', 'getRadicadoSalida')->find($request->id);
                if($pqr->hasAnulacion != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede cambiar la fecha ñímite porque el proceso está anulado.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
                $pqr->limite_respuesta = $request->nuevaFecha_submit;
                $pqr->save();
                $success = true;
            }catch (\Exception $e){

            }

            if($success){
                if($pqr->hasPeticionario->tipo_usuario == 'P'){
                    if($pqr->hasPeticionario->correo_notificacion != null){
                        try{
                            \Mail::send(new cambioFechaLimitePQR($pqr, $pqr->hasPeticionario->correo_notificacion));
                        }catch (\Exception $e){

                        }
                    }
                } else {
                    try{
                        \Mail::send(new cambioFechaLimitePQR($pqr, $pqr->hasPeticionario->couldHaveFuncionario->email));
                    }catch (\Exception $e){

                    }
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la fecha límite.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar la fecha límite. Si la problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Errores en la actualización:',
                ], 200);
            }
        }
    }

    public function registrarEnvio($id)
    {
        $modalidadesEnvios = gd_pqr_modalidad_envio::pluck('name','id');
        $empresasEnvios = empresa_mensajeria::pluck('name','id');
        return view('admin.gestion_documental.pqr.registrarEnvio', ['id'=>$id,'modalidadesEnvios'=>$modalidadesEnvios,'empresasEnvios'=>$empresasEnvios])->render();
    }

    public function guardarEnvio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modalidad_envio' => 'required|integer|exists:gd_pqr_modalidad_envio,id',
            'empresa_envio' => 'integer|exists:empresa_mensajeria,id',
            'fecha_envio_submit' => 'required|date_format:"Y-m-d"',
            'hora_envio_submit' => 'required|date_format:"H:i"',
            'numero_guia' => 'string|nullable'
        ], [
            'modalidad_envio.required' => 'No se ha especificado la modalidad de envío.',
            'modalidad_envio.integer' => 'El ID especificado para la modalidad de envío no tiene un formato válido.',
            'modalidad_envio.exists' => 'La modalidad de envío especificada no existe en el sistema.',
            'empresa_envio.integer' => 'El ID de la empresa de mensajería especificada no tiene un formato válido.',
            'empresa_envio.exists' => 'La empresa de mensajería especificada no existe en el sistema.',
            'fecha_envio_submit.required' => 'No se ha especificado la fecha de envío.',
            'fecha_envio_submit.date_format' => 'La fecha de envío especificada no tiene un formato válido. Debe ser Año-mes-día.',
            'hora_envio_submit.required' => 'No se ha especificado la hora de envío.',
            'hora_envio_submit.date_format' => 'La hora de envío especificada no tiene un formato válido. Debe ser Hora-minuto.',
            'numero_guia.string' => 'El número de guía no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        } else {
            $pqr = gd_pqr::find($request->id);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede realizar el registro de envío porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            $empresa_transporte = null;
            $fecha = $request->fecha_envio_submit;
            $hora = $request->hora_envio_submit;
            $fecha_hora = $fecha.' '.$hora;
            $modalidad_envio = gd_pqr_modalidad_envio::find($request->modalidad_envio);
            if($modalidad_envio->requiere_empresa == 'SI'){
                if($request->numero_guia == null || $request->empresa_envio == null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha especificado la empresa de envío y/o el número de guía.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }else{
                    $empresa_transporte =  $request->empresa_envio;
                }
            }else{
                $request->numero_guia = null;
                $request->empresa_envio = null;
            }
            \DB::beginTransaction();
            try{
                $pqr_envio = new gd_pqr_envio();
                $pqr_envio->gd_pqr_id = $request->id;
                $pqr_envio->empresa_mensajeria_id = $empresa_transporte;
                $pqr_envio->gd_pqr_modalidad_envio_id = $request->modalidad_envio;
                $pqr_envio->fecha_hora_envio = $fecha_hora;
                $pqr_envio->numero_guia = $request->numero_guia;
                $pqr_envio->save();
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                $success = false;
                \DB::rollBack();
            }

            if ($success) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la información del envío.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido guardar la información del envío.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function reUploadFileRadicadoE1($id)
    {
        $pqr = gd_pqr::find($id);
        if($pqr != null){
            if($pqr->documento_radicado == null){
                return view('admin.gestion_documental.pqr.reUploadFileRadicado', ['id'=>$id])->render();
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El proceso ya tiene vinculado un archivo de radicado.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El proceso especificado no existe en el sistema o es inaccesible.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function reUploadFileRadicadoE2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr,id',
            'archivo' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
        ], [
            'id.required' => 'No se ha especificado el proceso PQR.',
            'id.integer' => 'El ID del proceso especificado no tiene un formato válido.',
            'id.exists' => 'El proceso especificado no existe en el sistema.',
            'archivo.required' => 'No se ha especificado el documento radicado.',
            'archivo.mimetypes' => 'El documento radicado especificado no tiene un formato válido. (PDF)',
            'archivo.mimes' => 'El documento radicado especificado no tiene un formato válido. (PDF)',
            'archivo.max' => 'El documento radicado especificado supera el tamaño máximo permitido de :max MB.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }else{
            $pqr = gd_pqr::find($request->id);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede cargar el documento porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            $ruta_archivo = Storage::disk('pqr')->putFile($pqr->tipo_pqr.'/'.$request->id.'/radicado', $request->file('archivo'));
            if ($ruta_archivo != null && $ruta_archivo != false) {
                $pqr->documento_radicado = $ruta_archivo;
                $pqr->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha almacenado el archivo en el servidor.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error inesperado cuando se almacenaba el archivo. Por favor intente nuevamente y si el problema persiste comuníquese con un administrador.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }
    }

    public function reGenerarPDF($id)
    {
        try {
            $pqr = gd_pqr::with('getMedioTraslado','getRadicadoEntrada')->find($id);
            if($pqr->tipo_pqr == 'CoEx'){
                if($pqr->getMedioTraslado->name == 'FORMULARIO WEB'){
                    if($pqr->pdf == null || $pqr->pdf == ''){
                        $pdf = \PDF::loadView('publico.pqr.imprimirRadicado', [
                            'pqr' => $pqr,
                            'radicado' => $pqr->getRadicadoEntrada,
                        ])->setOption('margin-bottom', 20)->setOption('margin-left', 30)->setOption('margin-right', 20)->setOption('margin-top', 20)->setOption('images', true)->setOption('page-size', 'letter')->setOption('no-outline', true)->setOption('enable-smart-shrinking', true);

                        $url = '/CoEx/'.$pqr->id.'/radicado/'.$pqr->created_at->format('Y-m-d_H-i-s').'.pdf';
                        Storage::disk('pqr')->put($url, $pdf->download($pqr->id.'-'.$pqr->created_at->format('Y-m-d_H-i-s') . '.pdf'));
                        $pqr->pdf = $url;
                        $pqr->save();
                        return response()->view('admin.mensajes.success', [
                            'mensaje' => 'Se ha generado el PDF.',
                            'encabezado' => '¡Completado!',
                        ], 200);
                    }else{
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['El Proceso ya tiene vinculado un documento.'],
                            'encabezado' => 'Error en el proceso:',
                        ], 200);
                    }
                }else{
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El tipo de Proceso no requiere realizar esta función.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El tipo de Proceso no requiere realizar esta función.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error inesperado. Por favor intente nuevamente y si el problema persiste comuníquese con un administrador.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function vincularRadicadosEntradaF1($id)
    {
        return view('admin.gestion_documental.pqr.vincularRadicadosEntrada', ['id'=>$id])->render();
    }

    public function vincularRadicadosEntradaF2(Request $request)
    {
        if(isset($request->radicados_respuesta)){
            try{
                $pqr = gd_pqr::find($request->id);
                if($pqr->hasAnulacion != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede vincular radicados de entrada porque el proceso está anulado.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
                $radicados_respuesta_cadena = null;
                foreach ($request->radicados_respuesta as $radicado_respuesta){
                    $radicado_respuesta = str_replace(' ', '', $radicado_respuesta);
                    if($radicados_respuesta_cadena != ''){
                        $radicados_respuesta_cadena = $radicados_respuesta_cadena.','.strtoupper($radicado_respuesta);
                    }else{
                        $radicados_respuesta_cadena = strtoupper($radicado_respuesta);
                    }
                    $pqr_respondido = gd_pqr::with('getRadicadoEntrada', 'hasPeticionario', 'hasRespuestas', 'hasPeticionario.couldHaveFuncionario')
                        ->whereHas('getRadicadoEntrada', function($query) use ($radicado_respuesta) {
                            $query->where('numero', strtoupper($radicado_respuesta));
                        })->first();
                    if($pqr_respondido != null){
                        if($pqr_respondido->gd_pqr_respuesta_id == null){
                            $pqr_respondido->hasRespuestas()->attach($pqr->id);
                            if($pqr_respondido->getAsignacionesActivas() == null){
                                gd_pqr_asignacion::create([
                                    'funcionario_id' => \Auth::user()->id,
                                    'dependencia_id' => $pqr->hasPeticionario->couldHaveFuncionario->hasDependencia->id,
                                    'usuario_asignado_id' => $pqr->hasPeticionario->couldHaveFuncionario->id,
                                    'gd_pqr_id' => $pqr_respondido->id,
                                    'estado' => 1,
                                    'responsable' => 1,
                                ]);
                            }
                            $this->notificarRespuestaPQR($pqr_respondido);
                        }
                    }
                }
                $pqr->radicados_respuesta = $radicados_respuesta_cadena;
                $pqr->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han vinculado los radicados exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se especificado ningún radicado de entrada..'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function filtrarMisProcesosCoEx($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')
                ->whereHas('hasAsignaciones', function ($query) {
                    $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                })->whereHas('hasPeticionario', function ($query) use ($parametro){
                    $query->where('numero_documento', $parametro);
                })->orderBy('created_at', 'desc')->paginate(50);
                break;            
            case 2:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
                    ->where('tipo_pqr', 'CoEx')
                    ->whereHas('hasAsignaciones', function ($query) {
                        $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                    })->whereHas('getRadicadoEntrada', function ($query) use ($parametro) {
                        $query->where('numero', 'like', '%' . $parametro . '%');
                    })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')
                ->whereHas('hasAsignaciones', function ($query) {
                    $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                })->where(function ($query) use ($parametro) {
                    $query->whereHas('hasRespuestas', function ($query2) use ($parametro) {
                        $query2->where('numero_oficio', $parametro);
                    })->orWhere('numero_oficio', $parametro);
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoEx')
                ->whereHas('hasAsignaciones', function ($query) {
                    $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                })->where('asunto', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;    
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Numero documento',
                '2' => 'Radicado',
                '3' => 'Consecutivo',
                '4' => 'Asunto',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoMisProcesosCoEx', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
            ])->render();
        } else {
            return null;
        }        
    }

    public function filtrarMisProcesosCoIn($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')
                    ->whereHas('hasAsignaciones', function ($query) {
                        $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                    })
                    ->orWhereHas('hasPeticionario', function ($query){
                        $query->where('funcionario_id', auth()->user()->id);
                    })
                    ->where('', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $radicado = explode('-', $parametro);
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')
                    ->where('tipo_pqr', 'CoIn')
                    ->whereHas('hasAsignaciones', function ($query) {
                        $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                    })->orWhereHas('hasPeticionario', function ($query) {
                        $query->where('funcionario_id', auth()->user()->id);
                    })->whereHas('getRadicadoEntrada', function ($query) use ($parametro) {
                        $query->where('numero', 'like', '%' . $parametro . '%');
                    })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')
                    ->whereHas('hasPeticionario', function ($query){
                        $query->where('funcionario_id', auth()->user()->id);
                    })->orWhereHas('hasAsignaciones', function ($query) {
                        $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                    })->where(function ($query) use ($parametro) {
                        $query->whereHas('hasRespuestas', function ($query2) use ($parametro) {
                            $query2->where('numero_oficio', $parametro);
                        })->orWhere('numero_oficio', $parametro);
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoIn')
                    ->whereHas('hasPeticionario', function ($query){
                        $query->where('funcionario_id', auth()->user()->id);
                    })->orWhereHas('hasAsignaciones', function ($query) {
                        $query->where('usuario_asignado_id', '=', auth()->user()->id)->where('estado', '=', '1');
                    })->where('asunto', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Numero documento',
                '2' => 'Radicado',
                '3' => 'Consecutivo',
                '4' => 'Asunto',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoMisProcesosCoIn', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
            ])->render();
        } else {
            return null;
        }
    }

    public function filtrarMisProcesosCoSa($parametro, $tipo)
    {
        $pqrs = null;
        switch ($tipo) {
            case 1:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')
                    ->whereHas('hasPeticionario', function ($query) use ($parametro) {
                        $query->where('funcionario_id', '=', auth()->user()->id);
                    })->whereHas('getRadicadoSalida', function ($query) use ($parametro) {
                        $query->where('numero', 'like', '%' . $parametro . '%');
                    })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')
                ->whereHas('hasPeticionario', function ($query) use ($parametro){
                    $query->where('funcionario_id', '=', auth()->user()->id);
                })->where(function ($query) use ($parametro) {
                    $query->whereHas('hasRespuestas', function ($query2) use ($parametro) {
                        $query2->where('numero_oficio', $parametro);
                    })->orWhere('numero_oficio', $parametro);
                })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')
                ->whereHas('hasPeticionario', function ($query) use ($parametro){
                    $query->where('funcionario_id', '=', auth()->user()->id);
                })->where('asunto', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $pqrs = gd_pqr::with('hasPeticionario', 'getMedioTraslado', 'getRadicadoEntrada', 'getRadicadoSalida', 'hasClase', 'hasClasificacion', 'hasAsignaciones', 'hasRespuestas')->where('tipo_pqr', 'CoSa')
                ->whereHas('hasPeticionario', function ($query) use ($parametro) {
                    $query->where('funcionario_id', '=', auth()->user()->id);
                })->where('radicados_respuesta', 'like', '%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
        }

        if (count($pqrs) > 0) {
            $filtros = [
                '1' => 'Radicado',
                '2' => 'Consecutivo',
                '3' => 'Asunto',
                '4' => 'Radicado respuesta',
            ];
            $sFiltro = $tipo;

            return view('admin.gestion_documental.pqr.listadoMisProcesosCoSa', [
                'pqrs' => $pqrs,
                'filtros' => $filtros,
                'sFiltro' => $sFiltro,
            ])->render();
        } else {
            return null;
        }
    }

    public function cambiarClase($id)
    {
        $clases = gd_pqr_clase::orderBy('name')->pluck('name','id');
        return view('admin.gestion_documental.pqr.cambiarClase',['id'=>$id,'clases'=>$clases])->render();
    }

    public function registrarCambioClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr,id',
            'clase' => 'required|integer|exists:gd_pqr_clase,id',
        ], [
            'id.required' => 'No se ha especificado el proceso PQR.',
            'id.integer' => 'El ID del proceso especificado no tiene un formato válido.',
            'id.exists' => 'El proceso especificado no existe en el sistema.',
            'clase.required' => 'No se ha especificado la clase a cambiar.',
            'clase.integer' => 'El ID de la clase a cambiar no tiene un formato válido.',
            'clase.exists' => 'La clase especificada no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }else{
            try{
                $pqr = gd_pqr::find($request->id);
                if($pqr->hasAnulacion != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se puede realizar el cambio de clase porque el proceso está anulado.'],
                        'encabezado' => 'Error en el proceso:',
                    ], 200);
                }
                $pqr->gd_pqr_clase_id = $request->clase;
                $pqr->save();
                if($pqr->hasClase->required_answer === 'SI'){
                    if ($pqr->hasClase->dia_clase == 'HABIL') {
                        $dias = calendario::whereDate('fecha', '>=', Carbon::createFromFormat('Y-m-d H:i:s', $pqr->created_at)->toDateString())->where('laboral', '1')->take($pqr->hasClase->dia_cantidad)->get();
                    } else {
                        $dias = calendario::whereDate('fecha', '>=', Carbon::createFromFormat('Y-m-d H:i:s', $pqr->created_at)->toDateString())->take($pqr->hasClase->dia_cantidad)->get();
                    }
                    $pqr->limite_respuesta = $dias->last()->fecha;
                    $pqr->save();
                }else{
                    $pqr->limite_respuesta = null;
                    $pqr->save();
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se cambiado la clase exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }
    }

    public function verEnvio($id)
    {
        $pqr = gd_pqr::find($id);
        return view('admin.gestion_documental.pqr.verEnvio', ['pqr'=>$pqr])->render();
    }

    public function cambiarFuncionarioF1($pqrId,$funcionarioId)
    {
        $pqr = gd_pqr::find($pqrId);
        if($pqr->tipo_pqr != 'CoEx'){
            $funcionarios = User::where('lock_session', 'no')->pluck('name','id');
            return view('admin.gestion_documental.pqr.cambiarFuncionario',['pqrId'=>$pqrId,'funcionarioId'=>$funcionarioId,'funcionarios'=>$funcionarios])->render();        
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El tipo de proceso PQR no admite este cambio.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function cambiarFuncionarioF2(Request $request)
    {
        $pqr = gd_pqr::find($request->id);
        if($pqr->hasAnulacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se puede realizar el cambio de funcionario porque el proceso está anulado.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
        if($pqr->tipo_pqr != 'CoEx'){
            try{
                $pqr->hasPeticionario->funcionario_id = $request->funcionario;
                $pqr->hasPeticionario->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se cambiado el funcionario exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch(\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Se ha producido un error en la operación.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }          
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El tipo de proceso PQR no admite este cambio.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function eliminarRadicadoContestacion($pqrId, $radicado)
    {
        $pqr = gd_pqr::find($pqrId);
        if($pqr->hasAnulacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se puede realizar la eliminación del radicado de contestación poque el proceso está anulado.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
        try{
            \DB::beginTransaction();
            /*
             * Se arregla el formato
             */
            $radicado = str_replace(' ', '', strtoupper($radicado));
            /*
             * Obtenemos al pqr de salida
             */
            $pqr = gd_pqr::find($pqrId);
            /*
             * Se elimina el radicado de la cadena del campo_radicados_respuesta del pqr de salida.
             */
            $radicados = explode(',',$pqr->radicados_respuesta);
            $index = array_search($radicado,$radicados);
            if($index !== FALSE){
                unset($radicados[$index]);
            }
            /*
             * Se determina si el radicado anterior existe en el sistema, y en caso positivo eliminar la relacion con el pqr de salida
             */
            $pqrContestado = gd_pqr::whereHas('getRadicadoEntrada', function ($query) use ($radicado){
                $query->where('numero', $radicado);
            })->first();
            if($pqrContestado != null){
                $pqr->getRespondidos()->detach($pqrContestado->id);
            }
            /*
             * Se crea nuevamente la cadena separada con comas para actualizar el campo radicados_respuesta del pqr de salida.
             */
            $cadena_radicado = null;
            if(count($radicados) > 0){
                foreach ($radicados as $radicadostr){
                    if($cadena_radicado == null){
                        $cadena_radicado = $radicadostr;
                    }else{
                        $cadena_radicado = $cadena_radicado.','.$radicadostr;
                    }
                }
            }
            /*
             * Se actualiza el campo radicados_respuesta del pqr de salida
             */
            $pqr->radicados_respuesta = $cadena_radicado;
            $pqr->save();
            \DB::commit();
            /*
             * Se notifica al anterior destinatario la desvinculacion
             */
            if($pqrContestado->hasPeticionario->correo_notificacion != null){
                try{
                    \Mail::send(new DesvinculacionRadicadoRespuesta($pqrContestado, $radicado));
                }catch (\Exception $e){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Se ha eliminado el radicado, pero ha ocurrido un error en la notificación por correo. Deberá notificar al anterior usuario por otros medios.'],
                        'encabezado' => 'Realizado, pero con errores:',
                    ], 200);
                }
            }
            /*
             * Retorna respuesta
             */
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el radicado exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en la operación.'],
                'encabezado' => 'Sin cambios:',
            ], 200);
        }
    }

    public function modificarRadicadoContestacion(Request $request)
    {
        $pqr = gd_pqr::find($request->pqrId);
        if($pqr->hasAnulacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se puede realizar cambios en los radicados de contestación porque el proceso está anulado.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
        try{
            \DB::beginTransaction();
            $pqrId = $request->pqrId;
            $radicadoAnterior = $request->radicadoAnterior;
            $radicadoNuevo = $request->radicadoNuevo;
            /*
            * Se valida que sean distintos
            */
            if($radicadoAnterior === $radicadoNuevo){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El radicado anterior y el nuevo son iguales.'],
                    'encabezado' => 'Sin cambios:',
                ], 200);
            }
            /*
             * Se arregla formato
             */
            $radicadoAnterior = str_replace(' ', '', strtoupper($radicadoAnterior));
            $radicadoNuevo = str_replace(' ', '', strtoupper($radicadoNuevo));
            /*
             * Se carga el pqr de salida
             */
            $pqr = gd_pqr::find($pqrId);
            /*
             * Se elimina el radicado de la cadena del campo_radicados_respuesta del pqr de salida.
             */
            $radicados = explode(',',$pqr->radicados_respuesta);
            $index = array_search($radicadoAnterior,$radicados);
            if($index !== FALSE){
                unset($radicados[$index]);
            }
            /*
             * Se determina si el radicado anterior existe en el sistema, y en caso positivo eliminar la relacion con el pqr de salida
             */
            $pqrContestado = gd_pqr::whereHas('getRadicadoEntrada', function ($query) use ($radicadoAnterior){
                $query->where('numero', $radicadoAnterior);
            })->first();
            if($pqrContestado != null){
                $pqr->getRespondidos()->detach($pqrContestado->id);
            }
            /*
             * Se determina si el radicado nuevo existe en el sistema, y en caso positivo vincular la relacion con el pqr de salida
             */
            $pqrAContestar = gd_pqr::whereHas('getRadicadoEntrada', function ($query) use ($radicadoNuevo){
                $query->where('numero', $radicadoNuevo);
            })->first();
            if($pqrAContestar != null){
                $pqr->getRespondidos()->attach($pqrAContestar->id);
            }
            /*
             * Se crea nuevamente la cadena separada con comas para actualizar el campo radicados_respuesta del pqr de salida.
             */
            if(count($radicados) <= 0){
                $cadena_radicado = $radicadoNuevo;
            }else{
                $cadena_radicado = null;
                foreach ($radicados as $radicadostr){
                    if($cadena_radicado == null){
                        $cadena_radicado = $radicadostr;
                    }else{
                        $cadena_radicado = $cadena_radicado.','.$radicadostr;
                    }
                }
                $cadena_radicado = $cadena_radicado.','.$radicadoNuevo;
            }
            /*
             * Se actualiza el campo radicados_respuesta del pqr de salida
             */
            $pqr->radicados_respuesta = $cadena_radicado;
            $pqr->save();
            \DB::commit();
            /*
             * Se notifica por correo la respuesta
             */
            if($pqrAContestar != null){
                if($pqrAContestar->hasPeticionario->correo_notificacion != null){
                    try{
                        $this->notificarRespuestaPQR($pqrAContestar);
                    }catch (\Exception $e){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['Se ha eliminado el radicado, pero ha ocurrido un error en la notificación por correo. Deberá notificar al anterior usuario por otros medios.'],
                            'encabezado' => 'Realizado, pero con errores:',
                        ], 200);
                    }
                }
            }
            /*
             * Se notifica al anterior destinatario la desvinculacion
             */
            if($pqrContestado != null){
                if($pqrContestado->hasPeticionario->correo_notificacion != null){
                    try{
                        \Mail::send(new DesvinculacionRadicadoRespuesta($pqrContestado,$radicadoAnterior));
                    }catch (\Exception $e){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['Se ha eliminado el radicado, pero ha ocurrido un error en la notificación por correo. Deberá notificar al anterior usuario por otros medios.'],
                            'encabezado' => 'Realizado, pero con errores:',
                        ], 200);
                    }
                }
            }            
            /*
             * Retorna respuesta
             */
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha cambiado el radicado exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            \DB::rollBack();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en la operación.'],
                'encabezado' => 'Sin cambios:',
            ], 200);
        }
    }

    public function verPeticionario($id)
    {
        $pqr = gd_pqr::find($id);
        if($pqr->tipo_pqr != 'CoEx'){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Solo se puede visualizar la infomación de los peticionarios cuando el proceso PQR es externo.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
        if($pqr->comprobarUsuarioAsignacion(auth()->user()->id)){
            return view('admin.gestion_documental.pqr.verPeticionario', ['peticionario'=>$pqr->hasPeticionario])->render();
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Solo los funcionarios asignados al proceso pueden visualizar la información del peticionario.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function cambiarMedioTraslado($id)
    {
        $medios = gd_medio_traslado::orderBy('name')->pluck('name','id');
        return view('admin.gestion_documental.pqr.cambiarMedioTraslado',['id'=>$id,'medios'=>$medios])->render();
    }

    public function registrarCambioMedioTraslado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr,id',
            'medio' => 'required|integer|exists:gd_medio_traslado,id',
        ], [
            'id.required' => 'No se ha especificado el proceso PQR.',
            'id.integer' => 'El ID del proceso especificado no tiene un formato válido.',
            'id.exists' => 'El proceso especificado no existe en el sistema.',
            'medio.required' => 'No se ha especificado el medio de traslado a cambiar.',
            'medio.integer' => 'El ID del medio de traslado a cambiar no tiene un formato válido.',
            'medio.exists' => 'El medio de traslado especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }else{
            $pqr = gd_pqr::find($request->id);
            if($pqr->hasAnulacion != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se puede realizar el cambio de traslado porque el proceso está anulado.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            try{
                $pqr = gd_pqr::find($request->id);
                $pqr->gd_medio_traslado_id = $request->medio;
                $pqr->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se cambiado el medio de traslado exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error en el proceso.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
        }
    }

    public function anularProceso($id)
    {
        $motivos = gd_pqr_anulacion_motivo::pluck('name','id');
        return view('admin.gestion_documental.pqr.anular',['id'=>$id,'motivos'=>$motivos])->render();
    }

    public function registrarAnulacionProceso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr,id|unique:gd_pqr_anulacion,gd_pqr_id',
            'motivo' => 'required|integer|exists:gd_pqr_anulacion_motivo,id',
            'observacion' => 'required|string'
        ], [
            'id.required' => 'No se ha especificado el proceso PQR a anular.',
            'id.integer' => 'El ID del proceso PQR no tiene un formato válido.',
            'id.exists' => 'El proceso PQR especificado no existe en el sistema.',
            'motivo.required' => 'No se ha especificado el motivo de la anulación.',
            'motivo.integer' => 'El ID del motivo de anulación no tiene un formato válido.',
            'motivo.exists' => 'El motivo de anulación especificado no existe en el sistema.',
            'observacion.required' => 'No se ha proporcionado la observación sobre la anulación del proceso.',
            'observacion.string' => 'La observación proporcionada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }

        try{
            $pqr = gd_pqr::with('hasPeticionario','hasPeticionario.couldHaveFuncionario','getRespondidos')->find($request->id);
            if($pqr->getAsignacionesActivas() != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El proceso no puede ser anulado por cuanto tiene asignaciones de funcionarios activas.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            if($pqr->hasRespuestas->count() > 0){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El proceso no puede ser anulado por cuanto tiene respuesta(s).'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            if($pqr->hasEnvio != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El proceso no puede ser anulado por cuanto tiene un registro de envío.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            if($pqr->hasEntreg != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El proceso no puede ser anulado por cuanto tiene un registro de entrega.'],
                    'encabezado' => 'Error en el proceso:',
                ], 200);
            }
            gd_pqr_anulacion::create([
                'gd_pqr_id' => $request->id,
                'gd_pqr_anulacion_mo_id' => $request->motivo,
                'funcionario_id' => auth()->user()->id,
                'observation' => $request->observacion
            ]);
            if($pqr->hasPeticionario->correo_notificacion != null){
                try{
                    \Mail::send(new anulacionProceso($pqr, $pqr->hasPeticionario->correo_notificacion));
                }catch (\Exception $i){

                }
            }elseif($pqr->hasPeticionario->funcionario_id != null){
                try{
                    \Mail::send(new anulacionProceso($pqr, $pqr->hasPeticionario->couldHaveFuncionario->email));
                }catch (\Exception $i){

                }
            }
            if($pqr->tipo_pqr == 'CoSa'){
                foreach ($pqr->getRespondidos as $respuesta){
                    $respuesta->load('hasPeticionario','hasPeticionario.couldHaveFuncionario');
                    if($respuesta->hasPeticionario->correo_notificacion != null){
                        try{
                            \Mail::send(new anulacionRespuestaPQR($pqr, $respuesta, $respuesta->hasPeticionario->correo_notificacion));
                        }catch (\Exception $i){

                        }
                    }elseif($respuesta->hasPeticionario->funcionario_id != null){
                        try{
                            \Mail::send(new anulacionRespuestaPQR($pqr, $respuesta, $respuesta->hasPeticionario->couldHaveFuncionario->email));
                        }catch (\Exception $i){

                        }
                    }
                }
            }
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha anulado el proceso exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function obtenerMotivosAnulacion()
    {
        $motivos = gd_pqr_anulacion_motivo::paginate(15);
        return view('admin.gestion_documental.pqr.listadoMotivosAnulacion',['motivos'=>$motivos])->render();
    }

    public function nuevoMotivoAnulacion()
    {
        return view('admin.gestion_documental.pqr.nuevoMotivoAnulacion')->render();
    }

    public function crearMotivoAnulacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:gd_pqr_anulacion_motivo,name'
        ], [
            'nombre.required' => 'No se ha especificado el nombre para el motivo de anulación.',
            'nombre.string' => 'El nombre especificado no tiene un formato válido.',
            'nombre.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }

        try{
            gd_pqr_anulacion_motivo::create([
                'name' => $request->nombre
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el motivo de anulación correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function editarMotivoAnulacion($id)
    {
        $motivo = gd_pqr_anulacion_motivo::find($id);
        return view('admin.gestion_documental.pqr.editarMotivoAnulacion',['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoAnulacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:gd_pqr_anulacion_motivo,id',
            'nombre' => ['required','string',Rule::unique('gd_pqr_anulacion_motivo','name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el motivo de anulación a modificar.',
            'id.integer' => 'El ID del motivo de anulación a modificar no tiene un formato válido.',
            'id.exists' => 'El motivo de anulación a modificar no existe en el sistema.', 
            'nombre.required' => 'No se ha especificado el nombre para el motivo de anulación.',
            'nombre.string' => 'El nombre especificado no tiene un formato válido.',
            'nombre.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        }

        try{
            $motivo = gd_pqr_anulacion_motivo::find($request->id);
            $motivo->name = $request->nombre;
            $motivo->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el motivo anulación correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un error en el proceso.'],
                'encabezado' => 'Error en el proceso:',
            ], 200);
        }
    }

    public function verAnulacion($id)
    {
        $anulacion = gd_pqr_anulacion::where('gd_pqr_id',$id)->first();
        return view('admin.gestion_documental.pqr.verAnulacion',['anulacion'=>$anulacion])->render();
    }

    private function vincularPorNumeroOficioResponde($pqrRadicadoId, $numeroOficio)
    {
        $pqr = gd_pqr::where('numero_oficio', $numeroOficio)->first();
        if($pqr != null){
            if($pqr->tipo_pqr == 'CoSa'){
                if($pqr->getRespondidos->count() > 0){
                    $asignaciones = $pqr->getRespondidos->first()->getAsignacionesActivas();
                    foreach($asignaciones as $asignacion){
                        $asignacion2 = gd_pqr_asignacion::create([
                            'funcionario_id' => \Auth::user()->id,
                            'dependencia_id' => $asignacion->dependencia_id,
                            'usuario_asignado_id' => $asignacion->usuario_asignado_id,
                            'gd_pqr_id' => $pqrRadicadoId,
                            'estado' => 1,
                            'responsable' => $asignacion->responsable,
                        ]);
                        \Notification::send($asignacion2->hasFuncionario, new AsignacionPQR($asignacion));
                        event(new FuncionarioPQR($asignacion2->hasFuncionario->id));
                    }
                }else{
                    $asignacion = gd_pqr_asignacion::create([
                        'funcionario_id' => \Auth::user()->id,
                        'dependencia_id' => $pqr->hasPeticionario->dependencia_id,
                        'usuario_asignado_id' => $pqr->hasPeticionario->funcionario_id,
                        'gd_pqr_id' => $pqrRadicadoId,
                        'estado' => 1,
                        'responsable' => 1,
                    ]);
                    \Notification::send($asignacion->hasFuncionario, new AsignacionPQR($asignacion));
                    event(new FuncionarioPQR($asignacion->hasFuncionario->id));
                }
            }else{                
                foreach($pqr->getAsignacionesActivas() as $asignacion){
                    $asignacion2 = gd_pqr_asignacion::create([
                        'funcionario_id' => \Auth::user()->id,
                        'dependencia_id' => $asignacion->dependencia_id,
                        'usuario_asignado_id' => $asignacion->usuario_asignado_id,
                        'gd_pqr_id' => $pqrRadicadoId,
                        'estado' => 1,
                        'responsable' => $asignacion->responsable,
                    ]);
                    \Notification::send($asignacion2->hasFuncionario, new AsignacionPQR($asignacion));
                    event(new FuncionarioPQR($asignacion2->hasFuncionario->id));
                }
            }
        }
    }
}