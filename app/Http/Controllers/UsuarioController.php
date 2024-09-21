<?php

namespace App\Http\Controllers;

use Artesaos\Defender\Permission;
use Illuminate\Support\Facades\Notification;
use App\Mail\actualizarUsuario;
use App\Mail\informarActualizacionUsuario;
use App\Notifications\DBActualizarUsuario;
use App\Notifications\DBInformarUsuarioActualizado;
use App\Notifications\DBNuevoUsuario;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\dependencia;
use App\Mail\bienvenida;
use App\Mail\creacionUsuario;
use App\tramite;
use App\User;
use App\user_agente;
use Validator;
use Artesaos\Defender\Facades\Defender;
use Hash;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\comparendo_entidad;

class UsuarioController extends Controller
{
    private function obtenerComplementos()
    {
        $roles = Role::with('hasPermisos')->orderBy('name', 'asc')->get();
        $permisos = Permission::orderBy('name', 'asc')->get();
        $dependencias = dependencia::orderBy('name', 'asc')->pluck('name', 'id');
        $tramites = tramite::orderBy('name', 'asc')->get();

        return ['roles' => $roles, 'permisos' => $permisos, 'dependencias' => $dependencias, 'tramites' => $tramites];
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'dependencia' => 'required|integer|exists:dependencia,id',
            'avatar' => 'mimes:jpeg,jpeg,png|dimensions:min_height=300,min_width=300|mimetypes:image/jpeg,image/png',
        ], [
            'avatar.mimes' => 'El avatar suministrado no tiene una extension correcta: (jpg, jpeg, png)',
            'avatar.dimensions' => 'El avatar suministrado no tiene las dimensiones mínimas requeridas: (300px x 300px)',
            'avatar.mimetypes' => 'El avatar suministrado no es el tipo de archivo permitido: (imagen jpeg o png)',
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'El correo proporcionado ya está en uso.',
            'dependencia.exist' => 'La dependencia no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($this->obtenerComplementos());
        } else {
            try{
                $usuario = new User();
                $usuario->name = title_case($request->name);
                $usuario->email = $request->email;
                $usuario->dependencia_id = $request->dependencia;
                $contraseñaUsuario = Hash::make(str_random(10));
                $usuario->password = bcrypt($contraseñaUsuario);
                $usuario->lock_session = 'no';
                $errors = [];
                $contador = 0;
                if ($usuario->save()) {
                    if ($request->file('avatar') != null) {
                        $usuario->avatar = $request->file('avatar')->storeAs('avatars', $usuario->id.'.jpg');
                        $usuario->save();
                    }
                    if (is_array($request->roles)) {
                        $usuario->syncRoles($request->roles);
                    }
                    if (is_Array($request->permisos)) {
                        $errors = [];
                        $contador = 0;
                        foreach ($request->permisos as $permiso) {
                            if (is_array($request->expira) && is_array($request->desactivar)) {
                                if (in_array($permiso, $request->desactivar) && in_array($permiso, $request->expira)) {
                                    $validator = Validator::make($request->all(), [
                                        'fecha'.$permiso.'_submit' => 'required|date_format:"Y-m-d"',
                                        'hora'.$permiso.'_submit' => 'required|date_format:"H:i"',
                                    ], [
                                        'fecha'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de fecha fue incorrecto.',
                                        'hora'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de hora fue incorrecto.',
                                    ]);

                                    if ($validator->fails()) {
                                        foreach ($validator->errors()->all() as $error) {
                                            $errors[$contador] = $error;
                                            $contador++;
                                        }
                                    } else {
                                        $usuario->detachPermission($permiso);
                                        $fecha = 'fecha'.$permiso->name.'_submit';
                                        $fecha = $request->input($fecha);
                                        $hora = 'hora'.$permiso->name.'_submit';
                                        $hora = $request->input($hora);
                                        $fecha_final = $fecha.' '.$hora;
                                        $usuario->attachPermission(Permission::where('name', $permiso)->first(), [
                                            'value' => false, // false means that he will not have the permission,
                                            'expires' => $fecha_final // Set the permission's expiration date
                                        ]);
                                    }
                                } elseif (in_array($permiso, $request->desactivar)) {//comprobamos si se le desactivo el permiso
                                    $usuario->attachPermission(Permission::where('name', $permiso)->first(), [
                                        'value' => false, // false means that he will not have the permission,
                                    ]);
                                } elseif (in_array($permiso, $request->expira)) {//comprobamos si se le asigno un tiempo de expiracion
                                    $validator = Validator::make($request->all(), [
                                        'fecha'.$permiso.'_submit' => 'required|date_format:"Y-m-d"',
                                        'hora'.$permiso.'_submit' => 'required|date_format:"H:i"',
                                    ], [
                                        'fecha'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de fecha fue incorrecto.',
                                        'hora'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de hora fue incorrecto.',
                                        'fecha'.$permiso.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque la fecha no fue suministrada.',
                                        'hora'.$permiso.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque la hora no fue suministrada.',
                                    ]);

                                    if ($validator->fails()) {
                                        foreach ($validator->errors()->all() as $error) {
                                            $errors[$contador] = $error;
                                            $contador++;
                                        }
                                    } else {
                                        $fecha = 'fecha'.$permiso->name.'_submit';
                                        $fecha = $request->input($fecha);
                                        $hora = 'hora'.$permiso->name.'_submit';
                                        $hora = $request->input($hora);
                                        $fecha_final = $fecha.' '.$hora;
                                        if (Carbon::now('America/Bogota')->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i', $fecha_final), false) <= 0) {
                                            $value = false;
                                        } else {
                                            $value = true;
                                        }
                                        $usuario->attachPermission($permiso, [
                                            'value' => $value,
                                            // false means that he will not have the permission,
                                            'expires' => Carbon::createFromFormat('Y-m-d H:i', $fecha_final)->toDateTimeString()
                                            // Set the permission's expiration date
                                        ]);
                                    }
                                }
                            } elseif (is_array($request->expira)) {
                                if (in_array($permiso, $request->expira)) {
                                    $validator = Validator::make($request->all(), [
                                        'fecha'.$permiso.'_submit' => 'required|date_format:"Y-m-d"',
                                        'hora'.$permiso.'_submit' => 'required|date_format:"H:i"',
                                    ], [
                                        'fecha'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de fecha fue incorrecto.',
                                        'hora'.$permiso.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque el formato de hora fue incorrecto.',
                                        'fecha'.$permiso.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque la fecha no fue suministrada.',
                                        'hora'.$permiso.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso.' porque la hora no fue suministrada.',
                                    ]);

                                    if ($validator->fails()) {
                                        foreach ($validator->errors()->all() as $error) {
                                            $errors[$contador] = $error;
                                            $contador++;
                                        }
                                    } else {
                                        $fecha = 'fecha'.$permiso->name.'_submit';
                                        $fecha = $request->input($fecha);
                                        $hora = 'hora'.$permiso->name.'_submit';
                                        $hora = $request->input($hora);
                                        $fecha_final = $fecha.' '.$hora;
                                        if (Carbon::now('America/Bogota')->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i', $fecha_final), false) <= 0) {
                                            $value = false;
                                        } else {
                                            $value = true;
                                        }
                                        $usuario->attachPermission($permiso, [
                                            'value' => $value,
                                            // false means that he will not have the permission,
                                            'expires' => Carbon::createFromFormat('Y-m-d H:i', $fecha_final)->toDateTimeString()
                                            // Set the permission's expiration date
                                        ]);
                                    }
                                }
                            } elseif (is_array($request->desactivar)) {
                                if (in_array($permiso->name, $request->desactivar)) {
                                    $usuario->attachPermission(Permission::where('name', $permiso)->first(), [
                                        'value' => false, // false means that he will not have the permission,
                                    ]);
                                }
                            }
                        }
                    }
                    $this->notitifcarCreacionUsuario($usuario->id);
                    $this->darBienvenidaUsuario($usuario->id, $contraseñaUsuario);
                    if (count($errors) > 0) {
                        Session::flash('errores', $errors);

                        return back();
                    } else {
                        Session::flash('terminado', 'Se ha creado el usuario correctamente.');

                        return redirect()->to('admin/sistema/usuarios/perfil/'.$usuario->id);
                    }
                } else {
                    Session::flash('errores', ['No se ha podido crear el usuarios en el sistemas. Por favor intente de nuevo y si el problema persiste contacte al administrador.']);

                    return back();
                }
            }catch (\Exception $e){
                
            }
        }
    }

    private function darBienvenidaUsuario($idUsuario, $password)
    {
        $usuario = User::with('hasRoles')->find($idUsuario);
        Mail::to($usuario)->send(new bienvenida($usuario, $password));
    }

    private function notitifcarCreacionUsuario($idUsuario)
    {
        $usuario = User::with('hasRoles')->find($idUsuario);
        $administradores = User::whereHas('hasRoles', function ($query) {
            $query->where('name', '=', 'Administrador');
        })->where('id', '!=', auth()->user()->id)->get();//obtenemos todos los usuarios con el rol Administrador para enviarles el correo de notificación
        foreach ($administradores as $administrador) {
            Notification::send($administrador, new DBNuevoUsuario($usuario));
            Mail::to($administrador)->send(new creacionUsuario($usuario, $administrador));
        }
    }

    public function administrar()
    {
        return view('admin.sistema.usuarios.administrar', $this->obtenerComplementos());
    }

    public function obtenerUsuarios()
    {
        if (\Defender::hasRole('Administrador')) {
            $usuarios = User::withTrashed()->with('hasRoles', 'couldHavePermisosAgregados', 'hasDependencia')->where('id', '!=', auth()->user()->id)->paginate(25);
        } else {
            $usuarios = User::whereHas('hasRoles', function ($query) {
                $query->where('name', '!=', 'Administrador');
            })->with('hasRoles', 'couldHavePermisosAgregados', 'hasDependencia')->where('id', '!=', auth()->user()->id)->paginate(25);
        }

        return view('admin.sistema.usuarios.listadoUsuarios', ['usuarios' => $usuarios]);
    }

    public function editar($id)
    {
        if ($id == auth()->user()->id) {
            return redirect()->to('admin/cuenta/perfil');
        }

        if (auth()->user()->hasRole('Administrador')) {
            $usuario = User::withTrashed('hasRoles', 'couldHavePermisosAgregados', 'hasDependencia')->find($id);
        } else {
            $usuario = User::with('hasRoles', 'couldHavePermisosAgregados', 'hasDependencia')->find($id);
        }

        if ($usuario == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El usuario a editar no esta disponible.'],
                'encabezado' => 'Error en el proceso',
            ], 200);
        } else {
            return view('admin.sistema.usuarios.editarUsuario', array_add($this->obtenerComplementos(), 'usuario', $usuario))->render();
        }
    }

    public function verPerfilUsuario($usuarioId)
    {
        if ($usuarioId == auth()->user()->id) {
            return redirect()->to('admin/cuenta/perfil');
        }

        if (\Defender::hasRole('Administrador')) {
            $usuario = User::withTrashed()->with('hasRoles', 'hasDependencia')->where('id', $usuarioId)->first();
        } else {
            $usuario = User::with('hasRoles', 'hasDependencia')->where('id', $usuarioId)->first();
        }

        return view('admin.sistema.usuarios.perfil', array_add($this->obtenerComplementos(), 'usuario', $usuario))->render();
    }

    /*
     * Método únicamente para administradores. No se permite cambiar las contraseñas desde este método. Para ello usar la opción ¿Olvidaste tu contraseña?, dispoible en el formulario de inicio de sesión
     */
    public function actualizarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
            'dependencia' => 'required|integer|exists:dependencia,id',
            'avatar' => 'mimes:jpeg,jpeg,png|dimensions:min_height=300,min_width=300|mimetypes:image/jpeg,image/png',
        ], [
            'id.exists' => 'No se ha encontrado un usuario con el ID suministrado.',
            'id.required' => 'No se ha proporcionado un ID de usuario.',
            'avatar.mimes' => 'El avatar suministrado no tiene una extension correcta: (jpg, jpeg, png)',
            'avatar.dimensions' => 'El avatar suministrado no tiene las dimensiones mínimas requeridas: (300px x 300px)',
            'avatar.mimetypes' => 'El avatar suministrado no es el tipo de archivo permitido: (imagen jpeg o png)',
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El correo suministrado no tiene un formato válido.',
            'dependencia.exist' => 'La dependencia proporcionada no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($this->obtenerComplementos());
        } else {
            if ($request->id != auth()->user()->id) {
                if (\Defender::hasRole('Administrador')) {
                    $usuario = User::withTrashed()->with('couldHavePermisosAgregados')->find($request->id);
                } else {
                    $usuario = User::with('couldHavePermisosAgregados')->find($request->id);
                }

                if ($usuario == null) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Tal vez no existe o no tienes los permisos suficientes para modificarlo.'],
                        'encabezado' => 'Error en el proceso',
                    ], 200);
                }

                $usuario->name = title_case($request->name);
                $usuario->email = $request->email;
                $usuario->dependencia_id = $request->dependencia;
                if ($request->file('avatar') != null) {
                    $usuario->avatar = $request->file('avatar')->storeAs('avatars', $usuario->id.'.jpg');
                }
                $usuario->save();

                if (is_array($request->roles)) {
                    $usuario->hasRoles()->detach();
                    $usuario->syncRoles($request->roles);
                }else{
                    $usuario->hasRoles()->detach();
                }

                //Asignación de permisos. Se comprueba primero si se seleccionaron permisos en el formulario
                $errors = [];//variable para controlar los errores que se presenten en el proceso de actualizar el usuario
                if (is_array($request->permisos)) {
                    $contador = 0;
                    $permisos = Permission::all();
                    foreach ($permisos as $permiso) {
                        if (! $usuario->havePermisoAgregado($permiso->id)) {
                            $value = true;
                            $expires = null;
                            if (in_array($permiso->name, $request->permisos)) {
                                if (is_array($request->desactivar)) {
                                    if (in_array($permiso->name, $request->desactivar)) {
                                        $value = false;
                                    }
                                }
                                if (is_array($request->expira)) {
                                    if (in_array($permiso->name, $request->expira)) {
                                        $validator = Validator::make($request->all(), [
                                            'fecha'.$permiso->name.'_submit' => 'required|date_format:"Y-m-d"',
                                            'hora'.$permiso->name.'_submit' => 'required|date_format:"H:i"',
                                        ], [
                                            'fecha'.$permiso->name.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso->name.' porque no se suministró una fecha válida.',
                                            'hora'.$permiso->name.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permiso->name.' porque no se suministró una hora válida.',
                                            'fecha'.$permiso->name.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso->name.' porque el formato de fecha fue incorrecto.',
                                            'hora'.$permiso->name.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permiso->name.' porque el formato de hora fue incorrecto.',
                                        ]);

                                        if ($validator->fails()) {
                                            foreach ($validator->errors()->all() as $error) {
                                                $errors[$contador] = $error;
                                                $contador++;
                                            }
                                        } else {
                                            $fecha = 'fecha'.$permiso->name.'_submit';
                                            $fecha = $request->input($fecha);
                                            $hora = 'hora'.$permiso->name.'_submit';
                                            $hora = $request->input($hora);
                                            $fecha_final = $fecha.' '.$hora.':00';
                                            $expires = $fecha_final;
                                        }
                                    }
                                }
                                /*
                                 * Esta validación tiene el siguiente propósito:
                                 * Si "value" es false y "expires" es false se se comprueba si el permiso no es heredado de un rol para evitar tener doble asignación.
                                 * De lo contrario se asigna el permiso con los debidos parámetros indicados en la interfaz.
                                 */
                                if ($value == true && $expires == null) {
                                    if (! $usuario->roleHasPermission($permiso->name)) {
                                        $usuario->attachPermission($permiso);
                                    }
                                } else {
                                    $usuario->attachPermission($permiso, ['value' => $value, 'expires' => $expires]);
                                }
                            }
                        } else {
                            /*
                             * Se comprueba si el permiso se le es retirado
                             */
                            if (! in_array($permiso->name, $request->permisos)) {
                                if ($usuario->roleHasPermission($permiso->name)) {
                                    $usuario->detachPermission($permiso);
                                    $usuario->attachPermission($permiso, ['value' => false]);
                                } else {
                                    $usuario->detachPermission($permiso);
                                }
                            } else {
                                /*
                                 * En caso de que no se le haya retirado se hará el siguiente procedimiento:
                                 * 1) Instancia del permiso actual asignado
                                 * 2) Asignación del estado "activo" -> true or false
                                 * 3) Asignación del estado "temporal" -> true or false | date and hour
                                 * 4) Validación y asignación del permiso al usuario
                                 */

                                /*(1)*/
                                $permisoAsignado = $usuario->getPermisoAgregado($permiso->id);
                                /*(2)*/
                                if (is_array($request->desactivar)) {
                                    if (in_array($permisoAsignado->name, $request->desactivar)) {
                                        $permisoAsignado->pivot->value = false;
                                    } else {
                                        $permisoAsignado->pivot->value = true;
                                    }
                                } else {
                                    $permisoAsignado->pivot->value = true;
                                }
                                /*(3)*/
                                if (is_array($request->expira)) {
                                    if (in_array($permisoAsignado->name, $request->expira)) {
                                        $validator = Validator::make($request->all(), [
                                            'fecha'.$permisoAsignado->name.'_submit' => 'required|date_format:"Y-m-d"',
                                            'hora'.$permisoAsignado->name.'_submit' => 'required|date_format:"H:i"',
                                        ], [
                                            'fecha'.$permisoAsignado->name.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permisoAsignado->name.' porque no se suministró una fecha válida.',
                                            'hora'.$permisoAsignado->name.'_submit.required' => 'No se activo la propiedad de expiración al permiso '.$permisoAsignado->name.' porque no se suministró una hora válida.',
                                            'fecha'.$permisoAsignado->name.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permisoAsignado->name.' porque el formato de fecha fue incorrecto.',
                                            'hora'.$permisoAsignado->name.'_submit.date_format' => 'No se activo la propiedad de expiración al permiso '.$permisoAsignado->name.' porque el formato de hora fue incorrecto.',
                                        ]);

                                        if ($validator->fails()) {
                                            foreach ($validator->errors()->all() as $error) {
                                                $errors[$contador] = $error;
                                                $contador++;
                                            }
                                        } else {
                                            $fecha = 'fecha'.$permiso->name.'_submit';
                                            $fecha = $request->input($fecha);
                                            $hora = 'hora'.$permiso->name.'_submit';
                                            $hora = $request->input($hora);
                                            $fecha_final = $fecha.' '.$hora.':00';
                                            $permisoAsignado->pivot->expires = $fecha_final;
                                        }
                                    } else {
                                        $permisoAsignado->pivot->expires = null;
                                    }
                                } else {
                                    $permisoAsignado->pivot->expires = null;
                                }
                                /*(4)*/

                                /*
                                 * ¡Importante!:
                                 * Se realizará la siguiente operación lógica para determinar la asignación o remoción del permiso:
                                 * si la propiedad "value" es true y la propiedad "expires" es null y el permiso es heredado de algún rol que tiene el usuario,
                                 * se determina que el permiso no tiene configuraciones especiales y se removerá de la relación usuario - permisos.
                                 * De lo contrario se actualizaran los cambios al objeto instanciado.
                                 */
                                if ($permisoAsignado->pivot->value == true && $permisoAsignado->pivot->expires == null && $usuario->roleHasPermission($permisoAsignado->name)) {
                                    $permisoAsignado->pivot->delete();
                                } else {
                                    $permisoAsignado->pivot->save();
                                }
                            }
                        }
                    }
                } else {
                    $usuario->deleteAllPermisos();
                }

                if (count($errors) > 0) {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => $errors,
                        'encabezado' => 'Error en el proceso',
                    ], 200);
                } else {
                    $this->notificarActualizarUsuario($usuario);
                    $this->informarUsuarioActualizado($usuario);

                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se han realizado los cambios correctamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No puede actualizar su cuenta a través de este medio.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }//Fin de la condicional de validación de datos
    }

    private function informarUsuarioActualizado(User $usuario)
    {
        Notification::send($usuario, new DBInformarUsuarioActualizado(auth()->user()));//Notificamos al usuario actualizado sobre los cambios a la cuenta
        Mail::to($usuario)->send(new informarActualizacionUsuario(auth()->user(), $usuario));
    }

    private function notificarActualizarUsuario($usuario)
    {
        $administradores = User::whereHas('hasRoles', function ($query) {
            $query->where('name', '=', 'Administrador');
        })->where('id', '!=', auth()->user()->id)->get();//obtenemos todos los usuarios con el rol Administrador excepto el actual, para enviarles el correo de notificación
        foreach ($administradores as $administrador) {
            Notification::send($administrador, new DBActualizarUsuario($usuario, auth()->user()));
            Mail::to($administrador)->send(new actualizarUsuario($usuario, $administrador));
        }
    }

    public function desactivar($id)
    {
        if ($id != auth()->user()->id) {
            $usuario = User::find($id);
            if ($usuario == null) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado el usuario.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            } else {
                $usuario->lock_session = 'yes';
                if ($usuario->save()) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha deshabilitado el usuario.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la acción.'],
                        'encabezado' => 'Errores en la solicitud:',
                    ], 200);
                }
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No puedes deshabilitar tu propia cuenta.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function activar($id)
    {
        if ($id != auth()->user()->id) {
            $usuario = User::withTrashed()->find($id);
            if ($usuario == null) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado el usuario.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            } else {
                $usuario->lock_session = 'no';
                if ($usuario->save()) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha activado el usuario.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la acción.'],
                        'encabezado' => 'Errores en la solicitud:',
                    ], 200);
                }
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No puedes activar tu propia cuenta.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function eliminar($id)
    {
        if ($id != auth()->user()->id) {
            $usuario = User::find($id);
            if ($usuario == null) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado el usuario.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            } else {
                if ($usuario->delete()) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha eliminado el usuario.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la acción.'],
                        'encabezado' => 'Errores en la solicitud:',
                    ], 200);
                }
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No puedes eliminar tu propia cuenta.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function restaurar($id)
    {
        if ($id != auth()->user()->id) {
            $usuario = User::withTrashed()->find($id);
            if ($usuario == null) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha encontrado el usuario.'],
                    'encabezado' => 'Errores en la solicitud:',
                ], 200);
            } else {
                $usuario->restore();
                $usuario->save();
                if (! $usuario->trashed()) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha restaurado el usuario.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido realizar la acción.'],
                        'encabezado' => 'Errores en la solicitud:',
                    ], 200);
                }
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No puedes restaurar tu propia cuenta.'],
                'encabezado' => 'Errores en la solicitud:',
            ], 200);
        }
    }

    public function ultimosUsuariosCreados()
    {
        $ultimosUsuariosCreados = User::with('hasRoles')->orderBy('created_at', 'desc')->take(3)->get();

        return $ultimosUsuariosCreados;
    }

    public function nuevo()
    {
        return view('admin.sistema.usuarios.nuevoUsuario', $this->obtenerComplementos())->render();
    }

    public function convertirEnAgente($id)
    {
        $entidades = comparendo_entidad::pluck('name','id');
        return view('admin.sistema.usuarios.convertirEnAgente', ['id'=>$id,'entidades'=>$entidades])->render();
    }

    public function registrarAgente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuarioId' => 'required|integer|exists:users,id',
            'placa' => 'required|string',
            'fecha_vinculacion_submit' => 'required|date',
            'entidad' => 'required|integer|exists:comparendo_entidad,id'
        ], [
            'usuarioId.required' => 'No se ha especificado el ID del usuario a convertir en Agente.',
            'usuarioId.integer' => 'El ID del usuario especificado no tiene un formato válido.',
            'usuarioId.exists' => 'El ID del usuario especificado no existe en el sistema.',
            'placa.required' => 'No se ha especificado la placa del agente.',
            'placa.string' => 'La placa del agente especificada no tiene un formato válido.',
            'fecha_vinculacion_submit.required' => 'No se ha especificado la fecha de vinculación del agente.',
            'fecha_vinculacion_submit.date' => 'La fecha de vinculación del agente especificada no tiene un formato válido.'

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                user_agente::create([
                    'placa' => $request->placa,
                    'fecha_ingreso' => $request->fecha_vinculacion_submit,
                    'user_id' => $request->usuarioId,
                    'estado' => 1,
                    'comparendo_entidad_id' => $request->entidad,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha vinculado el usuario como agente correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la acción. Si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function verAgente($id)
    {
        $usuario = User::with('hasAgente')->find($id);
        return view('admin.sistema.usuarios.verAgente', ['agente'=>$usuario->hasAgente])->render();
    }

    public function desvincularAgente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuarioId' => 'required|integer|exists:user_agente,user_id',
            'fecha_desvinculacion_submit' => 'required|date'
        ], [
            'usuarioId.required' => 'No se ha especificado el ID del Agente a desvincular.',
            'usuarioId.integer' => 'El ID del Agente especificado no tiene un formato válido.',
            'usuarioId.exists' => 'El ID del Agente especificado no tiene un registro como Agente.',
            'fecha_desvinculacion_submit.required' => 'No se ha especificado la fecha de desvinculación del agente.',
            'fecha_desvinculacion_submit.date' => 'La fecha de desvinculación del agente especificada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $usuario = User::with('hasAgente')->find($request->usuarioId);
                $usuario->hasAgente->estado = 0;
                $usuario->hasAgente->fecha_retiro = $request->fecha_desvinculacion_submit;
                $usuario->hasAgente->save();
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha desvinculado el usuario como agente correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido realizar la acción. Si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }
}
