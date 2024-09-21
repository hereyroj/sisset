<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sancion;
use Validator;
use App\acuerdo_pago;
use App\comparendo;

class SancionController extends Controller
{
    public function index()
    {
        return view('admin.inspeccion.sanciones.administrar');
    }

    public function nueva()
    {
        return view('admin.inspeccion.sanciones.nueva')->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_sancion' => 'required|date',
            'cantidad_salarios' => 'required|numeric',
            'cuantia_salarios' => 'required|numeric',
            'numero_proceso' => 'required|numeric',
            'tipo_proceso' => 'nullable|string'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $procesos = null;
        $tipoProceso = null;
        $nombreProceso = null;
        $plantilla = null;
        $salario = null;
        $cuantia = null;
        $cantidad = null;
        $numero = null;        

        try{
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

            $ultimaSancion = sancion::orderBy('created_at', 'desc')->first();
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

            if($request->tipo_proceso == 1){
                $procesos = acuerdo_pago::where('numero', $request->numero_proceso)->get();
                $tipoProceso = 'Acuerdo de Pago';
                $nombreProceso = 'App\\acuerdo_pago';
            }else{
                $procesos = comparendo::where('numero', $request->numero_proceso)->get();
                $tipoProceso = 'Comparendo';
                $nombreProceso = 'App\\comparendo';
            }

            if(count($procesos) <= 0) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No hay ningún '.$tipoProceso.' con el número especificado.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }

            foreach($procesos as $proceso){ 
                \Jenssegers\Date\Date::setLocale('es');
                $fechaProceso = \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $proceso->fecha_realizacion)->format('Y-m-d');
                $fechaSancion = \Jenssegers\Date\Date::createFromFormat('Y/m/d', $request->fecha_sancion_submit);
                $anioSancion = $fechaSancion->format('Y');
                $fechaSancion = strtoupper($fechaSancion->format('F j')). ' DE '.$fechaSancion->format('Y'); 

                if($request->fecha_resolucion_letras === true){
                                       
                }else{

                }

                $sancion = sancion::create([
                    'fecha_sancion' => $request->fecha_sancion_submit,
                    'documento' => null,
                    'cantidad_salarios' => $cantidad,
                    'cuantia_salarios' => $cuantia,
                    'proceso_id' => $proceso->id,
                    'proceso_type' => $nombreProceso,
                    'numero_proceso' => $proceso->numero,
                    'numero' => $numero
                ]);

                $templateProcessor = new TemplateProcessor(storage_path('app/plantillas/'.$plantilla));
                $templateProcessor->setValue(array('NUMERO_SANCION', 'FECHA_SANCION', 'AÑO_SANCION', 'NOMBRE_INFRACTOR', 'TIPO_DOCUMENTO_INFRACTOR', 'NUMERO_DOCUMENTO_INFRACTOR', 'NUMERO_COMPARENDO', 'FECHA_COMPARENDO', 'CLASE_VEHICULO', 'PLACA_VEHICULO', 'PLACA_AGENTE', 'NOMBRE_AGENTE', 'NOMBRE_INFRACCION', 'DESCRIPCION_INFRACCION', 'SALARIOS_SANCION'), 
                                                array($sancion->numero, $fechaSancion, $anioSancion, $proceso->hasInfractor->nombre, $proceso->hasInfractor->hasTipoDocumento->name, $proceso->hasInfractor->numero_documento, $proceso->numero, $fechaProceso, $proceso->hasVehiculo->hasVehiculoClase->name, $proceso->hasVehiculo->placa, $proceso->hasAgente->placa, $proceso->hasAgente->hasUsuario->name, $proceso->hasInfraccion->name, $proceso->hasInfraccion->descripcion, $sancion->salarios)
                                            );
                $templateProcessor->saveAs(storage_path('app/temp/').'sancion.docx');   
                
                $sancion->documento = \Storage::move('temp/sancion.docx', 'sanciones/'.$sancion->numero.' '.$fechaSancion.'.docx');
                $sancion->save();

                $numero = ltrim($numero, "0");
                ++$numero;
                $numero = sprintf("%'.03d\n", $numero);
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la sanción correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear la sanción.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function editar($id)
    {
        $sancion = sancion::find($id);
        return view('admin.inspeccion.sanciones.editar', ['sancion'=>$sancion])->render();
    }

    public function actualizar(Request $request)
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

        try{
            
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

    public function obtenerTodas()
    {
        $sanciones = sancion::orderBy('fecha_sancion', 'desc')->get();
        return view('admin.inspeccion.sanciones.listado', ['sanciones'=>$sanciones])->render();
    }
}
