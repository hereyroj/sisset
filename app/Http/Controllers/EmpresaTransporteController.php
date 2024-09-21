<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\empresa_transporte;
use Illuminate\Validation\Rule;

class EmpresaTransporteController extends Controller
{
    public function crearEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:empresa_transporte',
            'email' => 'email|unique:empresa_transporte',
            'nit' => 'required|string|unique:empresa_transporte',
            'telephone' => 'numeric|unique:empresa_transporte',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'email.email' => 'El correo especificado no tiene un formato válido.',
            'name.unique' => 'Ya existe una empresa con el nombre especificado.',
            'nit.string' => 'El Nit especificado no tiene un formato válido.',
            'nit.unique' => 'Ya existe una empresa con el nit especificado.',
            'nit.required' => 'No se ha especificado el Nit.',
            'email.unique' => 'El correo especificado ya está en uso.',
            'telephone.unique' => 'El teléfono especificado ya está en uso.',
            'telephone.numeric' => 'El teléfono especificado no tiene un formato válido',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $empresa = new empresa_transporte();
            $empresa->nit = $request->nit;
            $empresa->name = strtoupper($request->name);
            $empresa->email = $request->email;
            $empresa->telephone = $request->telephone;
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
        $empresa = empresa_transporte::find($id);

        return view('admin.sistema.empresa_transporte.editarEmpresa', ['empresa' => $empresa])->render();
    }

    public function actualizarEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:empresa_transporte,id',
            'name' => ['required', 'string', Rule::unique('empresa_transporte')->ignore($request->id)],
            'email' => ['email', Rule::unique('empresa_transporte')->ignore($request->id)],
            'nit' => ['required', 'string', Rule::unique('empresa_transporte')->ignore($request->id)],
            'telephone' => ['numeric', Rule::unique('empresa_transporte')->ignore($request->id)],
        ], [
            'id.exists' => 'La empresa especificada no existe en el sistema',
            'id.integer' => 'El ID de la empresa no tiene un formato válido',
            'id.required' => 'No se ha especificado una empresa.',
            'email.email' => 'El correo especificado no tiene un formato válido.',
            'email.unique' => 'El correo especificado ya está en uso.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'nit.unique' => 'El Nit especificado yaa está en uso.',
            'nit.required' => 'No se ha especificado un Nit.',
            'nit.string' => 'El Nit especificado no tiene un formato válido.',
            'telephone.unique' => 'El teléfono especificado ya está en uso.',
            'telephone.numeric' => 'El teléfono especificado no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $empresa = empresa_transporte::find($request->id);
            $empresa->nit = $request->nit;
            $empresa->name = strtoupper($request->name);
            $empresa->email = $request->email;
            $empresa->telephone = $request->telephone;
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
        $empresas = empresa_transporte::paginate(15);

        return view('admin.sistema.empresa_transporte.listadoEmpresas', ['empresas' => $empresas])->render();
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

        return view('admin.sistema.empresa_transporte.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function nuevaEmpresa()
    {
        return view('admin.sistema.empresa_transporte.nuevaEmpresa')->render();
    }
}
