<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\grupo;
use App\User;
use App\vehiculo_clase;
use App\ventanilla;
use Validator;
use Illuminate\Validation\Rule;
use App\tramite_grupo;

class VentanillaController extends Controller
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

        return view('admin.tramites.ventanilla.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function nuevaVentanilla()
    {
        $grupos = tramite_grupo::all();

        return view('admin.tramites.ventanilla.nuevaVentanilla', [
            'grupos' => $grupos,
        ])->render();
    }

    public function obtenerVentanillas()
    {
        $grupos = tramite_grupo::all();
        $ventanillas = ventanilla::with('hasTramitesGruposAsignados', 'hasFuncionariosAsignados')->get();

        return view('admin.tramites.ventanilla.listadoVentanillas', [
            'ventanillas' => $ventanillas,
            'grupos' => $grupos,
        ])->render();
    }

    public function crearVentanilla(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:ventanilla,name',
            'codigo' => 'required|string|unique:ventanilla,codigo',
            'grupos' => 'required|array',
            ''
        ], [
            'name.required' => 'No se ha especificado el nombre de la ventanilla.',
            'name.unique' => 'El nombre especificado para la ventanilla ya está en uso.',
            'name.string' => 'El nombre especificado para la ventanilla no tiene un formato válido.',
            'codigo.required' => 'No se ha especificado el código de la ventanilla.',
            'codigo.unique' => 'El código especificado para la ventanilla ya está en uso.',
            'codigo.string' => 'El código especificado para la ventanilla no tiene un formato válido.',
            'grupos.required' => 'No se han seleccionado algún trámite.',
            'grupos.array' => 'El formato de los trámites no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                \DB::beginTransaction();
                $todasLasPrioridades = true;
                foreach ($request->grupos as $grupo){
                    $prioridad = $request->get('prioridad-'.$grupo);
                    if($prioridad == null || $prioridad == ''){
                        $todasLasPrioridades = false;
                    }
                }

                if(!$todasLasPrioridades){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se han suministrado todas las prioridades para los grupos.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }

                $ventanilla = ventanilla::create([
                    'name' => $request->name,
                    'codigo' => $request->codigo,
                ]);

                foreach ($request->grupos as $grupo){
                    $ventanilla->hasTramitesGruposAsignados()->save(tramite_grupo::find($grupo), ['prioridad'=>$request->get('prioridad-'.$grupo)]);
                }

                \DB::commit();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la ventanilla en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarVentanilla($id)
    {
        $grupos = tramite_grupo::all();
        $ventanilla = ventanilla::find($id);

        return view('admin.tramites.ventanilla.editarVentanilla', [
            'ventanilla' => $ventanilla,
            'grupos' => $grupos,
        ])->render();
    }

    public function actualizarVentanilla(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ventanilla' => 'required|integer|exists:ventanilla,id',
            'name' => ['required', 'string', Rule::unique('ventanilla', 'name')->ignore($request->ventanilla)],
            'codigo' => ['required', 'string', Rule::unique('ventanilla', 'codigo')->ignore($request->ventanilla)],
            'grupos' => 'required|array',
        ], [
            'name.required' => 'No se ha especificado el nombre de la ventanilla.',
            'name.unique' => 'El nombre especificado para la ventanilla ya está en uso.',
            'name.string' => 'El nombre especificado para la ventanilla no tiene un formato válido.',
            'codigo.required' => 'No se ha especificado el código de la ventanilla.',
            'codigo.unique' => 'El código especificado para la ventanilla ya está en uso.',
            'codigo.string' => 'El código especificado para la ventanilla no tiene un formato válido.',
            'grupos.required' => 'No se han seleccionado algún trámite.',
            'grupos.array' => 'El formato de los trámites no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $todasLasPrioridades = true;
                foreach ($request->grupos as $grupo){
                    $prioridad = $request->get('prioridad-'.$grupo);
                    if($prioridad == null || $prioridad == ''){
                        $todasLasPrioridades = false;
                    }
                }

                if(!$todasLasPrioridades){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se han suministrado todas las prioridades para los grupos.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }

                $ventanilla = ventanilla::find($request->ventanilla);
                $ventanilla->name = $request->name;
                $ventanilla->codigo = $request->codigo;
                $ventanilla->save();

                $ventanilla->hasTramitesGruposAsignados()->detach();
                foreach ($request->grupos as $grupo){
                    $ventanilla->hasTramitesGruposAsignados()->save(tramite_grupo::find($grupo), ['prioridad'=>$request->get('prioridad-'.$grupo)]);
                }

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la ventanilla en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }
}
