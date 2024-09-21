<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\usuario_tipo_documento;
use Validator;

class DocumentoIdentidadController extends Controller
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

        return view('admin.sistema.documento_identidad.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function nuevo()
    {
        return view('admin.sistema.documento_identidad.nuevoDocumento')->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:usuario_tipo_documento,name',
            'requiere_numero' => [Rule::in(['SI', 'NO']), 'required']
        ], [
            'name.required' => 'Debe suministrar un nombre al tramite.',
            'name.unique' => 'El nombre a registrar ya está en uso.',
            'requiere_numero.required' => 'No se ha especificado si el documento requiere un número.',
            'requiere_numero.in' => 'El valor especificado para el campo requiere_numero no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $documento = usuario_tipo_documento::create(['name' => $request->name, 'requiere_numero'=>$request->requiere_numero]);
            if ($documento != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el documento.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el documento. Si el problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Errores en la eliminación:',
                ], 200);
            }
        }
    }

    public function editar($id)
    {
        $documento = usuario_tipo_documento::find($id);

        return view('admin.sistema.documento_identidad.editarDocumento', ['documento' => $documento])->render();
    }

    public function actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:usuario_tipo_documento,id|integer',
            'name' => ['required', 'string', Rule::unique('usuario_tipo_documento', 'name')->ignore($request->id)],
            'requiere_numero' => [Rule::in(['SI', 'NO']), 'required']
        ], [
            'id.required' => 'No se ha especificado el ID del documento a actualizar.',
            'id.exists' => 'El ID del documento especificado no existe en el sistema.',
            'id.integer' => 'El ID del documento especificado no tiene un formato válido.',
            'name.required' => 'Debe suministrar un nombre al tramite.',
            'name.unique' => 'El nombre a registrar ya está en uso.',
            'requiere_numero.required' => 'No se ha especificado si el documento requiere un número.',
            'requiere_numero.in' => 'El valor especificado para el campo requiere_numero no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $documento = usuario_tipo_documento::find($request->id);
            $documento->name = $request->name;
            $documento->requiere_numero = $request->requiere_numero;
            if ($documento->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el documento.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el documento. Si el problema persiste por favor contacte a un administrador.'],
                    'encabezado' => 'Errores en la eliminación:',
                ], 200);
            }
        }
    }

    public function eliminar($id)
    {
        $documento = usuario_tipo_documento::find($id);
        if ($documento->delete()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el documento.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido eliminar el documento. Si el problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en la eliminación:',
            ], 200);
        }
    }

    public function obtenerDocumentos()
    {
        $documentos = usuario_tipo_documento::withTrashed()->get();

        return view('admin.sistema.documento_identidad.listadoDocumentos', ['documentos' => $documentos])->render();
    }

    public function activar($id)
    {
        $documento = usuario_tipo_documento::withTrashed()->find($id);
        if ($documento->restore()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha activador el documento.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido activar el documento. Si el problema persiste por favor contacte a un administrador.'],
                'encabezado' => 'Errores en activación:',
            ], 200);
        }
    }
}
