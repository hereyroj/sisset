<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tramite_grupo;
use App\tramite;
use Validator;
use Illuminate\Validation\Rule;

class TramiteGrupoController extends Controller
{
    public function obtenerGrupos()
    {
        $grupos = tramite_grupo::with('hasTramites')->orderBy('name', 'asc')->paginate(25);
        return view('admin.tramites.tramitesGrupos.listadoGrupos', ['grupos'=>$grupos])->render();
    }

    public function eliminarGrupo($id)
    {

    }

    public function restaurarGrupo($id)
    {

    }

    public function editarGrupo($id)
    {
        $grupo = tramite_grupo::find($id);
        $tramites = tramite::all();
        return view('admin.tramites.tramitesGrupos.editarGrupo', ['grupo' => $grupo, 'tramites'=>$tramites])->render();
    }

    public function actualizarGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:tramite_grupo,id',
            'nombre' => ['required','string',Rule::unique('tramite_grupo','name')->ignore($request->id)],
            'codigo' => 'required|string',
            'tramites' => 'required|array|min:1'
        ], [
            
        ]);

        if ($validator->fails()) {
            $request->flash();
            $tramites = tramite::all();
            return view('admin.tramites.tramitesGrupos.editarGrupo', ['tramites' => $tramites])->withErrors($validator->errors()->all())->render();
        }

        try {
            $grupo = tramite_grupo::find($request->id);
            $grupo->name = $request->nombre;
            $grupo->code = $request->codigo;
            $grupo->save();
            $grupo->hasTramites()->sync($request->tramites);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el grupo exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function nuevoGrupo()
    {
        $tramites = tramite::all();
        return view('admin.tramites.tramitesGrupos.nuevoGrupo', ['tramites' => $tramites])->render();
    }

    public function crearGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:tramite_grupo,name',
            'codigo' => 'required|string',
            'tramites' => 'required|array|min:1'
        ], [

        ]);

        if ($validator->fails()) {
            $request->flash();
            $tramites = tramite::all();
            return view('admin.tramites.tramitesGrupos.nuevoGrupo', ['tramites' => $tramites])->withErrors($validator->errors()->all())->render();
        }

        try{
            $grupo = tramite_grupo::create([
                'name' => $request->nombre,
                'code' => $request->codigo
            ]);
            $grupo->hasTramites()->sync($request->tramites);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el grupo exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function obtenerTramites($id)
    {
        $grupo = tramite_grupo::find($id);
        return $grupo->hasTramites->pluck('name','id');
    }
}
