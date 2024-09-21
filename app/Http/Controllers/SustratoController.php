<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\SustratosAnulados;
use App\Exports\SustratosConsumidos;
use App\sustrato_liberacion;
use App\sustrato_liberacion_motivo;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\sustrato;
use App\tipo_sustrato;
use App\sustrato_anulacion_motivo;
use App\sustrato_anulacion;
use App\Exports\SustratosDetallados;

class SustratoController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Número sustrato',
            '2' => 'Placa',
            '3' => 'Número documento'
        ];
        $sFiltro = null;

        $tiposSustratos = tipo_sustrato::pluck('name','id');

        return view('admin.tramites.sustratos.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro,'tiposSustratos'=>$tiposSustratos]);
    }

    public function obtenerSustratos()
    {
        $sustratos = sustrato::with('hasTipoSustrato')->paginate(50);

        return view('admin.tramites.sustratos.listadoSustratos', ['sustratos' => $sustratos])->render();
    }

    public function editarSustrato($id)
    {
        $sustrato = sustrato::with('hasTipoSustrato')->find($id);
        $tiposSustratos = tipo_sustrato::pluck('name', 'id');

        return view('admin.tramites.sustratos.editarSustrato', [
            'sustrato' => $sustrato,
            'tiposSustratos' => $tiposSustratos,
        ])->render();
    }

    public function actualizarSustrato(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sustrato' => 'integer|required|exists:sustrato,id',
            'tipo_sustrato' => 'integer|required|exists:tipo_sustrato,id',
            'numero' => 'numeric',
        ], [
            'sustrato.integer' => 'El ID del sustrato especificado no tiene un formato válido.',
            'sustrato.required' => 'No se ha especificado el ID del sustrato a actualizar.',
            'sustrato.exists' => 'El ID del sustrato especificado no existe en el sistema.',
            'tipo_sustrato.integer' => 'El ID del tipo de sustrato especificado no tiene un formato válido.',
            'tipo_sustrato.required' => 'No se ha especificado el ID del tipo de sustrato.',
            'tipo_sustrato.exists' => 'El ID del tipo de sustrato especificado no existe en el sistema.',
            'numero.numeric' => 'El número especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $sustrato_tmp = sustrato::where('tipo_sustrato_id', $request->tipo_sustrato)->where('numero', $request->numero)->first();
                if($sustrato_tmp != null){
                    if ($sustrato_tmp->id != $request->sustrato){
                        return response()->view('admin.mensajes.errors', [
                            'errors' => ['Ya hay un sustrato con el mismo tipo y número asignado.'],
                            'encabezado' => 'Errores en la validación:',
                        ], 200);
                    }
                }

                $sustrato = sustrato::find($request->sustrato);
                $sustrato->tipo_sustrato_id = $request->tipo_sustrato;
                $sustrato->numero = $request->numero;
                $sustrato->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha completado la actualización del sustrato.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el sustrato. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function nuevosSustratos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_sustrato_id' => 'integer|required|exists:tipo_sustrato,id',
            'numeros_rango_inicial' => 'integer|required',
            'numeros_rango_final' => 'integer|required',
            'cantidad_digitos' => 'required|integer',
        ], [
            'tipo_sustrato_id.integer' => 'El ID del tipo de sustrato especificado no tiene un formato válido.',
            'tipo_sustrato_id.required' => 'No se ha especificado el ID del tipo de sustrato.',
            'tipo_sustrato_id.exists' => 'El ID del tipo de sustrato especificado no existe en el sistema.',
            'numeros_rango_inicial.integer' => 'El número especificado no tiene un formato válido.',
            'numeros_rango_inicial.required' => '',
            'numeros_rango_final.integer' => 'El número especificado no tiene un formato válido.',
            'numeros_rango_final.required' => '',
            'cantidad_digitos.required' => 'No ha especificado el valor para la cantidad de dígitos de los rangos numéricos.',
            'cantidad_digitos.integer' => 'El valor especificado para la cantidad de dígitos de los rangos numéricos no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            if ($request->numeros_rango_inicial < $request->numeros_rango_final) {
                try {
                    for ($i = $request->numeros_rango_inicial; $i <= $request->numeros_rango_final; $i++) {
                        sustrato::firstOrCreate([
                            'numero' => sprintf("%'.0".$request->cantidad_digitos."d", $i),
                            'tipo_sustrato_id' => $request->tipo_sustrato_id,
                        ]);
                    }

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha completado el ingreso de los nuevos sustratos.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } catch (\Exception $e) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No ha ocurrido un error en el proceso.'],
                        'encabezado' => '¡Error!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El rango inicial debe ser menor al rango final.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function crearNuevosSustratos()
    {
        $tiposSustrato = tipo_sustrato::pluck('name', 'id');

        return view('admin.tramites.sustratos.nuevosSustratos', ['tiposSustrato' => $tiposSustrato])->render();
    }

    public function obtenerListadoTiposSustratos()
    {
        $tipos = tipo_sustrato::paginate(15);
        return view('admin.tramites.sustratos.listadoTiposSustratos', ['tipos'=>$tipos])->render();
    }

    public function nuevoTipoSustrato()
    {
        return view('admin.tramites.sustratos.nuevoTipoSustrato')->render();
    }

    public function crearTipoSustrato(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tipo_sustrato',
        ], [
            'name.required' => 'No se ha especificado el nombre del tipo de sustrato.',
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
                tipo_sustrato::create([
                    'name'=>strtoupper($request->name),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Sustrato ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Tipo Sustrato.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarTipoSustrato($id)
    {
        $tipo = tipo_sustrato::find($id);
        return view('admin.tramites.sustratos.editarTipoSustrato',['tipo'=>$tipo])->render();
    }

    public function actualizarTipoSustrato(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tipo_sustrato',
            'name' => ['required','string',Rule::unique('tipo_sustrato', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo de sustrato a modificar.',
            'id.integer' => 'El ID del tipo de sustrato a modificar no tiene un formato válido.',
            'id.exists' => 'El tipo de sustrato a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre del tipo de sustrato.',
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
                $estado = tipo_sustrato::find($request->id);
                $estado->name = strtoupper($request->name);
                $estado->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Sustrato ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Tipo Sustrato.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function obtenerListadoMotivosAnulaciones()
    {
        $motivos = sustrato_anulacion_motivo::paginate(15);
        return view('admin.tramites.sustratos.listadoMotivosAnulaciones', ['motivos'=>$motivos])->render();
    }

    public function nuevoMotivoAnulacion()
    {
        return view('admin.tramites.sustratos.nuevoMotivoAnulacion')->render();
    }

    public function crearMotivoAnulacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:sustrato_anulacion_motivo',
        ], [
            'name.required' => 'No se ha especificado el nombre del motivo de anulacion.',
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
                sustrato_anulacion_motivo::create([
                    'name'=>strtoupper($request->name),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Motivo Anulación ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Motivo Anulación.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarMotivoAnulacion($id)
    {
        $motivo = sustrato_anulacion_motivo::find($id);
        return view('admin.tramites.sustratos.editarMotivoAnulacion',['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoAnulacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:sustrato_anulacion_motivo',
            'name' => ['required','string',Rule::unique('sustrato_anulacion_motivo', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el motivo de anulacion a modificar.',
            'id.integer' => 'El ID del motivo de anulacion a modificar no tiene un formato válido.',
            'id.exists' => 'El motivo de anulacion a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre del motivo de anulacion.',
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
                $estado = sustrato_anulacion_motivo::find($request->id);
                $estado->name = strtoupper($request->name);
                $estado->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Motivo Anulación ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Motivo Anulación.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function filtrarSustratos(Request $request)
    {
        $sustratos = [];
        switch($request->criterio){
            case 1: $sustratos = $this->filtrarPorNumero($request->parametro);
                break;
            case 2: $sustratos = $this->filtrarPorPlaca($request->parametro);   
                break;
            case 3: $sustratos = $this->filtrarPorNumeroDocumento($request->parametro);
                break;
        }
        return view('admin.tramites.sustratos.listadoSustratos', ['sustratos' => $sustratos])->render();
    }

    private function filtrarPorNumero($parametro)
    {
        return sustrato::where('numero', 'like', '%'.$parametro.'%')->get();
    }

    private function filtrarPorPlaca($parametro)
    {
        return sustrato::whereHas('hasTramiteFinalizacion', function($query) use ($parametro){
            $query->whereHas('hasTramiteServicio', function($query2) use ($parametro){
                $query2->where('placa', $parametro);
            });
        })->get();
    }

    private function filtrarPorNumeroDocumento($parametro)
    {
        return sustrato::whereHas('hasTramiteFinalizacion', function($query) use ($parametro){
            $query->whereHas('hasTramiteServicio', function($query2) use ($parametro){
                $query2->whereHas('hasSolicitud', function($query3) use ($parametro){
                    $query3->whereHas('hasTurnos', function ($query4) use ($parametro){
                        $query4->whereHas('hasUsuarioSolicitante', function($query5) use ($parametro){
                            $query5->where('numero_documento', $parametro);
                        });
                    });
                });
            });
        })->orWhereHas('hasLicencia', function($query) use ($parametro){
            $query->whereHas('hasTramiteSolicitud', function($query2) use ($parametro){
                $query2->whereHas('hasTurnos', function ($query3) use ($parametro){
                    $query3->whereHas('hasUsuarioSolicitante', function($query4) use ($parametro){
                        $query4->where('numero_documento', $parametro);
                    });
                });
            });
        })->get();
    }

    public function verConsumo($id)
    {
        $sustrato = sustrato::find($id);
        if($sustrato->proceso_type == 'App\tramite_servicio_finalizacion'){
            return view('admin.tramites.sustratos.verTramiteServicio', ['servicio'=>$sustrato->hasConsumo->hasTramiteServicio])->render();
        }elseif($sustrato->proceso_type == 'App\tramite_licencia'){
            return view('admin.tramites.sustratos.verLicencia', ['licencia'=>$sustrato->hasConsumo])->render();
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Sustrato sin consumir.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function verAnulacion($id)
    {
        $sustrato = sustrato::with('hasAnulacion')->find($id);
        return view('admin.tramites.sustratos.verAnulacion', ['anulacion'=>$sustrato->hasAnulacion])->render();
    }

    public function anularSustratoF1($sustratoId)
    {
        $motivos = sustrato_anulacion_motivo::pluck('name','id');
        return view('admin.tramites.sustratos.anularSustrato', ['motivos'=>$motivos, 'sustratoId'=>$sustratoId])->render();
    }

    public function anularSustratoF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sustratoId' => 'required|integer|exists:sustrato,id',
            'observacion' => 'required|string'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

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
                $sustrato->consumido = 'SI';
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
                    'observacion' => $request->observacion,
                    'funcionario_id' => auth()->user()->id
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

    public function generarReporteSustratos(Request $request)
    {
        $sustratos = null;
        switch ($request->estado){
            case 1: $sustratos = sustrato::where('tipo_sustrato_id', $request->tipo)->whereHas('hasTramiteFinalizacion', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->orWhereHas('hasLicencia', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->where('sustrato.tipo_sustrato_id', $request->tipo)->get();
                break;
            case 2: $sustratos = sustrato::where('tipo_sustrato_id', $request->tipo)->whereHas('hasAnulacion', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->get();
                break;
            case 3: $sustratos = sustrato::where('tipo_sustrato_id', $request->tipo)->whereHas('hasAnulacion', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->orWhereHas('hasTramiteFinalizacion', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->orWhereHas('hasLicencia', function ($query) use ($request){
                $query->whereDate('created_at', '>=', $request->fecha_inicio_submit)->whereDate('created_at', '<=', $request->fecha_fin_submit);
            })->where('sustrato.tipo_sustrato_id', $request->tipo)->get();
                break;    
        }

        if($sustratos->count() > 0){
            if($request->estado == 1){
                return Excel::download(new SustratosConsumidos($sustratos), 'ReporteSustratosConsumidos-'.$request->fecha_inicio_submit.'-a-'.$request->fecha_fin_submit.'.xlsx');
            }elseif($request->estado == 2){
                return Excel::download(new SustratosAnulados($sustratos), 'ReporteSustratosAnulados-'.$request->fecha_inicio_submit.'-a-'.$request->fecha_fin_submit.'.xlsx');
            }else{
                return Excel::download(new SustratosDetallados($sustratos), 'ReporteSustratosDetallados-'.$request->fecha_inicio_submit.'-a-'.$request->fecha_fin_submit.'.xlsx');
            }
        }else{
            \Session::flash('errorReporte', 'No hay registros de sustratos con los parámetros indicados para el reporte.');
            return back();
        }
    }

    public function liberarSustratoF1($sustratoId)
    {
        $motivos = sustrato_liberacion_motivo::pluck('name','id');
        return view('admin.tramites.sustratos.liberarSustrato', ['motivos'=>$motivos, 'sustratoId'=>$sustratoId])->render();
    }

    public function liberarSustratoF2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sustratoId' => 'required|integer|exists:sustrato,id',
            'observacion' => 'required|string'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $sustrato = sustrato::find($request->sustratoId);
        $proceso = $sustrato->hasConsumo;

        try{
            \DB::beginTransaction();
            $sustrato->consumido = 'NO';
            $sustrato->proceso_id = null;
            $sustrato->proceso_type = null;
            $sustrato->save();
            $proceso->delete();
            
            sustrato_liberacion::create([
                'sustrato_id' => $sustrato->id,
                'sus_liberacion_motivo_id' => $request->motivo_liberacion,
                'observacion' => $request->observacion,
                'funcionario_id' => auth()->user()->id
            ]);
            \DB::commit();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha liberado el sustrato.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            \DB::rollback();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido liberar el sustrato.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function obtenerListadoMotivosLiberaciones()
    {
        $motivos = sustrato_liberacion_motivo::paginate(15);
        return view('admin.tramites.sustratos.listadoMotivosLiberaciones', ['motivos'=>$motivos])->render();
    }

    public function nuevoMotivoLiberacion()
    {
        return view('admin.tramites.sustratos.nuevoMotivoLiberacion')->render();
    }

    public function crearMotivoLiberacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:sustrato_liberacion_motivo',
        ], [
            'name.required' => 'No se ha especificado el nombre del motivo de liberacion.',
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
                sustrato_liberacion_motivo::create([
                    'name'=>strtoupper($request->name),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Motivo Liberación ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Motivo Liberación.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarMotivoLiberacion($id)
    {
        $motivo = sustrato_liberacion_motivo::find($id);
        return view('admin.tramites.sustratos.editarMotivoLiberacion',['motivo'=>$motivo])->render();
    }

    public function actualizarMotivoLiberacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:sustrato_liberacion_motivo',
            'name' => ['required','string',Rule::unique('sustrato_liberacion_motivo', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el motivo de liberacion a modificar.',
            'id.integer' => 'El ID del motivo de liberacion a modificar no tiene un formato válido.',
            'id.exists' => 'El motivo de liberacion a modificar no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre del motivo de liberacion.',
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
                $estado = sustrato_liberacion_motivo::find($request->id);
                $estado->name = strtoupper($request->name);
                $estado->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Motivo Liberación ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Motivo Liberación.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function verLiberaciones($id)
    {
        $liberaciones = sustrato_liberacion::where('sustrato_id', $id)->get();
        return view('admin.tramites.sustratos.verLiberaciones', ['liberaciones'=>$liberaciones])->render();
    }

    public function restaurarSustrato($id)
    {        
        try{
            $sustrato = sustrato::find($id);
            $sustrato->consumido = 'NO';
            $sustrato->proceso_id = null;
            $sustrato->proceso_type = null;
            $sustrato->save();
            $sustrato->hasAnulacion->delete();
            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El sustrato ha sido restaurado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido restaurar el sustrato.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
        }
    }
}
