<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\empresa_mensajeria;
use Validator;

class EmpresaMensajeriaController extends Controller
{
    public function crearEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:empresa_mensajeria',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'Ya existe una empresa con el nombre especificado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $empresa = new empresa_mensajeria();
            $empresa->name = strtoupper($request->name);
            if ($empresa->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'La empresa ha sido registrada exitosamente en el sistema.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la creación',
                ], 200);
            }
        }
    }

    public function editarEmpresa($id)
    {
        $empresa = empresa_mensajeria::find($id);

        return view('admin.sistema.empresa_mensajeria.editarEmpresa', ['empresa' => $empresa])->render();
    }

    public function actualizarEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:empresa_mensajeria,id',
            'name' => ['required', 'string', Rule::unique('empresa_mensajeria')->ignore($request->id)],
        ], [
            'id.exists' => 'La empresa especificada no existe en el sistema',
            'id.integer' => 'El ID de la empresa no tiene un formato válido',
            'id.required' => 'No se ha especificado una empresa.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $empresa = empresa_mensajeria::find($request->id);
            $empresa->name = strtoupper($request->name);
            if ($empresa->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la empresa.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la actualización.',
                ], 200);
            }
        }
    }

    public function obtenerEmpresas()
    {
        $empresas = empresa_mensajeria::paginate(15);

        return view('admin.sistema.empresa_mensajeria.listadoEmpresas', ['empresas' => $empresas])->render();
    }

    public function administrar()
    {
        $filtros = [
            '1' => 'Numero documento',
            '2' => 'Radicado entrada',
            '3' => 'Radicado salida',
            '4' => 'Consecutivo',
        ];
        $sFiltro = null;

        return view('admin.sistema.empresa_mensajeria.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function nuevaEmpresa()
    {
        return view('admin.sistema.empresa_mensajeria.nuevaEmpresa')->render();
    }
}
