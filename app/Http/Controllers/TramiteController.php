<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tipo_sustrato;
use App\tramite;
use App\tramite_requerimiento;
use Validator;
use Illuminate\Validation\Rule;

class TramiteController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado entrada',
            '3' => 'Radicado salida',
            '4' => 'Consecutivo',
        ];
        $sFiltro = null;

        return view('admin.tramites.tramites.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function obtenerTramites()
    {
        $tramites = Tramite::withTrashed()->orderBy('name', 'asc')->paginate(15);

        return view('admin.tramites.tramites.listadoTramites', ['tramites' => $tramites])->render();
    }

    public function crearTramite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tramite,name',
            'requiere_sustrato' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'requiere_placa' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'solicita_carpeta' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'tipo_sustrato' => 'integer|exists:tipo_sustrato,id',
            'cupl' => 'required|numeric',
            'entidad' => 'required|numeric',
            'ministerio' => 'required|numeric',
            'sustrato' => 'required|numeric'
        ], [
            'name.required' => 'Debe suministrar un nombre al tramite.',
            'name.unique' => 'El nombre a registrar ya está en uso.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'requiere_sustrato.in' => 'Los valores proporcionados para el campo Requiere sustrato, no son válidos.',
            'requiere_sustrato.required' => 'No se ha especificado el valor para el campo Requiere sustrato.',
            'requiere_sustrato.string' => 'El valor especificado para el campo Requiere sustrato, no tiene un formato válido.',
            'solicita_carpeta.in' => 'Los valores proporcionados para el campo Solicita carpeta, no son válidos.',
            'solicita_carpeta.required' => 'No se ha especificado el valor para el campo Solicita carpeta.',
            'solicita_carpeta.string' => 'El valor especificado para el campo Solicita carpeta, no tiene un formato válido.',
            'requiere_placa.in' => 'Los valores proporcionados para el campo Requiere placa, no son válidos.',
            'requiere_placa.required' => 'No se ha especificado el valor para el campo Requiere placa.',
            'requiere_placa.string' => 'El valor especificado para el campo Requiere placa, no tiene un formato válido.',
            'tipo_sustrato.integer' => 'El ID del Tipo de Sustrato especificado no es válido.',
            'tipo_sustrato.exists' => 'El Tipo de Sustrato especificado no existe en el sistema.',
            'cupl.required' => 'No se ha especificado el valor de CUPL.',
            'cupl.numeric' => 'El valor especificado para CUPL no tiene un formato válido.',
            'entidad.required' => 'No se ha especificado el valor de Entidad',
            'entidad.numeric' => 'El valor especificado para Entidad no tiene un formato válido.',
            'ministerio.required' => 'No se ha especificado el valor de Ministerio',
            'ministerio.numeric' => 'El valor especificado para Ministerio no tiene un formato válido.',
            'sustrato.required' => 'No se ha especificado el valor del Sustrato',
            'sustrato.numeric' => 'El valor especificado para Sustrato no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            if($request->tipo_sustrato === 'SI' && $request->tipo_sustrato == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha especificado que el tramite requiere de sustrato, pero no ha especificado el tipo de sustrato requerido.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
            $tramite = new tramite();
            $tramite->name = strtoupper($request->name);
            $tramite->requiere_sustrato = $request->requiere_sustrato;
            $tramite->solicita_carpeta = $request->solicita_carpeta;
            $tramite->requiere_placa = $request->requiere_placa;
            $tramite->cupl = $request->cupl;
            $tramite->ministerio = $request->ministerio;
            $tramite->entidad = $request->entidad;
            $tramite->sustrato = $request->sustrato;
            if($request->requiere_sustrato === 'SI'){
                $tramite->tipo_sustrato_id = $request->tipo_sustrato;
            }
            if ($tramite->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la dependencia en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarTramite($id)
    {
        $tramite = tramite::withTrashed()->find($id);
        $tiposSustratos = tipo_sustrato::pluck('name','id');

        return view('admin.tramites.tramites.editarTramite', ['tramite' => $tramite, 'tiposSustratos'=>$tiposSustratos])->render();
    }

    public function actualizarTramite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idTramite' => 'integer|required|exists:tramite,id',
            'nameTramite' => ['required', 'string', Rule::unique('tramite', 'name')->ignore($request->idTramite)],
            'requiere_sustrato' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'requiere_placa' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'tipo_sustrato' => 'integer|exists:tipo_sustrato,id',
            'solicita_carpeta' => [Rule::in(['SI', 'NO']), 'required', 'string'],
            'cupl' => 'required|numeric',
            'entidad' => 'required|numeric',
            'ministerio' => 'required|numeric',
            'sustrato' => 'required|numeric'
        ], [
            'nameTramite.required' => 'Debe suministrar un nombre del tramite.',
            'nameTramite.unique' => 'El nombre a registrar ya está en uso.',
            'idTramite.integer' => 'El tipo de id de la tramite no es válido.',
            'idTramite.required' => 'No se suministro el id del tramite a actualizar.',
            'idTramite.exists' => 'El tramite especificado no existe en la base de datos.',
            'requiere_sustrato.in' => 'Los valores proporcionados para el campo Requiere sustrato, no son válidos.',
            'requiere_sustrato.required' => 'No se ha especificado el valor para el campo Requiere sustrato.',
            'requiere_sustrato.string' => 'El valor especificado para el campo Requiere sustrato, no tiene un formato válido.',
            'solicita_carpeta.in' => 'Los valores proporcionados para el campo Solicita carpeta, no son válidos.',
            'solicita_carpeta.required' => 'No se ha especificado el valor para el campo Solicita carpeta.',
            'solicita_carpeta.string' => 'El valor especificado para el campo Solicita carpeta, no tiene un formato válido.',
            'requiere_placa.in' => 'Los valores proporcionados para el campo Requiere placa, no son válidos.',
            'requiere_placa.required' => 'No se ha especificado el valor para el campo Requiere placa.',
            'requiere_placa.string' => 'El valor especificado para el campo Requiere placa, no tiene un formato válido.',
            'tipo_sustrato.integer' => 'El ID del Tipo de Sustrato especificado no es válido.',
            'tipo_sustrato.exists' => 'El Tipo de Sustrato especificado no existe en el sistema.',
            'cupl.required' => 'No se ha especificado el valor de CUPL.',
            'cupl.numeric' => 'El valor especificado para CUPL no tiene un formato válido.',
            'entidad.required' => 'No se ha especificado el valor de Entidad',
            'entidad.numeric' => 'El valor especificado para Entidad no tiene un formato válido.',
            'ministerio.required' => 'No se ha especificado el valor de Ministerio',
            'ministerio.numeric' => 'El valor especificado para Ministerio no tiene un formato válido.',
            'sustrato.required' => 'No se ha especificado el valor del Sustrato',
            'sustrato.numeric' => 'El valor especificado para Sustrato no tiene un formato válido.'
        ]);
        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            if($request->tipo_sustrato === 'SI' && $request->tipo_sustrato == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ha especificado que el tramite requiere de sustrato, pero no ha especificado el tipo de sustrato requerido.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
            $tramite = tramite::withTrashed()->find($request->idTramite);
            $tramite->name = strtoupper($request->nameTramite);
            $tramite->requiere_sustrato = $request->requiere_sustrato;
            $tramite->solicita_carpeta = $request->solicita_carpeta;
            $tramite->requiere_placa = $request->requiere_placa;
            $tramite->cupl = $request->cupl;
            $tramite->ministerio = $request->ministerio;
            $tramite->entidad = $request->entidad;
            $tramite->sustrato = $request->sustrato;
            if($request->requiere_sustrato === 'SI'){
                $tramite->tipo_sustrato_id = $request->tipo_sustrato;
            }
            if ($tramite->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la tramite en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function eliminarTramite($id)
    {
        $tramite = tramite::find($id);
        if ($tramite == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha encontrado el tramite en la base de datos.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $tramite->delete();
            if ($tramite->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el tramite en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function restaurarTramite($id)
    {
        $tramite = tramite::withTrashed()->find($id);
        if ($tramite == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha encontrado el tramite en la base de datos.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $tramite->restore();
            if (! $tramite->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado el tramite.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function nuevoTramite()
    {
        $tiposSustratos = tipo_sustrato::pluck('name','id');
        return view('admin.tramites.tramites.nuevoTramite', ['tiposSustratos'=>$tiposSustratos])->render();
    }

    public function administrarRequerimientos($tramiteId)
    {
        $requerimientos = tramite_requerimiento::where('tramite_id',$tramiteId)->get();
        return view('admin.tramites.tramites.administrarRequerimientos',['requerimientos'=>$requerimientos,'id'=>$tramiteId])->render();
    }

    public function obtenerRequerimientos($tramiteId)
    {
        $requerimientos = tramite_requerimiento::where('tramite_id',$tramiteId)->get();
        return view('admin.tramites.tramites.listadoRequerimientos',['requerimientos'=>$requerimientos,'id'=>$tramiteId])->render();
    }

    public function crearRequerimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'id' => 'required|integer|exists:tramite,id'
        ], [
            'nombre.required' => 'No ha especificado un nombre para el requerimiento.',
            'nombre.string' => 'El nombre del requerimiento especificado no tiene un formato válido.',
            'descripcion.required' => 'No ha especificado una descripción para el requerimiento.',
            'descripcion.string' => 'La descripción especificada para el requerimeinto no tiene un formato válido.',
            'id.required' => 'No se ha especificado el tramite al cual pertenece el requerimeinto.',
            'id.integer' => 'El ID del tramite especificado no tiene un formato válido.',
            'id.exists' => 'El tramite especificado al cual pertene el requerimiento no existe en el sistema.'
        ]);
        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        try{
            tramite_requerimiento::create([
                'name' => strtoupper($request->nombre),
                'description' => $request->descripcion,
                'tramite_id' => $request->id
            ]);
            return json_encode(true);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un problema en el registro del requerimiento. Si el problema persiste, por favor contacte a un administrador.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function editarRequerimiento($requerimientoId)
    {
        $requerimiento = tramite_requerimiento::find($requerimientoId);
        return view('admin.tramites.tramites.editarRequerimiento',['requerimiento'=>$requerimiento])->render();        
    }

    public function actualizarRequerimiento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'id' => 'required|integer|exists:tramite_requerimiento,id'
        ], [
            'nombre.required' => 'No ha especificado un nombre para el requerimiento.',
            'nombre.string' => 'El nombre del requerimiento especificado no tiene un formato válido.',
            'descripcion.required' => 'No ha especificado una descripción para el requerimiento.',
            'descripcion.string' => 'La descripción especificada para el requerimeinto no tiene un formato válido.',
            'id.required' => 'No se ha especificado el requerimeinto a actualizar.',
            'id.integer' => 'El ID del requerimiento especificado no tiene un formato válido.',
            'id.exists' => 'El requerimiento especificado al cual pertene el requerimiento no existe en el sistema.'
        ]);
        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        try{
            $requerimiento = tramite_requerimiento::find($request->id);
            $requerimiento->name = strtoupper($request->nombre);
            $requerimiento->description = $request->descripcion;
            $requerimiento->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el requerimiento existosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Ha ocurrido un problema en la actualización del requerimiento. Si el problema persiste, por favor contacte a un administrador.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function obtenerRequerimientosTramites(Request $request)
    {
        return tramite::with('hasRequerimientos')->has('hasRequerimientos')->whereIn('id', $request->tramites)->get()->toJson();        
    }
}
