<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dependencia;
use App\User;
use Validator;
use Illuminate\Validation\Rule;

class DependenciaController extends Controller
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

        return view('admin.sistema.dependencias.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function crearDependencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:dependencia,name',
        ], [
            'name.required' => 'Debe suministrar un nombre a la dependencia.',
            'name.unique' => 'El nombre a registrar ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $dependencia = new dependencia();
            $dependencia->name = strtoupper($request->name);
            if ($dependencia->save()) {
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

    public function editarDependencia($id)
    {
        $dependencia = dependencia::withTrashed()->find($id);

        return view('admin.sistema.dependencias.editarDependencia', ['dependencia' => $dependencia])->render();
    }

    public function actualizarDependencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idDependencia' => 'integer|required|exists:dependencia,id',
            'nameDependencia' => [
                'required',
                'string',
                Rule::unique('dependencia', 'name')->ignore($request->idDependencia),
            ],
        ], [
            'nameDependencia.required' => 'Debe suministrar un nombre a la dependencia.',
            'nameDependencia.unique' => 'El nombre a registrar ya está en uso.',
            'idDependencia.integer' => 'El tipo de id de la dependencia no es válido.',
            'idDependencia.required' => 'No se suministro el id de la depeendencia a actualizar.',
            'idDependencia.exists' => 'El id proporcionado no existe en la base de datos.',
        ]);
        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $dependencia = dependencia::withTrashed()->find($request->idDependencia);
            $dependencia->name = strtoupper($request->nameDependencia);
            if ($dependencia->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la dependencia en el sistema.',
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

    public function obtenerDependencias()
    {
        $dependencias = dependencia::withTrashed()->orderBy('name', 'asc')->paginate(10);

        return view('admin.sistema.dependencias.listadoDependencias', ['dependencias' => $dependencias])->render();
    }

    public function eliminarDependencia($id)
    {
        $dependencia = dependencia::withTrashed()->find($id);
        $dependencia->delete();
        if ($dependencia->trashed()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado la dependencia en el sistema.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function restaurarDependencia($id)
    {
        $dependencia = dependencia::withTrashed()->find($id);
        $dependencia->restore();
        if (! $dependencia->trashed()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha activado la dependencia.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function obtenerFuncionariosDependencia($id)
    {
        $funcionarios = User::where('dependencia_id', $id)->select('name', 'id')->get();

        return $funcionarios->toJson();
    }

    public function nuevaDependencia()
    {
        return view('admin.sistema.dependencias.nuevaDependencia')->render();
    }
}
