<?php

namespace App\Http\Controllers;

use Artesaos\Defender\Permission;
use Illuminate\Http\Request;
use App\Role;
use Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
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

        return view('admin.sistema.roles.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function obtenerRoles()
    {
        $roles = Role::with('hasPermisos')->paginate(10);

        return view('admin.sistema.roles.listadoRoles', ['roles' => $roles])->render();
    }

    public function editar($id)
    {
        $rol = Role::with('hasPermisos')->find($id);
        $permisos = Permission::orderBy('readable_name', 'asc')->get();

        return view('admin.sistema.roles.editarRol', ['rol' => $rol, 'permisos' => $permisos])->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permisos' => 'required|array',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.unique' => 'El nombre proporcionado a está en uso.',
            'name.string' => 'El nombre especificado no tiene un formato vállido.',
            'permisos.required' => 'Se debe seleccionar al menos un permiso.',
            'permisos.array' => 'El formato de los permisos no es válido',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $role = Role::firstOrCreate(['name' => $request->name]);
            if (is_array($request->permisos)) {
                foreach ($request->permisos as $permiso) {
                    $role->attachPermission(Permission::find($permiso));
                }
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el rol en el sistema.',
                'encabezado' => '¡Completado!',
            ], 200);
        }
    }

    public function guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', Rule::unique('roles')->ignore($request->id, 'id')],
            'permisos' => 'required|array',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.unique' => 'El nombre especificado ya está en uso.',
            'permisos.required' => 'Se debe seleccionar al menos un permiso.',
            'permisos.array' => 'El formato de los permisos no es válido',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $role = Role::find($request->id);
            $role->name = $request->name;
            if ($role->save()) {
                if (is_array($request->permisos)) {
                    $role->syncPermissions($request->permisos);
                }

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el rol.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'mensaje' => 'No se ha podido actualizar el Rol. Si el problema persiste, por favor contacte a un administrador.',
                    'encabezado' => 'Errores en la actualización:',
                ], 200);
            }
        }
    }

    public function nuevoRol()
    {
        $permisos = Permission::orderBy('readable_name', 'asc')->get();

        return view('admin.sistema.roles.nuevoRol', ['permisos' => $permisos])->render();
    }
}
