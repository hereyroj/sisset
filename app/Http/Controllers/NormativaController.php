<?php

namespace App\Http\Controllers;

use App\normativa;
use App\normativa_tipo;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Validation\Rule;
use Validator;

class NormativaController extends Controller
{
    public function administrar()
    {
        $filtros = [
            '1' => 'Número',
            '2' => 'Fecha',
            '3' => 'Objeto'
        ];
        $sFiltro = null;
        return view('admin.normativa.administrar', ['filtros'=>$filtros,'sFiltro'=>$sFiltro]);
    }

    public function obtenerTodas()
    {
        $normativas = normativa::paginate(50);
        return view('admin.normativa.listadoNormativas', ['normativas'=>$normativas])->render();
    }

    public function filtrar($param)
    {

    }

    public function nueva()
    {
        $tiposNormativa = normativa_tipo::orderBy('name')->pluck('name','id');
        return view('admin.normativa.nuevaNormativa', ['tiposNormativa'=>$tiposNormativa])->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|integer|exists:normativa_tipo,id',
            'fecha_submit' => 'required|date',
            'objeto' => 'required|string',
            'numero' => 'required|numeric',
            'documento' => 'required|mimetypes:application/pdf|mimes:pdf'
        ], [
            'tipo.required' => 'No se ha especificado el tipo de normativa.',
            'tipo.integer' => 'El ID del tipo de normativa especificado no tiene un formato válido.',
            'tipo.exists' => 'El tipo de normativa especificado no existe en el sistema.',
            'fecha_submit.required' => 'No se ha especificado la fecha de expedición de la normativa.',
            'fecha_submit.date' => 'El valor especificado para la fecha de expedición no es correcto.',
            'objeto.required' => 'No se ha especificado el objeto de la nromativa.',
            'objeto.string' => 'El objeto especificado no tiene un formato válido.',
            'numero.required' => 'No se ha especificado el número de la normativa.',
            'numero.numeric' => 'El número especificado no tiene un formato válido.',
            'documento.required' => 'No se ha suministrado el documento de la nromativa.',
            'documento.mimetypes' => 'El documento de la normativa suministrado no tiene un formato válido.',
            'documento.mimes' => 'El documento de la normativa suministrado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $documento = Storage::disk('normativas')->putFile('/', $request->file('documento'));
            normativa::create([
                'numero' => $request->numero,
                'fecha_expedicion' => $request->fecha_submit,
                'objeto' => $request->objeto,
                'documento' => $documento,
                'normativa_tipo_id' => $request->tipo
            ]);

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la normativa satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            echo $e->getMessage();
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear la normativa.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function editar($id)
    {
        $tiposNormativa = normativa_tipo::orderBy('name')->pluck('name','id');
        $normativa = normativa::find($id);
        return view('admin.normativa.editarNormativa', ['tiposNormativa'=>$tiposNormativa, 'normativa'=>$normativa])->render();
    }

    public function actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:normativa,id',
            'tipo' => 'required|integer|exists:normativa_tipo,id',
            'fecha_submit' => 'required|date',
            'objeto' => 'required|string',
            'numero' => 'required|numeric',
            'documento' => 'nullable|mimetypes:application/pdf|mimes:pdf'
        ], [
            'id.required' => 'No se ha especificado la normativa a actualizar.',
            'id.integer' => 'El ID de la normativa especificada no es válido.',
            'id.exists' => 'La normativa especificada no existe.',
            'tipo.required' => 'No se ha especificado el tipo de normativa.',
            'tipo.integer' => 'El ID del tipo de normativa especificado no tiene un formato válido.',
            'tipo.exists' => 'El tipo de normativa especificado no existe en el sistema.',
            'fecha_submit.required' => 'No se ha especificado la fecha de expedición de la normativa.',
            'fecha_submit.date' => 'El valor especificado para la fecha de expedición no es correcto.',
            'objeto.required' => 'No se ha especificado el objeto de la nromativa.',
            'objeto.string' => 'El objeto especificado no tiene un formato válido.',
            'numero.required' => 'No se ha especificado el número de la normativa.',
            'numero.numeric' => 'El número especificado no tiene un formato válido.',
            'documento.mimetypes' => 'El documento de la normativa suministrado no tiene un formato válido.',
            'documento.mimes' => 'El documento de la normativa suministrado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        
        try{
            $normativa = normativa::find($request->id);
            $normativa->numero = $request->numero;
            $normativa->fecha_expedicion = $request->fecha_submit;
            $normativa->objeto = $request->objeto;            
            $normativa->normativa_tipo_id = $request->tipo;
            if($request->documento != null){
                $documento = Storage::disk('normativas')->putFile('/', $request->file('documento'));
                $normativa->documento = $documento;
            }
            $normativa->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la normativa satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la normativa.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function obtenerListadoTiposNormativa()
    {
        $tiposNormativa = normativa_tipo::all();
        return view('admin.normativa.listadoTiposNormativa', ['tiposNormativa'=>$tiposNormativa])->render();
    }

    public function nuevoTipoNormativa()
    {
        return view('admin.normativa.nuevoTipoNormativa')->render();
    }

    public function crearTipoNormativa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:normativa_tipo,name'
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'Ya existe un tipo de normativa con el nombre especificado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            normativa_tipo::create([
                'name' => $request->name
            ]);

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el tipo de normativa satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el tipo de normativa.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }

    public function editarTipoNormativa($id)
    {
        $tipoNormativa = normativa_tipo::find($id);
        return view('admin.normativa.editarTipoNormativa', ['tipoNormativa'=>$tipoNormativa])->render();
    }

    public function actualizarTipoNormativa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:normativa_tipo,id',
            'name' => ['required','string',Rule::unique('tipo','name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo normativa a actualizar.',
            'id.integer' => 'El ID del tipo normativa especificado no tiene un formato válido.',
            'id.exists' => 'El tipo de normativa especificado no existe.',
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'Ya existe un tipo de normativa con el nombre especificado.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $tipoNormativa = normativa_tipo::find($request->id);
            $tipoNormativa->name = $request->name;
            $tipoNormativa->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el tipo de normativa satisfactoriamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el tipo de normativa.'],
                'encabezado' => 'Errores en el proceso:',
            ], 200);
        }
    }
}
