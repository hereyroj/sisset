<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ciudad;
use App\cm_pago;
use App\comparendo;
use App\comparendo_infraccion;
use App\comparendo_infractor;
use App\comparendo_inmovilizacion_tipo;
use App\comparendo_tipo;
use App\comparendo_vehiculo;
use App\departamento;
use App\empresa_transporte;
use App\gd_pqr;
use App\User;
use App\user_agente;
use App\usuario_tipo_documento;
use App\vehiculo_clase;
use App\vehiculo_radio_operacion;
use App\vehiculo_servicio;
use Validator;
use Illuminate\Validation\Rule;
use App\comparendo_inmovilizacion;
use App\comparendo_infractor_tipo;
use App\vehiculo_nivel_servicio;
use App\licencia_categoria;
use App\comparendo_entidad;
use App\comparendo_testigo;
use PhpOffice\PhpWord\TemplateProcessor;
use App\sancion;
use App\sistema_parametros_gd;
use NcJoes\OfficeConverter\OfficeConverter;

class ComparendoController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Número documento propietario',
            '2' => 'Número documento infractor',
            '3' => 'Número comparendo',
            '4' => 'Placa',
        ];
        $sFiltro = null;

        return view('admin.inspeccion.comparendos.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function obtenerDescripcionInfraccion($id)
    {
        $infraccion = comparendo_infraccion::find($id);
        return $infraccion->descripcion;
    }

    public function obtenerInfracciones($id)
    {
        $infracciones = comparendo_infraccion::where('comparendo_tipo_id', $id)->pluck('name', 'id');
        return $infracciones;
    }

    public function obtenerComparendos()
    {
        $comparendos = comparendo::with('hasInfraccion', 'hasTipoComparendo', 'hasPago', 'hasTipoInmovilizacion')->paginate(50);
        return view('admin.inspeccion.comparendos.listado', ['comparendos'=>$comparendos])->render();
    }

    public function verVehiuclo($comparendo_id)
    {
        $vehiculo = comparendo_vehiculo::where('comparendo_id', $comparendo_id)->first();
        return view('admin.inspeccion.comparendos.verVehiculo', ['vehiculo'=>$vehiculo])->render();
    }

    public function verAgente($comparendo_id)
    {
        $comparendo = comparendo::with('hasAgente','hasAgente.hasUsuario')->find($comparendo_id);
        return view('admin.inspeccion.comparendos.verAgente', ['agente'=>$comparendo->hasAgente])->render();
    }

    public function verInfractor($comparendo_id)
    {
        $infractor = comparendo_infractor::where('comparendo_id', $comparendo_id)->first();
        return view('admin.inspeccion.comparendos.verInfractor', ['infractor'=>$infractor])->render();
    }

    private function obtenerComplementos()
    {
        $tiposComparendos = comparendo_tipo::pluck('name', 'id');
        $servicios = vehiculo_servicio::pluck('name', 'id');
        $clases = vehiculo_clase::pluck('name', 'id');
        $empresas = empresa_transporte::pluck('name', 'id');
        $radiosOperacion = vehiculo_radio_operacion::pluck('name', 'id');
        $tiposDocumentos = usuario_tipo_documento::pluck('name', 'id');
        $departamentos = departamento::pluck('name', 'id');
        $agentes = User::has('hasAgente')->pluck('name', 'id');
        $tiposInmovilizaciones = comparendo_inmovilizacion_tipo::pluck('name', 'id');
        $infractorTipos = comparendo_infractor_tipo::pluck('name', 'id');
        $nivelesServicios = vehiculo_nivel_servicio::pluck('name', 'id');
        $ciudades = ciudad::pluck('name', 'id');
        $licenciaCategorias = licencia_categoria::pluck('name', 'id');
        return [
            'tiposComparendos' => $tiposComparendos,
            'servicios' => $servicios,
            'clases' => $clases,
            'empresas' => $empresas,
            'radiosOperacion' => $radiosOperacion,
            'tiposDocumentos' => $tiposDocumentos,
            'departamentos' => $departamentos,
            'agentes' => $agentes,
            'tiposInmovilizaciones' => $tiposInmovilizaciones,
            'infractorTipos' => $infractorTipos,
            'nivelesServicios' => $nivelesServicios,
            'ciudades' => $ciudades,
            'licenciaCategorias' => $licenciaCategorias,
        ];
    }

    public function nuevo()
    {
        return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comparendo_numero' => 'string|unique:comparendo,numero',
            'comparendo_fecha_submit' => 'required|date',
            'comparendo_hora_submit' => 'required|date_format:H:i',
            'comparendo_tipo' => 'required|integer|exists:comparendo_tipo,id',
            'comparendo_infraccion' => 'required|integer|exists:comparendo_infraccion,id',
            'comparendo_valor' => 'nullable|string',            
            'comparendo_observacion' => 'nullable|string',
            'comparendo_barrio' => 'required|string',
            'comparendo_direccion' => 'required|string',         
            'propietario_nombre' => 'required|string',
            'conductor_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'conductor_numero_documento' => 'required|numeric',
            'conductor_nombre' => 'required|string',
            'conductor_direccion' => 'nullable|string',
            'conductor_telefono' => 'nullable|numeric',
            'conductor_licencia' => 'nullable|numeric',
            'conductor_licencia_vencimiento_submit' => 'nullable|date',
            'agente' => 'required|integer|exists:user_agente,user_id',
            'conductor_numero_documento' => 'nullable|numeric',
            'conductor_licenciaCategoria' => 'nullable|integer|exists:licencia_categoria,id',
            'conductor_ciudad' => 'nullable|integer|exists:municipio,id',
            'conductor_direccionElectronica' => 'nullable|string',
            'conductor_tipo' => 'nullable|integer|exists:comparendo_infractor_tipo,id',
            'comparendo_fuga' => 'nullable|boolean',
            'alcoholemia_grado' => 'nullable|numeric|exists:',
            'alcoholemia_negacion' => 'nullable|boolean'
        ], [
            'comparendo_numero.required' => 'No se ha especificado el número del comparendo.',
            'comparendo_numero.string' => 'El número de comparendo especificado no tiene un formato válido.',
            'comparendo_numero.unique' => 'El número de comparendo especificado ya está registrado en el sistema.',
            'comparendo_fecha_submit.required' => 'No se ha especificado la fecha en el que se realizó el comparendo.',
            'comparendo_fecha_submit.date' => 'La fecha de realización del comparendo no tiene un formato válido.',
            'comparendo_hora_submit.required' => 'No se ha especificado la hora en la que se realizó el comparendo.',
            'comparendo_hora_submit.date_format' => 'La hora de realización del comparendo no tiene un formato válido.',
            'comparendo_tipo.required' => 'No se ha especificado el tipo de comparendo.',
            'comparendo_tipo.integer' => 'El valor especificado para el tipo de comparendo no tiene un formato válido.',
            'comparendo_tipo.exists' => 'El tipo de comparendo especificado no existe en el sistema.',
            'comparendo_infraccion.required' => 'No se ha especificado la infracción del comparendo.',
            'comparendo_infraccion.integer' => 'El valor especificado para la infracción del comparendo no tiene un formato válido.',
            'comparendo_infraccion.exists' => 'La infracción del comparendo especificado no existe en el sistema.',
            'comparendo_valor.string' => 'El valor del comparendo especificado no tiene un formato válido.',            
            'comparendo_observacion.string' => 'El valor especificado para la observación del comparendo no tiene un formato válido.',
            'comparendo_barrio.required' => 'No se ha especificado el barrio o verada en donde ocurrió el comparendo.',
            'comparendo_barrion.string' => 'El barrio o vereda del comparendo especificado no tiene un formato válido.',
            'comparendo_direccion.required' => 'No se ha especificado la dirección del comparendo',
            'comparendo_direccion.string' => 'La dirección del comparendo especificado no tiene un formato válido.',
            'propietario_nombre.required' => 'No se ha especificado el nombre del propietario del vehículo.',
            'propietario_nombre.string' => 'El valor especificado para el nombre del propietario del vehículo no tiene un formato válido.',
            'conductor_documento.required' => 'No se ha especificado el tipo de documento del infractor.',
            'conductor_documento.integer' => 'El valor especificado para el tipo del documento del infractor no tiene un formato válido.',
            'conductor_documento.exists' => 'El tipo del documento del infractor especificado no existe en el sistema.',
            'conductor_numero_documento.required' => 'No se ha especificado el número de documento del infractor.',
            'conductor_numero_documento.numeric' => 'El valor especificado para el número de documento del infractor no tiene un formato válido.',
            'conductor_nombre.required' => 'No se ha especificado el nombre del infractor.',
            'conductor_nombre.string' => 'El valor especificado para el nombre del infractor no tiene un formato válido.',
            'conductor_direccion.string' => 'El valor especificado para la dirección del infractor no tiene un formato válido.',
            'conductor_telefono.numeric' => 'El valor especificado para el número telefónico del infractor no tiene un formato válido.',
            'conductor_licencia.numeric' => 'El valor especificado para el número la licencia de conducción del infractor no tiene un formato válido.',
            'conductor_licencia_vencimiento_submit.date' => 'El valor especificado para la fecha de vencimiento de la licencia de conducción del infractor no tiene un formato válido.',
            'agente.required' => 'No se ha especificado el agente.',
            'agente.integer' => 'El valor especificado para el agente no tiene un formato válido.',
            'agente.exists' => 'El agente especificado no existe en el sistema.',          
            'conductor_numero_documento.string' => 'El número de documento especificado del infractor no tiene un formato válido.',
            'conductor_licenciaCategoria.integer' => 'La categoría de la licencia del infractor especificada no tiene un formato válido.',
            'conductor_licenciaCategoria.exists' => 'La categoría de la licencia del infractor especificado no existe en el sistema.',
            'conductor_ciudad.integer' => 'La ciudad del infractor especificada no tiene un formato válido.',
            'conductor_ciudad.exists' => 'La ciudad del infractor especificada no existe en el sistema.',
            'conductor_direccionElectronica.string' => 'La dirección electrónica del infractor especificada no tiene un formato válido.',
            'conductor_tipo.integer' => 'El tipo de infractor especificado no tiene un formato válido.',
            'conductor_tipo.exists' => 'El tipo de infractor especificado no existe en el sistema.',
            'comparendo_fuga.boolean' => 'El valor especificado para Fuga no es válido.',
            'alcoholemia_grado.numeric' => 'El valor especificado para Grado alcoholemia no es válido.',
            'alcoholemia_grado.exists' => 'El valor especificado para Grado alcoholemia no es válido.',
            'alcoholemia_negacion.boolean' => 'El valor especificado para Negación alcoholemia no es válido.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
        }

        $comparendoInfraccion = comparendo_infraccion::find($request->comparendo_infraccion);
        if($comparendoInfraccion->inmoviliza === 1){
            $validator = Validator::make($request->all(), [
                'comparendo_tipoInmovilizacion' => 'required|integer|exists:comparendo_inmovilizacion_tipo,id',
                'comparendo_patioNombre' => 'required|string',
                'comparendo_patioDireccion' => 'required|string',
                'comparendo_gruaNumero' => 'required|string',
                'comparendo_gruaPlaca' => 'required|string',
                'comparendo_inmovilizacionConsecutivo' => 'required|string',
                'comparendo_observacionInmovilizacion' => 'required|string',                    
            ], [
                'comparendo_tipoInmovilizacion.required' => 'No se ha especificado el tipo de inmovilización.',
                'comparendo_tipoInmovilizacion.integer' => 'El valor especificado para el tipo de inmovilización no tiene un formato válido.',
                'comparendo_tipoInmovilizacion.exists' => 'El tipo de inmovilización especificado no existe en el sistema.',
                'comparendo_patioNombre.required' => 'No se especificó el nombre del patio.',
                'comparendo_patioNombre.string' => 'El nombre del patio especificado no tiene un formato válido.',
                'comparendo_patioDireccion.string' => 'La dirección del patio especificada no tiene un formato válido.',
                'comparendo_gruaNumero.string' => 'El número de grúa especificado no tiene un formato válido.',
                'comparendo_gruaPlaca.string' => 'La placa de la grúa especificado no tiene un formato válido.',
                'comparendo_inmovilizacionConsecutivo.string' => 'El consecutivo de inmovilización especificado no tiene un formato válido.',
                'comparendo_observacionInmovilizacion.required' => 'No se ha especificado la observación de la inmovilización.',
                'comparendo_observacionInmovilizacion.string' => 'El valor especificaco para la observación del ainmovilización no tiene un formato válido.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El tipo de infracción requiere que se suministre todos los parámetros de inmovilización.'])->render();
            }
        }

        $servicio = vehiculo_servicio::find($request->vehiculo_servicio);
        if($servicio->name === 'PUBLICO'){
            $validator = Validator::make($request->all(), [
                'vehiculo_razon_social' => 'required|integer|exists:empresa_transporte,id',
                'vehiculo_radio_operacion' => 'required|integer|exists:vehiculo_radio_operacion,id',
                'vehiculo_nivelServicio' => 'nullable|integer|exists:vehiculo_nivel_servicio,id',
            ], [
                'vehiculo_razon_social.required' => 'No se ha especificado la empresa a la cual está afiliado el vehículo.',
                'vehiculo_razon_social.integer' => 'El valor especificado para la empresa a la cual está afiliado el vehículo no tiene un formato válido.',
                'vehiculo_razon_social.exists' => 'La empresa a la cual está afiliado el vehículo especificada no existe en el sistema.',
                'vehiculo_radio_operacion.required' => 'No se ha especificado el radio de operación del vehículo.',
                'vehiculo_radio_operacion.integer' => 'El valor especificado para el radio de operación del vehículo no tiene un formato válido.',
                'vehiculo_radio_operacion.exists' => 'El radio de operación del vehículo especificado no existe en el sistema.',
                'vehiculo_nivelServicio.integer' => 'El nivel del servicio del vehículo no tiene un formato válido.',
                'vehiculo_nivelServicio.exists' => 'El nivel del servicio del vehiculo especificado no existe en el sistema.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El servicio del vehículo requiere que se suministre información comercial del vehículo.'])->render();
            }
        }

        $tipoInfractor = comparendo_infractor_tipo::find($request->conductor_tipo);
        if($tipoInfractor->name != 'PEATON'){
            $validator = Validator::make($request->all(), [
                'vehiculo_licencia_numero' => 'required|numeric',
                'vehiculo_servicio' => 'required|integer|exists:vehiculo_servicio,id',
                'vehiculo_clase' => 'required|integer|exists:vehiculo_clase,id',            
                'vehiculo_tarjeta_operacion' => 'nullable|numeric',
            ], [
                'vehiculo_placa.required' => 'No se ha especificado la placa del vehículo.',
                'vehiculo_placa.string' => 'El valor especificado para la placa del vehículo no tiene un formato válido.',
                'vehiculo_placa.max' => 'La placa debe tener un valor máximo de :max caracteres.',
                'vehiculo_placa.min' => 'La placa debe tener un valor mínimo de :min caracteres.',
                'vehiculo_licencia_numero.required' => 'No se ha especificado el número de la licencia de tránsito del vehículo.',
                'vehiculo_licencia_numero.numeric' => 'El valor especificado para el número de la licencia de tránsito del vehículo no tiene un formato válido.',
                'vehiculo_servicio.required' => 'No se ha especificado el servicio del vehículo.',
                'vehiculo_servicio.integer' => 'El valor especificado para el servicio del vehículo no tiene un formato válido.',
                'vehiculo_servicio.exists' => 'El servicio del vehículo especificado no existe en el sistema.',
                'vehiculo_clase.required' => 'No se ha especificado la clase del vehículo.',
                'vehiculo_clase.integer' => 'El valor especificado para la clase del vehículo no tiene un formato válido.',
                'vehiculo_clase.exists' => 'La clase del vehículo especificada no existe en el sistema.',            
                'vehiculo_tarjeta_operacion.numeric' => 'El valor especificado para la tarjeta de operación del vehículo no tiene un formato válido.',            
                'vehiculo_licenciaTransitoOtto.string' => 'El organismo de tránsito de la licencia de tránsito especificado no tiene un formato válido.',            
                'vehiculo_propTipoDocumento.integer' => 'El tipo de documento del propietario no tiene un formato válido.',
                'vehiculo_propTipoDocumento.exists' => 'El tipo de documento del propietario especificado no existe en el sistema.',
                'vehiculo_propNumeroDocumento.numeric' => 'El número de documento del propietario no tiene un formato válido.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
            } 
        }    

        try{
            $comparendoRadicadoPQR = gd_pqr::whereHas('hasClase', function($query){
                $query->where('name', 'COMPARENDOS');
            })->where('numero_oficio', $request->comparendo_numero)->where('documento_radicado', '!=', null)->first();
            
            if($comparendoRadicadoPQR  == null){
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El comparendo especificado no ha sido radicado en PQR.'])->render();
            }

            $fecha_comparendo = $request->comparendo_fecha_submit.' '.$request->comparendo_hora_submit;
            $fecha_comparendo = Carbon::createFromFormat('Y-m-d H:i', $fecha_comparendo)->toDateTimeString();
            $agente = user_agente::where('user_id', $request->agente)->where('estado', 1)->first();            
            $success = false;
            $ruta_comparendo = null;  
            $valorComparendo = $comparendoInfraccion->smdlv * (\anlutro\LaravelSettings\Facade::get('salario_minimo') / 30);
            $comparendo_inmovilizacion = null;
            $comparendo_vehiculo = null;
            $comparendo_infractor = null;
            $comparendoTestigo = null;

            if($request->comparendo_fuga === true){
                $valorComparendo = $valorComparendo * 2;
            }

            if($request->alcoholemia_grado != null){
                $comparendoAlcohomemia = comparendo_alcoholemia::where('grado', $request->alcoholemia_grado)->first();
                $valorComparendo = $comparendoAlcohomemia->valor;
            }

            \DB::beginTransaction();          

            if($comparendoInfraccion->inmoviliza !== 1){
                $request->comparendo_tipoInmovilizacion = null;
                $request->comparendo_patioNombre = null;
                $request->comparendo_patioDireccion = null;
                $request->comparendo_gruaNumero = null;
                $request->comparendo_gruaPlaca = null;
                $request->comparendo_inmovilizacionConsecutivo = null;
                $request->comparendo_observacionInmovilizacion = null;
            }

            $comparendo = comparendo::create([
                'numero' => $request->comparendo_numero,
                'valor' => $valorComparendo,
                'fecha_realizacion' => $fecha_comparendo,
                'comparendo_infraccion_id' => $request->comparendo_infraccion,
                'comparendo_tipo_id' => $request->comparendo_tipo,
                'observacion_agente' => strtoupper($request->comparendo_observacion),
                'agente_id' => $agente->id,
                'documento' => $ruta_comparendo,
                'barrio_vereda' => $request->comparendo_barrio,
                'direccion' => $request->comparendo_direccion,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $name = explode('/', $comparendoRadicadoPQR->documento_radicado);
            \Storage::copy('pqr/'.$comparendoRadicadoPQR->documento_radicado, 'comparendos/'.array_last($name));

            if($servicio->name === 'PUBLICO'){
                $comparendo_inmovilizacion = comparendo_inmovilizacion::create([
                    'comparendo_id' => $comparendo->id,
                    'inmovilizacion_tipo_id' => $request->comparendo_tipoInmovilizacion,
                    'observacion' => $request->comparendo_observacionInmovilizacion,
                    'patio_nombre' => $request->comparendo_patioNombre,
                    'patio_direccion' => $request->comparendo_patioDireccion,
                    'grua_numero' => $request->comparendo_gruaNumero,
                    'grua_placa' => $request->comparendo_gruaPlaca,
                    'consecutivo' => $request->comparendo_inmovilizacionConsecutivo,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $valorComparendo = $valorComparendo * 2;
            }else{
                $request->vehiculo_razon_social = null;
                $request->vehiculo_radio_operacion = null;
                $request->vehiculo_nivelServicio = null;
            }

            if($tipoInfractor->name != 'PEATON'){
                $comparendo_vehiculo = comparendo_vehiculo::create([
                    'placa' => strtoupper($request->vehiculo_placa),
                    'licencia_transito' => $request->vehiculo_licencia_numero,
                    'propietario_nombre' => strtoupper($request->propietario_nombre),
                    'vehiculo_servicio_id' => $request->vehiculo_servicio,
                    'vehiculo_clase_id' => $request->vehiculo_clase,
                    'tarjeta_operacion' => $request->vehiculo_tarjeta_operacion,
                    'vehiculo_radio_operacion_id' => $request->vehiculo_radio_operacion,
                    'empresa_transportadora_id' => $request->vehiculo_razon_social,
                    'comparendo_id' => $comparendo->id,
                    'licencia_transito_otto' => $request->vehiculo_licenciaTransitoOtto,
                    'vehiculo_nivel_servicio_id' => $request->vehiculo_nivelServicio,
                    'prop_tipo_documento_id' => $request->vehiculo_propTipoDocumento,
                    'prop_numero_documento' => $request->vehiculo_propNumeroDocumento, 
                    'created_at' => date('Y-m-d H:i:s')
                ]);  
            }                

            if($request->conductor_nombre != null){
                $comparendo_infractor = comparendo_infractor::create([
                    'nombre' => strtoupper($request->conductor_nombre),
                    'telefono' => $request->conductor_telefono,
                    'direccion' => strtoupper($request->conductor_direccion),
                    'licencia_numero' => $request->conductor_licencia,
                    'licencia_fecha_vencimiento' => $request->conductor_licencia_vencimiento_submit,
                    'tipo_documento_id' => $request->conductor_documento,
                    'comparendo_id' => $comparendo->id,
                    'numero_documento' => $request->conductor_numero_documento,
                    'licencia_categoria_id' => $request->conductor_licenciaCategoria,
                    'ciudad_id' => $request->conductor_ciudad,
                    'direccion_electronica' => $request->conductor_direccionElectronica,
                    'infractor_tipo_id' => $request->conductor_tipo,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            if($request->testigo_numeroDocumento != null){
                $comparendoTestigo = comparendo_testigo::create([
                    'nombre' => $request->testigo_nombre,
                    'numero_documento' => $request->testigo_numeroDocumento,
                    'direccion' => $request->testigo_direccion,
                    'telefono' => $request->testigo_telefono,
                    'tipo_documento_id' => $request->testigo_tipoDocumento,
                    'comparendo_id' => $comparendo->id
                ]);
            }

            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
            if($ruta_comparendo != null){
                \Storage::delete($ruta_comparendo);
            }
        }        

        if($success == true){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'El comparendo ha sido registrado satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            $request->flash();
            return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['No se ha podido registrar el comparendo.'])->render();
        }
    }

    public function realizarPago($comparendo_id)
    {
        return view('admin.inspeccion.comparendos.registroPago', ['comparendo_id'=>$comparendo_id])->render();
    }

    public function registrarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comparendo_id' => 'required|integer|exists:comparendo,id',
            'fecha_pago_submit' => 'required|date',
            'valor' => 'required|numeric',
            'descuento_valor' => 'nullable|numeric',
            'intereses' => 'nullable|numeric',
            'interes_descuento' => 'nullable|numeric',
            'cobro_adicional' => 'nullable|numeric',
            'numero_factura' => 'required|numeric',
            'numero_consignacion' => 'required|numeric',
            'consignacion' => 'required|mimetypes:application/pdf|mimes:pdf'
        ], [
            'comparendo_id.required' => 'No se ha especificado el comparendo a pagar.',
            'comparendo_id.integer' => 'El ID del comparendo especificado no tiene un formato válido.',
            'comparendo_id.exists' => 'El comparendo especificado a pagar no existe en el sistema.',
            'consignacion.required' => 'No se ha suministrado la consignación del pago.',
            'consignacion.mimetypes' => 'El documento de la consignación suministrada no tiene un formato válido.',
            'consignacion.mimes' => 'El documento de la consignación suministrada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $comparendo = comparendo::find($request->comparendo_id);
            if($comparendo->hasAcuerdoPago->count() > 0){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo está en proceso de acuerdo de pago, lo cual no permite registrar su pago.'],
                    'encabezado' => 'Restricción:',
                ], 200);
            }

            if($comparendo->hasMandamientoPago != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo está en proceso de mandamiento de pago, lo cual no permite registrar su pago.'],
                    'encabezado' => 'Restricción:',
                ], 200);
            }

            if($comparendo->hasPago != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo ya tiene un pago registrado.'],
                    'encabezado' => 'Restricción:',
                ], 200);
            }
            $success = false;
            try{
                \DB::beginTransaction();
                cm_pago::create([
                    'fecha_pago' => $request->fecha_pago_submit,
                    'valor_intereses' => $request->intereses,
                    'descuento_intereses' => $request->intereses_descuento * 100,
                    'numero_factura' => $request->numero_factura,
                    'numero_consignacion' => $request->numero_consignacion,
                    'valor' => $request->valor,
                    'descuento_valor' => $request->descuento_valor * 100,
                    'cobro_adicional' => $request->cobro_adicional,
                    'proceso_id' => $request->comparendo_id,
                    'proceso_type' => 'App\\comparendo',
                    'consignacion' => \Storage::disk('comparendos')->putFile($request->comparendo_id, $request->file('consignacion'))
                ]);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if($success == true){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El pago ha sido registrado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido registrar el pago.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function verPago($comparendo_id)
    {
        $comparendo = comparendo::find($comparendo_id);
        return view('admin.inspeccion.comparendos.verPago', ['pago'=>$comparendo->hasPago])->render();
    }

    public function editar($id)
    {
        $comparendo = comparendo::find($id);
        $infracciones = comparendo_infraccion::where('comparendo_tipo_id', $comparendo->comparendo_tipo_id)->pluck('name', 'id');
        $ciudades = ciudad::where('departamento_id', $comparendo->hasVehiculo->placa_dpto_expedicion_id)->pluck('name', 'id');
        return view('admin.inspeccion.comparendos.editar', array_merge($this->obtenerComplementos(), ['comparendo'=>$comparendo,'infracciones'=>$infracciones,'ciudades'=>$ciudades]))->render();
    }

    public function actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comparendo_id' => 'required|integer|exists:comparendo,id',
            'comparendo_numero' => ['numeric','required',Rule::unique('comparendo','numero')->ignore($request->comparendo_id)],
            'comparendo_fecha_submit' => 'required|date',
            'comparendo_hora_submit' => 'required|date_format:H:i',
            'comparendo_tipo' => 'required|integer|exists:comparendo_tipo,id',
            'comparendo_infraccion' => 'required|integer|exists:comparendo_infraccion,id',
            'comparendo_valor' => 'nullable|string',            
            'comparendo_observacion' => 'nullable|string',
            'comparendo_barrio' => 'required|string',
            'comparendo_direccion' => 'required|string',         
            'propietario_nombre' => 'required|string',
            'conductor_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'conductor_numero_documento' => 'required|numeric',
            'conductor_nombre' => 'required|string',
            'conductor_direccion' => 'nullable|string',
            'conductor_telefono' => 'nullable|numeric',
            'conductor_licencia' => 'nullable|numeric',
            'conductor_licencia_vencimiento_submit' => 'nullable|date',
            'agente' => 'required|integer|exists:user_agente,user_id',
            'conductor_numero_documento' => 'nullable|numeric',
            'conductor_licenciaCategoria' => 'nullable|integer|exists:licencia_categoria,id',
            'conductor_ciudad' => 'nullable|integer|exists:municipio,id',
            'conductor_direccionElectronica' => 'nullable|string',
            'conductor_tipo' => 'nullable|integer|exists:comparendo_infractor_tipo,id',
            'comparendo_fuga' => 'nullable|boolean',
            'alcoholemia_grado' => 'nullable|numeric|exists:',
            'alcoholemia_negacion' => 'nullable|boolean'
        ], [
            'comparendo_id.required' => 'No se ha especificado el comparendo a actualizar.',
            'comparendo_id.integer' => 'El ID del comparendo especificado no es válido.',
            'comparendo_id.exists' => 'El comparendo especificado no existe.',
            'comparendo_numero.required' => 'No se ha especificado el número del comparendo.',
            'comparendo_numero.string' => 'El número de comparendo especificado no tiene un formato válido.',
            'comparendo_numero.unique' => 'El número de comparendo especificado ya está registrado en el sistema.',
            'comparendo_fecha_submit.required' => 'No se ha especificado la fecha en el que se realizó el comparendo.',
            'comparendo_fecha_submit.date' => 'La fecha de realización del comparendo no tiene un formato válido.',
            'comparendo_hora_submit.required' => 'No se ha especificado la hora en la que se realizó el comparendo.',
            'comparendo_hora_submit.date_format' => 'La hora de realización del comparendo no tiene un formato válido.',
            'comparendo_tipo.required' => 'No se ha especificado el tipo de comparendo.',
            'comparendo_tipo.integer' => 'El valor especificado para el tipo de comparendo no tiene un formato válido.',
            'comparendo_tipo.exists' => 'El tipo de comparendo especificado no existe en el sistema.',
            'comparendo_infraccion.required' => 'No se ha especificado la infracción del comparendo.',
            'comparendo_infraccion.integer' => 'El valor especificado para la infracción del comparendo no tiene un formato válido.',
            'comparendo_infraccion.exists' => 'La infracción del comparendo especificado no existe en el sistema.',
            'comparendo_valor.string' => 'El valor del comparendo especificado no tiene un formato válido.',            
            'comparendo_observacion.string' => 'El valor especificado para la observación del comparendo no tiene un formato válido.',
            'comparendo_barrio.required' => 'No se ha especificado el barrio o verada en donde ocurrió el comparendo.',
            'comparendo_barrion.string' => 'El barrio o vereda del comparendo especificado no tiene un formato válido.',
            'comparendo_direccion.required' => 'No se ha especificado la dirección del comparendo',
            'comparendo_direccion.string' => 'La dirección del comparendo especificado no tiene un formato válido.',
            'propietario_nombre.required' => 'No se ha especificado el nombre del propietario del vehículo.',
            'propietario_nombre.string' => 'El valor especificado para el nombre del propietario del vehículo no tiene un formato válido.',
            'conductor_documento.required' => 'No se ha especificado el tipo de documento del infractor.',
            'conductor_documento.integer' => 'El valor especificado para el tipo del documento del infractor no tiene un formato válido.',
            'conductor_documento.exists' => 'El tipo del documento del infractor especificado no existe en el sistema.',
            'conductor_numero_documento.required' => 'No se ha especificado el número de documento del infractor.',
            'conductor_numero_documento.numeric' => 'El valor especificado para el número de documento del infractor no tiene un formato válido.',
            'conductor_nombre.required' => 'No se ha especificado el nombre del infractor.',
            'conductor_nombre.string' => 'El valor especificado para el nombre del infractor no tiene un formato válido.',
            'conductor_direccion.string' => 'El valor especificado para la dirección del infractor no tiene un formato válido.',
            'conductor_telefono.numeric' => 'El valor especificado para el número telefónico del infractor no tiene un formato válido.',
            'conductor_licencia.numeric' => 'El valor especificado para el número la licencia de conducción del infractor no tiene un formato válido.',
            'conductor_licencia_vencimiento_submit.date' => 'El valor especificado para la fecha de vencimiento de la licencia de conducción del infractor no tiene un formato válido.',
            'agente.required' => 'No se ha especificado el agente.',
            'agente.integer' => 'El valor especificado para el agente no tiene un formato válido.',
            'agente.exists' => 'El agente especificado no existe en el sistema.',          
            'conductor_numero_documento.string' => 'El número de documento especificado del infractor no tiene un formato válido.',
            'conductor_licenciaCategoria.integer' => 'La categoría de la licencia del infractor especificada no tiene un formato válido.',
            'conductor_licenciaCategoria.exists' => 'La categoría de la licencia del infractor especificado no existe en el sistema.',
            'conductor_ciudad.integer' => 'La ciudad del infractor especificada no tiene un formato válido.',
            'conductor_ciudad.exists' => 'La ciudad del infractor especificada no existe en el sistema.',
            'conductor_direccionElectronica.string' => 'La dirección electrónica del infractor especificada no tiene un formato válido.',
            'conductor_tipo.integer' => 'El tipo de infractor especificado no tiene un formato válido.',
            'conductor_tipo.exists' => 'El tipo de infractor especificado no existe en el sistema.',
            'comparendo_fuga.boolean' => 'El valor especificado para Fuga no es válido.',
            'alcoholemia_grado.numeric' => 'El valor especificado para Grado alcoholemia no es válido.',
            'alcoholemia_grado.exists' => 'El valor especificado para Grado alcoholemia no es válido.',
            'alcoholemia_negacion.boolean' => 'El valor especificado para Negación alcoholemia no es válido.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
        }

        $comparendoInfraccion = comparendo_infraccion::find($request->comparendo_infraccion);
        if($comparendoInfraccion->inmoviliza === 1){
            $validator = Validator::make($request->all(), [
                'comparendo_tipoInmovilizacion' => 'required|integer|exists:comparendo_inmovilizacion_tipo,id',
                'comparendo_patioNombre' => 'required|string',
                'comparendo_patioDireccion' => 'required|string',
                'comparendo_gruaNumero' => 'required|string',
                'comparendo_gruaPlaca' => 'required|string',
                'comparendo_inmovilizacionConsecutivo' => 'required|string',
                'comparendo_observacionInmovilizacion' => 'required|string',                    
            ], [
                'comparendo_tipoInmovilizacion.required' => 'No se ha especificado el tipo de inmovilización.',
                'comparendo_tipoInmovilizacion.integer' => 'El valor especificado para el tipo de inmovilización no tiene un formato válido.',
                'comparendo_tipoInmovilizacion.exists' => 'El tipo de inmovilización especificado no existe en el sistema.',
                'comparendo_patioNombre.required' => 'No se especificó el nombre del patio.',
                'comparendo_patioNombre.string' => 'El nombre del patio especificado no tiene un formato válido.',
                'comparendo_patioDireccion.string' => 'La dirección del patio especificada no tiene un formato válido.',
                'comparendo_gruaNumero.string' => 'El número de grúa especificado no tiene un formato válido.',
                'comparendo_gruaPlaca.string' => 'La placa de la grúa especificado no tiene un formato válido.',
                'comparendo_inmovilizacionConsecutivo.string' => 'El consecutivo de inmovilización especificado no tiene un formato válido.',
                'comparendo_observacionInmovilizacion.required' => 'No se ha especificado la observación de la inmovilización.',
                'comparendo_observacionInmovilizacion.string' => 'El valor especificaco para la observación del ainmovilización no tiene un formato válido.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El tipo de infracción requiere que se suministre todos los parámetros de inmovilización.'])->render();
            }
        }

        $servicio = vehiculo_servicio::find($request->vehiculo_servicio);
        if($servicio->name === 'PUBLICO'){
            $validator = Validator::make($request->all(), [
                'vehiculo_razon_social' => 'required|integer|exists:empresa_transporte,id',
                'vehiculo_radio_operacion' => 'required|integer|exists:vehiculo_radio_operacion,id',
                'vehiculo_nivelServicio' => 'nullable|integer|exists:vehiculo_nivel_servicio,id',
            ], [
                'vehiculo_razon_social.required' => 'No se ha especificado la empresa a la cual está afiliado el vehículo.',
                'vehiculo_razon_social.integer' => 'El valor especificado para la empresa a la cual está afiliado el vehículo no tiene un formato válido.',
                'vehiculo_razon_social.exists' => 'La empresa a la cual está afiliado el vehículo especificada no existe en el sistema.',
                'vehiculo_radio_operacion.required' => 'No se ha especificado el radio de operación del vehículo.',
                'vehiculo_radio_operacion.integer' => 'El valor especificado para el radio de operación del vehículo no tiene un formato válido.',
                'vehiculo_radio_operacion.exists' => 'El radio de operación del vehículo especificado no existe en el sistema.',
                'vehiculo_nivelServicio.integer' => 'El nivel del servicio del vehículo no tiene un formato válido.',
                'vehiculo_nivelServicio.exists' => 'El nivel del servicio del vehiculo especificado no existe en el sistema.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El servicio del vehículo requiere que se suministre información comercial del vehículo.'])->render();
            }
        }

        $tipoInfractor = comparendo_infractor_tipo::find($request->conductor_tipo);
        if($tipoInfractor->name != 'PEATON'){
            $validator = Validator::make($request->all(), [
                'vehiculo_licencia_numero' => 'required|numeric',
                'vehiculo_servicio' => 'required|integer|exists:vehiculo_servicio,id',
                'vehiculo_clase' => 'required|integer|exists:vehiculo_clase,id',            
                'vehiculo_tarjeta_operacion' => 'nullable|numeric',
            ], [
                'vehiculo_placa.required' => 'No se ha especificado la placa del vehículo.',
                'vehiculo_placa.string' => 'El valor especificado para la placa del vehículo no tiene un formato válido.',
                'vehiculo_placa.max' => 'La placa debe tener un valor máximo de :max caracteres.',
                'vehiculo_placa.min' => 'La placa debe tener un valor mínimo de :min caracteres.',
                'vehiculo_licencia_numero.required' => 'No se ha especificado el número de la licencia de tránsito del vehículo.',
                'vehiculo_licencia_numero.numeric' => 'El valor especificado para el número de la licencia de tránsito del vehículo no tiene un formato válido.',
                'vehiculo_servicio.required' => 'No se ha especificado el servicio del vehículo.',
                'vehiculo_servicio.integer' => 'El valor especificado para el servicio del vehículo no tiene un formato válido.',
                'vehiculo_servicio.exists' => 'El servicio del vehículo especificado no existe en el sistema.',
                'vehiculo_clase.required' => 'No se ha especificado la clase del vehículo.',
                'vehiculo_clase.integer' => 'El valor especificado para la clase del vehículo no tiene un formato válido.',
                'vehiculo_clase.exists' => 'La clase del vehículo especificada no existe en el sistema.',            
                'vehiculo_tarjeta_operacion.numeric' => 'El valor especificado para la tarjeta de operación del vehículo no tiene un formato válido.',            
                'vehiculo_licenciaTransitoOtto.string' => 'El organismo de tránsito de la licencia de tránsito especificado no tiene un formato válido.',            
                'vehiculo_propTipoDocumento.integer' => 'El tipo de documento del propietario no tiene un formato válido.',
                'vehiculo_propTipoDocumento.exists' => 'El tipo de documento del propietario especificado no existe en el sistema.',
                'vehiculo_propNumeroDocumento.numeric' => 'El número de documento del propietario no tiene un formato válido.',
            ]);

            if ($validator->fails()) {
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors($validator->errors()->all())->render();
            } 
        }

        try{
            $comparendoRadicadoPQR = gd_pqr::whereHas('hasClase', function($query){
                $query->where('name', 'COMPARENDOS');
            })->where('numero_oficio', $request->comparendo_numero)->where('documento_radicado', '!=', null)->first();
            
            if($comparendoRadicadoPQR  == null){
                $request->flash();
                return view('admin.inspeccion.comparendos.nuevo', $this->obtenerComplementos())->withErrors(['El comparendo especificado no ha sido radicado en PQR.'])->render();
            }

            $fecha_comparendo = $request->comparendo_fecha_submit.' '.$request->comparendo_hora_submit;
            $fecha_comparendo = Carbon::createFromFormat('Y-m-d H:i', $fecha_comparendo)->toDateTimeString();
            $agente = user_agente::where('user_id', $request->agente)->where('estado', 1)->first();            
            $success = false;
            $ruta_comparendo = null;  
            $valorComparendo = $comparendoInfraccion->smdlv * (\anlutro\LaravelSettings\Facade::get('salario_minimo') / 30);

            \DB::beginTransaction(); 

            $comparendo = comparendo::find($request->comparendo_id);

            if($request->comparendo_numero != $comparendo->numero){
                $name = explode('/', $comparendoRadicadoPQR->documento_radicado);
                \Storage::copy('pqr/'.$comparendoRadicadoPQR->documento_radicado, 'comparendos/'.array_last($name));
                $comparendo->documento = array_last($name);
                $ruta_comparendo = array_last($name);
            }

            $comparendo->numero = $request->comparendo_numero;
            $comparendo->valor = $valorComparendo;
            $comparendo->fecha_realizacion = $fecha_comparendo;
            $comparendo->comparendo_infraccion_id = $request->comparendo_infraccion;
            $comparendo->comparendo_tipo_id = $request->comparendo_tipo;
            $comparendo->observacion_agente = strtoupper($request->comparendo_observacion);
            $comparendo->agente_id = $agente->id;                
            $comparendo->barrio_vereda = $request->comparendo_barrio;
            $comparendo->direccion = $request->comparendo_direccion;

            if($comparendoInfraccion->inmoviliza !== 1){
                if($comparendo->hasInmovilizacion != null){
                    $comparendo->hasInmovilizacion->delete();
                }
            }else{
                if($comparendo->hasInmovilizacion != null){
                    $comparendo->hasInmovilizacion->inmovilizacion_tipo_id = $request->comparendo_tipoInmovilizacion;
                    $comparendo->hasInmovilizacion->observacion = $request->comparendo_observacionInmovilizacion;
                    $comparendo->hasInmovilizacion->patio_nombre = $request->comparendo_patioNombre;
                    $comparendo->hasInmovilizacion->patio_direccion = $request->comparendo_patioDireccion;
                    $comparendo->hasInmovilizacion->grua_numero = $request->comparendo_gruaNumero;
                    $comparendo->hasInmovilizacion->grua_placa = $request->comparendo_gruaPlaca;
                    $comparendo->hasInmovilizacion->consecutivo = $request->comparendo_inmovilizacionConsecutivo;
                    $comparendo->hasInmovilizacion->save();        
                }else{
                    $comparendo_inmovilizacion = comparendo_inmovilizacion::create([
                        'comparendo_id' => $comparendo->id,
                        'inmovilizacion_tipo_id' => $request->comparendo_tipoInmovilizacion,
                        'observacion' => $request->comparendo_observacionInmovilizacion,
                        'patio_nombre' => $request->comparendo_patioNombre,
                        'patio_direccion' => $request->comparendo_patioDireccion,
                        'grua_numero' => $request->comparendo_gruaNumero,
                        'grua_placa' => $request->comparendo_gruaPlaca,
                        'consecutivo' => $request->comparendo_inmovilizacionConsecutivo,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }   
                
                if($servicio->name === 'PUBLICO'){ 
                    $valorComparendo = $valorComparendo * 2;
                }else{
                    $request->vehiculo_razon_social = null;
                    $request->vehiculo_radio_operacion = null;
                    $request->vehiculo_nivelServicio = null;
                }
            }

            if($tipoInfractor->name != 'PEATON'){
                if($comparendo->hasVehiculo != null){
                    $comparendo->hasVehiculo->placa = strtoupper($request->vehiculo_placa);
                    $comparendo->hasVehiculo->licencia_transito = $request->vehiculo_licencia_numero;
                    $comparendo->hasVehiculo->propietario_nombre = strtoupper($request->propietario_nombre);
                    $comparendo->hasVehiculo->vehiculo_servicio_id = $request->vehiculo_servicio;
                    $comparendo->hasVehiculo->vehiculo_clase_id = $request->vehiculo_clase;
                    $comparendo->hasVehiculo->tarjeta_operacion = $request->vehiculo_tarjeta_operacion;
                    $comparendo->hasVehiculo->vehiculo_radio_operacion_id = $request->vehiculo_radio_operacion;
                    $comparendo->hasVehiculo->empresa_transportadora_id = $request->vehiculo_razon_social;
                    $comparendo->hasVehiculo->licencia_transito_otto = $request->vehiculo_licenciaTransitoOtto;
                    $comparendo->hasVehiculo->vehiculo_nivel_servicio_id = $request->vehiculo_nivelServicio;
                    $comparendo->hasVehiculo->prop_tipo_documento_id = $request->vehiculo_propTipoDocumento;
                    $comparendo->hasVehiculo->prop_numero_documento = $request->vehiculo_propNumeroDocumento; 
                    $comparendo->hasVehiculo->save();
                }else{
                    $comparendo_vehiculo = comparendo_vehiculo::create([
                        'placa' => strtoupper($request->vehiculo_placa),
                        'licencia_transito' => $request->vehiculo_licencia_numero,
                        'propietario_nombre' => strtoupper($request->propietario_nombre),
                        'vehiculo_servicio_id' => $request->vehiculo_servicio,
                        'vehiculo_clase_id' => $request->vehiculo_clase,
                        'tarjeta_operacion' => $request->vehiculo_tarjeta_operacion,
                        'vehiculo_radio_operacion_id' => $request->vehiculo_radio_operacion,
                        'empresa_transportadora_id' => $request->vehiculo_razon_social,
                        'comparendo_id' => $comparendo->id,
                        'licencia_transito_otto' => $request->vehiculo_licenciaTransitoOtto,
                        'vehiculo_nivel_servicio_id' => $request->vehiculo_nivelServicio,
                        'prop_tipo_documento_id' => $request->vehiculo_propTipoDocumento,
                        'prop_numero_documento' => $request->vehiculo_propNumeroDocumento, 
                        'created_at' => date('Y-m-d H:i:s')
                    ]); 
                }                
            }else{
                if($comparendo->hasVehiculo != null){
                    $comparendo->hasVehiculo->delete();
                } 
            }                

            if($request->conductor_nombre != null){
                if($comparendo->hasInfractor != null){
                    $comparendo->hasInfractor->nombre = strtoupper($request->conductor_nombre);
                    $comparendo->hasInfractor->telefono = $request->conductor_telefono;
                    $comparendo->hasInfractor->direccion = strtoupper($request->conductor_direccion);
                    $comparendo->hasInfractor->licencia_numero = $request->conductor_licencia;
                    $comparendo->hasInfractor->licencia_fecha_vencimiento = $request->conductor_licencia_vencimiento_submit;
                    $comparendo->hasInfractor->tipo_documento_id = $request->conductor_documento;
                    $comparendo->hasInfractor->numero_documento = $request->conductor_numero_documento;
                    $comparendo->hasInfractor->licencia_categoria_id = $request->conductor_licenciaCategoria;
                    $comparendo->hasInfractor->ciudad_id = $request->conductor_ciudad;
                    $comparendo->hasInfractor->direccion_electronica = $request->conductor_direccionElectronica;
                    $comparendo->hasInfractor->infractor_tipo_id = $request->conductor_tipo;
                    $comparendo->hasInfractor->save();
                }else{
                    $comparendo_infractor = comparendo_infractor::create([
                        'nombre' => strtoupper($request->conductor_nombre),
                        'telefono' => $request->conductor_telefono,
                        'direccion' => strtoupper($request->conductor_direccion),
                        'licencia_numero' => $request->conductor_licencia,
                        'licencia_fecha_vencimiento' => $request->conductor_licencia_vencimiento_submit,
                        'tipo_documento_id' => $request->conductor_documento,
                        'comparendo_id' => $comparendo->id,
                        'numero_documento' => $request->conductor_numero_documento,
                        'licencia_categoria_id' => $request->conductor_licenciaCategoria,
                        'ciudad_id' => $request->conductor_ciudad,
                        'direccion_electronica' => $request->conductor_direccionElectronica,
                        'infractor_tipo_id' => $request->conductor_tipo,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }                
            }else{
                if($comparendo->hasInfractor != null){
                    $comparendo->hasInfractor->delete();
                }
            }

            if($request->testigo_numeroDocumento != null){
                if($comparendo->hasTestigo != null){
                    $comparendo->hasTestigo->nombre = $request->testigo_nombre;
                    $comparendo->hasTestigo->numero_documento = $request->testigo_numeroDocumento;
                    $comparendo->hasTestigo->direccion = $request->testigo_direccion;
                    $comparendo->hasTestigo->telefono = $request->testigo_telefono;
                    $comparendo->hasTestigo->tipo_documento_id = $request->testigo_tipoDocumento;
                    $comparendo->hasTestigo->save();
                }else{
                    $comparendoTestigo = comparendo_testigo::create([
                        'nombre' => $request->testigo_nombre,
                        'numero_documento' => $request->testigo_numeroDocumento,
                        'direccion' => $request->testigo_direccion,
                        'telefono' => $request->testigo_telefono,
                        'tipo_documento_id' => $request->testigo_tipoDocumento,
                        'comparendo_id' => $comparendo->id
                    ]);
                }                
            }else{
                if($comparendo->hasTestigo != null){
                    $comparendo->hasTestigo->delete();
                }
            }

            if($request->comparendo_fuga === true){
                $valorComparendo = $valorComparendo * 2;
            }

            if($request->alcoholemia_grado != null){
                $comparendoAlcohomemia = comparendo_alcoholemia::where('grado', $request->alcoholemia_grado)->first();
                $valorComparendo = $comparendoAlcohomemia->valor;
            }

            $comparendo->valor = $valorComparendo;
            $comparendo->save();

            \DB::commit();
            $success = true;
        }catch (\Exception $e){
            \DB::rollBack();
            if($ruta_comparendo != null){
                \Storage::delete($ruta_comparendo);
            }
        }        

        if($success == true){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'El comparendo ha sido actualizado satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }else{
            $request->flash();
            return view('admin.inspeccion.comparendos.editar', $this->obtenerComplementos())->withErrors(['No se ha podido actualizar el comparendo.'])->render();
        }
    }

    public function editarPago($comparendo_id)
    {
        $comparendo = comparendo::find($comparendo_id);
        return view('admin.inspeccion.comparendos.editarPago', ['pago'=>$comparendo->hasPago])->render();
    }

    public function actualizarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cm_pago,id',
            'fecha_pago_submit' => 'required|date',
            'valor' => 'required|numeric',
            'descuento_valor' => 'nullable|numeric',
            'intereses' => 'nullable|numeric',
            'interes_descuento' => 'nullable|numeric',
            'cobro_adicional' => 'nullable|numeric',
            'numero_factura' => 'required|numeric',
            'numero_consignacion' => 'required|numeric',
            'consignacion' => 'mimetypes:application/pdf|mimes:pdf'
        ], [
            'consignacion.mimetypes' => 'El documento de la consignación suministrada no tiene un formato válido.',
            'consignacion.mimes' => 'El documento de la consignación suministrada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $success = false;
            try{
                \DB::beginTransaction();
                $pago = cm_pago::find($request->id);
                $pago->fecha_pago = $request->fecha_pago_submit;
                $pago->valor_intereses = $request->intereses;
                $pago->descuento_intereses = $request->intereses_descuento;
                $pago->numero_factura = $request->numero_factura;
                $pago->numero_consignacion = $request->numero_consignacion;
                $pago->valor = $request->valor;
                $pago->descuento_valor = $request->descuento_valor;
                $pago->cobro_adicional = $request->cobro_adicional;
                if($request->consignacion != null) {
                    $pago->consignacion = \Storage::disk('comparendos')->putFile($pago->proceso_id, $request->file('consignacion'));
                }
                $pago->save();
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if($success == true){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El pago ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el pago.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function obtenerComparendo($id)
    {
        $comparendo = comparendo::find($id);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.$comparendo->numero.'.pdf"',
        ];
        return Response()->download(storage_path('app/comparendos/'.$comparendo->documento), $comparendo->numero.'.pdf', $headers);
    }

    public function obtenerPagoConsignacion($id)
    {
        $pago = cm_pago::find($id);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.$pago->hasProceso->numero.'.pdf"',
        ];
        return Response()->download(storage_path('app/comparendos/'.$pago->consignacion), $pago->hasProceso->numero.'.pdf', $headers);
    }

    public function obtenerListadoTiposComparendos()
    {
        $tiposComparendos = comparendo_tipo::paginate(15);
        return view('admin.inspeccion.comparendos.listadoTiposComparendo', ['tiposComparendos'=>$tiposComparendos])->render();
    }

    public function nuevoTipoComparendo()
    {
        return view('admin.inspeccion.comparendos.nuevoTipoComparendo')->render();
    }

    public function crearTipoComparendo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:comparendo_tipo'
        ], [
            'name.required' => 'No se ha especificado el nombre para el tipo de comparendo.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                comparendo_tipo::create(['name'=>strtoupper($request->name)]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Comparendo ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Tipo Comparendo.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarTipoComparendo($id)
    {
        $tipoComparendo = comparendo_tipo::find($id);
        return view('admin.inspeccion.comparendos.editarTipoComparendo', ['tipoComparendo'=>$tipoComparendo])->render();
    }

    public function actualizarTipoComparendo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:comparendo_tipo',
            'name' => ['required','string',Rule::unique('comparendo_tipo', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo de comparendo a modificar.',
            'id.integer' => 'El ID del tipo de comparendo no tiene un formato válido.',
            'id.exists' => 'El tipo de comparendo a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre para el tipo de comparendo.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $tipoComparendo = comparendo_tipo::find($request->id);
                $tipoComparendo->name = strtoupper($request->name);
                $tipoComparendo->save();
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Comparendo ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Tipo Comparendo.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function obtenerListadoInfracciones()
    {
        $infracciones = comparendo_infraccion::paginate(15);
        return view('admin.inspeccion.comparendos.listadoInfracciones', ['infracciones'=>$infracciones])->render();
    }

    public function nuevaInfraccion()
    {
        $tiposComparendos = comparendo_tipo::pluck('name', 'id');
        return view('admin.inspeccion.comparendos.nuevaInfraccion', ['tiposComparendos'=>$tiposComparendos])->render();
    }

    public function crearInfraccion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:comparendo_infraccion',
            'description' => 'required|string',
            'tipoComparendo' => 'required|integer|exists:comparendo_tipo,id',
            'inmoviliza' => 'required|integer',
            'smdlv' => 'required|integer'
        ], [
            'name.required' => 'No se ha especificado el nombre de la infracción.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'description.required' => 'No se ha proporcionado la descripción de la infracción.',
            'description.string' => 'La descripción de la infracción no tiene un formato válido.',
            'tipoComparendo.required' => 'No se ha especificado el tipo de comparendo.',
            'tipoComparendo.integer' => 'El ID del tipo de comparendo especificado no tiene un formato válido.',
            'tipoComparendo.exists' => 'El tipo de comaprendo especificado no existe en el sistema.',
            'inmoviliza.required' => 'No se ha especificado si la infracción da inmovilización.',
            'inmoviliza.integer' => 'El valor para el campo inmoviliza no tiene un formato válido.',
            'smdlv.required' =>  'No se ha especificado la cantidad de SMDLV.',
            'smdlv.integer' => 'El valor especificado para la cantidad de SMDLV no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                comparendo_infraccion::create([
                    'name'=>strtoupper($request->name),
                    'descripcion'=>strtoupper($request->description),
                    'comparendo_tipo_id'=>$request->tipoComparendo,
                    'inmoviliza' => $request->inmoviliza,
                    'smdlv' => $request->smdlv
                ]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'La infracción ha sido creada satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear la infracción.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarInfraccion($id)
    {
        $tiposComparendos = comparendo_tipo::pluck('name', 'id');
        $infraccion = comparendo_infraccion::find($id);
        return view('admin.inspeccion.comparendos.editarInfraccion', ['infraccion'=>$infraccion, 'tiposComparendos'=>$tiposComparendos])->render();
    }

    public function actualizarInfraccion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:comparendo_infraccion',
            'name' => ['required','string',Rule::unique('comparendo_infraccion', 'name')->ignore($request->id)],
            'description' => 'required|string',
            'tipoComparendo' => 'required|integer|exists:comparendo_tipo,id',
            'inmoviliza' => 'required|integer',
            'smdlv' => 'required|integer'
        ], [
            'id.required' => 'No se ha especificado la infracción a modificar.',
            'id.integer' => 'El ID de la infración espeicificada no tiene un formato válido.',
            'id.exists' => 'La infracción a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre de la infracción.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'description.required' => 'No se ha proporcionado la descripción de la infracción.',
            'description.string' => 'La descripción de la infracción no tiene un formato válido.',
            'tipoComparendo.required' => 'No se ha especificado el tipo de comparendo.',
            'tipoComparendo.integer' => 'El ID del tipo de comparendo especificado no tiene un formato válido.',
            'tipoComparendo.exists' => 'El tipo de comaprendo especificado no existe en el sistema.',
            'inmoviliza.required' => 'No se ha especificado si la infracción da inmovilización.',
            'inmoviliza.integer' => 'El valor para el campo inmoviliza no tiene un formato válido.',
            'smdlv.required' =>  'No se ha especificado la cantidad de SMDLV.',
            'smdlv.integer' => 'El valor especificado para la cantidad de SMDLV no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $infraccion = comparendo_infraccion::find($request->id);
                $infraccion->name = strtoupper($request->name);
                $infraccion->descripcion = strtoupper($request->description);
                $infraccion->comparendo_tipo_id = $request->tipoComparendo;
                $infraccion->inmoviliza = $request->inmoviliza;
                $infraccion->smdlv = $request->smdlv;
                $infraccion->save();
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'La infracción ha sido actualizada satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar la infracción.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function obtenerListadoTiposInmovilizaciones()
    {
        $tiposInmovilizaciones = comparendo_inmovilizacion_tipo::paginate(15);
        return view('admin.inspeccion.comparendos.listadoTiposInmovilizaciones', ['tiposInmovilizaciones'=>$tiposInmovilizaciones])->render();
    }

    public function nuevoTipoInmovilizacion()
    {
        return view('admin.inspeccion.comparendos.nuevoTipoInmovilizacion')->render();
    }

    public function crearTipoInmovilizacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:comparendo_inmovilizacion_tipo'
        ], [
            'name.required' => 'No se ha especificado el nombre del tipo de inmovilización.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                comparendo_inmovilizacion_tipo::create(['name'=>strtoupper($request->name)]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Comparendo ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Tipo Comparendo.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarTipoInmovilizacion($id)
    {
        $tipoInmovilizacion = comparendo_inmovilizacion_tipo::find($id);
        return view('admin.inspeccion.comparendos.editarTipoInmovilizacion', ['tipoInmovilizacion'=>$tipoInmovilizacion])->render();
    }

    public function actualizarTipoInmovilizacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:comparendo_inmovilizacion_tipo',
            'name' => ['required','string',Rule::unique('comparendo_inmovilizacion_tipo', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo de inmovilización a modificar.',
            'id.integer' => 'El ID del tipo de inmovilización especificado no tiene un formato válido.',
            'id.exists' => 'El tipo de inmovilización a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre del tipo de inmovilización.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $tipoInmovilizacion = comparendo_inmovilizacion_tipo::find($request->id);
                $tipoInmovilizacion->name = strtoupper($request->name);
                $tipoInmovilizacion->save();
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Inmovilización ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Tipo Inmovilización.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function sancionarF1()
    {
        $tiposComparendos = array_add(comparendo_tipo::pluck('name','id'), 3, 'MIXTO');
        return view('admin.inspeccion.comparendos.sancionar', ['tiposComparendos'=>$tiposComparendos])->render();
    }

    public function sancionarF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $plantilla = null;
        $salario = null;
        $cuantia = null;
        $cantidad = null;
        $numero = null;
        $vigencia = \anlutro\LaravelSettings\Facade::get('vigencia');

        if($request->has('mayor')){
            $plantilla = '2019_PRIMERA_MAYOR20.docx';
            $salario = 828116 * $request->vmayor;
            $cuantia = 'mayor';
            $cantidad = $request->vmayor;
        }else{
            $plantilla = '2019_UNICA_MENOR20.docx';
            $salario = 828116 * $request->vmenor;
            $cuantia = 'menor';
            $cantidad = $request->vmenor;
        }

        try{
            $ultimaSancion = sancion::orderBy('created_at', 'desc')->get()->first();
            if($ultimaSancion != null){
                $numero = $ultimaSancion->numero;
                $numero = ltrim($numero, "0");
                ++$numero;
                $numero = sprintf("%'.03d\n", $numero);
            }else{
                $numero = sistema_parametros_gd::whereHas('hasVigencia', function($query) use ($vigencia){
                    $query->where('vigencia', $vigencia);
                })->first()->consecutivo_sancion;
            }

            if($numero == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha programado los rangos para sanciones.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            if($request->tipoComparendo == 3){
                $comparendos = comparendo::doesntHave('hasSancion')->has('hasInfractor')->whereDate('fecha_realizacion', '>=', $request->fecha_inicio_submit)->whereDate('fecha_realizacion', '<=', $request->fecha_fin_submit)->where('valor', '!=', null)->where('valor', '>', $salario)->doesntHave('hasSancion')->whereHas('hasInfraccion', function($query){
                    $query->where('name', '!=', 'F');
                })->get();
            }else{
                $comparendos = comparendo::doesntHave('hasSancion')->has('hasInfractor')->whereDate('fecha_realizacion', '>=', $request->fecha_inicio_submit)->whereDate('fecha_realizacion', '<=', $request->fecha_fin_submit)->where('valor', '!=', null)->where('valor', '<', $salario)->doesntHave('hasSancion')->where('comparendo_tipo_id', $request->tipoComparendo)->whereHas('hasInfraccion', function($query){
                    $query->where('name', '!=', 'F');
                })->get();
            }  

            \Jenssegers\Date\Date::setLocale('es');
            $fechaSancion = \Jenssegers\Date\Date::createFromFormat('Y/m/d', $request->fecha_sancion_submit);
            $anioSancion = $fechaSancion->format('Y');

            if($request->fecha_resolucion_letras === true){
                $fechaSancion = strtoupper($fechaSancion->format('F j')). ' DE '.$fechaSancion->format('Y');          
            }
            
            foreach ($comparendos as $comparendo){                
                $fechaComparendo = \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $comparendo->fecha_realizacion)->format('Y-m-d');
                
                $sancion = sancion::create([
                    'fecha_sancion' => $request->fecha_sancion_submit,
                    'documento' => null,
                    'cantidad_salarios' => $cantidad,
                    'cuantia_salarios' => $cuantia,
                    'proceso_id' => $comparendo->id,
                    'proceso_type' => 'App\\comparendo',
                    'numero_proceso' => $comparendo->numero,
                    'numero' => $numero
                ]);

                $templateProcessor = new TemplateProcessor(storage_path('app/plantillas/'.$plantilla));
                $templateProcessor->setValue(array('NUMERO_SANCION', 'FECHA_SANCION', 'AÑO_SANCION', 'NOMBRE_INFRACTOR', 'TIPO_DOCUMENTO_INFRACTOR', 'NUMERO_DOCUMENTO_INFRACTOR', 'NUMERO_COMPARENDO', 'FECHA_COMPARENDO', 'CLASE_VEHICULO', 'PLACA_VEHICULO', 'PLACA_AGENTE', 'NOMBRE_AGENTE', 'NOMBRE_INFRACCION', 'DESCRIPCION_INFRACCION', 'SALARIOS_SANCION'), 
                                             array($sancion->numero, $fechaSancion, $anioSancion, $comparendo->hasInfractor->nombre, $comparendo->hasInfractor->hasTipoDocumento->name, $comparendo->hasInfractor->numero_documento, $comparendo->numero, $fechaComparendo, $comparendo->hasVehiculo->hasVehiculoClase->name, $comparendo->hasVehiculo->placa, $comparendo->hasAgente->placa, $comparendo->hasAgente->hasUsuario->name, $comparendo->hasInfraccion->name, $comparendo->hasInfraccion->descripcion, $sancion->salarios)
                                            );
                $templateProcessor->saveAs(storage_path('app/temp/').'sancion.docx');   
                
                $sancion->documento = \Storage::move('temp/sancion.docx', 'sanciones/'.$sancion->numero.' '.$fechaSancion.'.docx');
                $sancion->save();

                $numero = ltrim($numero, "0");
                ++$numero;
                $numero = sprintf("%'.03d\n", $numero);
            }
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha ocurrido un error al generar las sanciones.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
        }        
    }

    public function tipoInfractor_nuevo()
    {
        return view('admin.inspeccion.comparendos.nuevoTipoInfractor')->render();
    }

    public function tipoInfractor_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:comparendo_infractor_tipo,name'
        ], [
            'nombre.required' => 'No se ha especificado el nombre del tipo infractor.',
            'nombre.string' => 'El nombre del tipo infractor especificado no tiene un formato válido.',
            'nombre.unique' => 'El nombre del tipo infractor especificado ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            comparendo_infractor_tipo::create([
                'name' => $request->nombre
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el tipo infractor correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el tipo infractor.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function tipoInfractor_editar($id)
    {
        $tipoInfractor = comparendo_infractor_tipo::find($id);
        return view('admin.inspeccion.comparendos.editarTipoInfractor',['tipoInfractor'=>$tipoInfractor])->render();
    }

    public function tipoInfractor_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|exists:comparendo_infractor_tipo,id',
            'nombre' => ['required','string', Rule::unique('comparendo_infractor_tipo', 'name')->ignore($request->id)]
        ], [
            'nombre.required' => 'No se ha especificado el nombre del tipo infractor.',
            'nombre.string' => 'El nombre del tipo infractor especificado no tiene un formato válido.',
            'nombre.unique' => 'El nombre del tipo infractor especificado ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $tipoInfractor = comparendo_infractor_tipo::find($request->id);
            $tipoInfractor->name = $request->nombre;
            $tipoInfractor->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el tipo infractor correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el tipo infractor.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function tipoInfractor_obtenerTodos()
    {
        $tiposInfractores = comparendo_infractor_tipo::all();
        return view('admin.inspeccion.comparendos.listadoTiposInfractores', ['tiposInfractores'=>$tiposInfractores])->render();
    }

    public function verInmovilizacion($id)
    {
        $inmovilizacion = comparendo_inmovilizacion::where('comparendo_id', $id)->first();
        return view('admin.inspeccion.comparendos.verInmovilizacion', ['inmovilizacion'=>$inmovilizacion])->render();
    }

    public function verUbicacion($id)
    {
        $comparendo = comparendo::find($id);
        return view('admin.inspeccion.comparendos.verUbicacion', ['comparendo'=>$comparendo])->render();
    }

    public function verTestigo($id)
    {
        $testigo = comparendo_testigo::where('comparendo_id', $id)->first();
        return view('admin.inspeccion.comparendos.verTestigo', ['testigo'=>$testigo])->render();
    }

    public function licenciaCategoria_obtenerListado()
    {
        $licenciaCategorias = licencia_categoria::all();
        return view('admin.inspeccion.comparendos.listadoLicenciaCategorias', ['licenciaCategorias'=>$licenciaCategorias])->render();
    }

    public function licenciaCategoria_nuevo()
    {
        return view('admin.inspeccion.comparendos.nuevaLicenciaCategoria')->render();
    }

    public function licenciaCategoria_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:licencia_categoria,name'
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la categoría de la licencia.',
            'nombre.string' => 'El nombre de la categoría de la licencia especificado no tiene un formato válido.',
            'nombre.unique' => 'El nombre de la categoría de la licencia especificado ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            licencia_categoria::create([
                'name' => $request->nombre
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la categoría de la licencia.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear la categoría de la licencia.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function licenciaCategoria_editar($id)
    {
        $categoria = licencia_categoria::find($id);
        return view('admin.inspeccion.comparendos.editarLicenciaCategoria', ['categoria'=>$categoria])->render();
    }

    public function licenciaCategoria_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|exists:licencia_categoria,id',
            'nombre' => ['required','string', Rule::unique('licencia_categoria', 'name')->ignore($request->id)]
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la categoría de la licencia.',
            'nombre.string' => 'El nombre de la categoría de la licencia no tiene un formato válido.',
            'nombre.unique' => 'El nombre de la categoría de la licencia ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $categoria = licencia_categoria::find($request->id);
            $categoria->name = $request->nombre;
            $categoria->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la categoría de la licencia.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la categoría de la licencia.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function entidad_obtenerListado()
    {
        $entidades = comparendo_entidad::all();
        return view('admin.inspeccion.comparendos.listadoEntidades', ['entidades'=>$entidades])->render();
    }

    public function entidad_nuevo()
    {
        return view('admin.inspeccion.comparendos.nuevaEntidad')->render();
    }

    public function entidad_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:comparendo_entidad,name'
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la entidad.',
            'nombre.string' => 'El nombre de la entidad especificada no tiene un formato válido.',
            'nombre.unique' => 'El nombre de la entidad especificada ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            comparendo_entidad::create([
                'name' => $request->nombre
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la entidad.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear la entidad.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function entidad_editar($id)
    {
        $entidad = comparendo_entidad::find($id);
        return view('admin.inspeccion.comparendos.editarEntidad', ['entidad'=>$entidad])->render();
    }

    public function entidad_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|exists:comparendo_entidad,id',
            'nombre' => ['required','string', Rule::unique('comparendo_entidad', 'name')->ignore($request->id)]
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la entidad.',
            'nombre.string' => 'El nombre de la categoría de la entidad no tiene un formato válido.',
            'nombre.unique' => 'El nombre de la categoría de la entidad ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $entidad = comparendo_entidad::find($request->id);
            $entidad->name = $request->nombre;
            $entidad->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la entidad.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la entidad.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function filtrarComparendos($valor, $filtro)
    {
        $comparendos = [];
        switch ($filtro){
            case 1: $comparendos = comparendo::whereHas('hasVehiculo', function($query) use ($valor){
                $query->where('prop_numero_documento', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 2: $comparendos = comparendo::whereHas('hasInfractor', function($query) use ($valor){
                $query->where('numero_documento', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 3: $comparendos = comparendo::where('numero', $valor)->orderBy('created_at','desc')->paginate(50);
                break;
            case 4: $comparendos = comparendo::whereHas('hasVehiculo', function($query) use ($valor){
                $query->where('placa', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
        }

        return view('admin.inspeccion.comparendos.listado', ['comparendos'=>$comparendos])->render();

    }
}
