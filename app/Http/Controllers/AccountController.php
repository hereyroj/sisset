<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use Artesaos\Defender\Permission;
use App\dependencia;
use App\tramite;
use Session;
use Carbon\Carbon;
use Validator;
use Hash;
use App\User;

class AccountController extends Controller
{
    private function obtenerComplementos()
    {
        $roles = Role::with('hasPermisos')->orderBy('name', 'asc')->get();
        $permisos = Permission::orderBy('name', 'asc')->get();
        $dependencias = dependencia::pluck('name', 'id');
        $tramites = tramite::orderBy('name', 'asc')->get();

        return ['roles' => $roles, 'permisos' => $permisos, 'dependencias' => $dependencias, 'tramites' => $tramites];
    }

    public function viewProfile()
    {
        return view('admin.account.profile', $this->obtenerComplementos());
    }

    public function actualizarPerfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'dependencia' => 'required|integer|exists:dependencia,id',
            'avatar' => 'mimes:jpeg,jpeg,png|dimensions:min_height=300,min_width=300|mimetypes:image/jpeg,image/png',
        ], [
            'avatar.mimes' => 'El avatar suministrado no tiene una extension correcta: (jpg, jpeg, png)',
            'avatar.dimensions' => 'El avatar suministrado no tiene las dimensiones mínimas requeridas: (300px x 300px)',
            'avatar.mimetypes' => 'El avatar suministrado no es el tipo de archivo permitido: (imagen jpeg o png)',
            'name.required' => 'No se ha especificado un nombre de usuario.',
            'email.required' => 'No se ha especificado un correo electrónico.',
            'dependencia.required' => 'No se ha especificado una dependencia.',
            'email.email' => 'El correo suministrado no tiene un formato válido.',
            'dependencia.exist' => 'La dependencia especificada no existe en la base de datos.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($this->obtenerComplementos());
        } else {
            $errors = [];
            if (auth()->user()->hasRole('Administrador') && auth()->user()->puedeHacerlo('editar-usuario')) {
                $usuario = auth()->user();
                $usuario->name = title_case($request->name);
                $usuario->email = $request->email;
                $usuario->dependencia_id = $request->dependencia;
                if ($request->file('avatar') != null) {
                    $usuario->avatar = $request->file('avatar')->storeAs('avatars', $usuario->id.'.jpg');
                }
                $usuario->save();

                $usuario->syncRoles($request->roles);

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
            } else {
                $usuario = auth()->user();
                $usuario->name = title_case($request->name);
                $usuario->email = $request->email;
                $usuario->dependencia_id = $request->dependencia;
                if ($request->file('avatar') != null) {
                    $usuario->avatar = $request->file('avatar')->storeAs('avatars', $usuario->id.'.jpg');
                }
                $usuario->save();
            }

            if (count($errors) > 0) {
                Session::flash('errors', $errors);
            } else {
                Session::flash('terminado', 'Se han realizado los cambios correctamente.');
            }

            return back()->withInput($this->obtenerComplementos());
        }
    }

    public function changePassword(Request $request)
    {
        if (Auth::Check()) {
            $validator = Validator::make($request->all(), [
                'current-password' => 'required',
                'password' => 'required|same:password_confirmation',
                'password_confirmation' => 'required|same:password',
            ], [
                'current-password.required' => 'Debe ingresar la contraseña actual.',
                'password.required' => 'Debe ingresar la nueva contraseña.',
                'password.same' => 'No coinciden las contraseñas.',
                'password_confirmation.required' => 'Debe ingresar la confirmación de la nueva contraseña.',
                'password_confirmation.same' => 'No coinciden las contraseñas.',
            ]);

            if ($validator->fails()) {
                return response()->view('mensajes.errors', [
                    'errors' => $validator->errors()->all(),
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            } else {
                $request_data = $request->All();
                $current_password = Auth::User()->password;
                if (Hash::check($request_data['current-password'], $current_password)) {
                    $user_id = Auth::User()->id;
                    $obj_user = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['password']);
                    if ($obj_user->save()) {
                        $request->session()->regenerate();

                        return back()->with(['terminado' => 'Se ha cambiado la contraseña.']);
                    } else {
                        return back()->with(['errors' => ['No se ha podido realizar el cambio.']]);
                    }
                } else {
                    return back()->with(['errors' => ['La contraseña actual es incorrecta.']]);
                }
            }
        } else {
            return redirect()->to('admin/dashboard');
        }
    }

    public function setPIN(Request $request)
    {
        if (Auth::Check()) {
            $validator = Validator::make($request->all(), [
                'current-password' => 'required',
                'pin' => 'required|same:pin_confirmation|numeric',
                'pin_confirmation' => 'required|same:pin|numeric',
            ], [
                'current-password.required' => 'Debe ingresar la contraseña actual.',
                'pin.required' => 'Debe ingresar el PIN.',
                'pin.same' => 'Ambos PIN deben coincidir.',
                'pin.numeric' => 'El formato del PIN no es válido. Debe ser únicamente números.',
                'pin_confirmation.required' => 'Debe confirmar el PIN.',
                'pin_confirmation.same' => 'Ambos PIN deben coincidir.',
                'pin_confirmation.numeric' => 'El formato del PIN no es válido. Debe ser únicamente números.',
            ]);

            if ($validator->fails()) {
                return response()->view('mensajes.errors', [
                    'errors' => $validator->errors()->all(),
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            } else {
                $request_data = $request->All();
                $current_password = Auth::User()->password;
                if (Hash::check($request_data['current-password'], $current_password)) {
                    $user_id = Auth::User()->id;
                    $obj_user = User::find($user_id);
                    $obj_user->pin_code = Hash::make($request_data['pin']);
                    if ($obj_user->save()) {
                        $request->session()->regenerate();

                        return back()->with(['terminado' => 'Se ha establecido el PIN.']);
                    } else {
                        return back()->with(['errors' => ['No se ha podido establecer el PIN.']]);
                    }
                } else {
                    return back()->with(['errors' => ['La contraseña actual es incorrecta.']]);
                }
            }
        } else {
            return redirect()->to('admin/dashboard');
        }
    }

    public function changePIN(Request $request)
    {
        if (Auth::Check()) {
            $validator = Validator::make($request->all(), [
                'current-pin' => 'required|numeric',
                'pin' => 'required|same:pin_confirmation|numeric',
                'pin_confirmation' => 'required|same:pin|numeric',
            ], [
                'current-pin.required' => 'Debe ingresar la contraseña actual.',
                'pin.required' => 'Debe ingresar el nuevo PIN.',
                'pin.same' => 'Ambos PIN deben coincidir.',
                'pin.numeric' => 'El formato del PIN no es válido. Debe ser únicamente números.',
                'pin_confirmation.required' => 'Debe confirmar el nuevo PIN.',
                'pin_confirmation.same' => 'Ambos PIN deben coincidir.',
                'pin_confirmation.numeric' => 'El formato del PIN no es válido. Debe ser únicamente números.',
            ]);

            if ($validator->fails()) {
                return response()->view('mensajes.errors', [
                    'errors' => $validator->errors()->all(),
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            } else {
                $request_data = $request->All();
                $current_pin = Auth::User()->pin_code;
                if (Hash::check($request_data['current-pin'], $current_pin)) {
                    $user_id = Auth::User()->id;
                    $obj_user = User::find($user_id);
                    $obj_user->pin_code = Hash::make($request_data['pin']);
                    if ($obj_user->save()) {
                        $request->session()->regenerate();

                        return back()->with(['terminado' => 'Se ha establecido el nuevo PIN.']);
                    } else {
                        return back()->with(['errors' => ['No se ha podido establecer el nuevo PIN.']]);
                    }
                } else {
                    return back()->with(['errors' => ['El PIN actual es incorrecto.']]);
                }
            }
        } else {
            return redirect()->to('admin/dashboard');
        }
    }

    public function registrar2fa(Request $request)
    {
        $user = Auth::user();
        //$google2fa = app('pragmarx.google2fa');
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $code = $request->input('secret');
        $secret = $request->input('verify-code');
        $valid = $google2fa->verifyKey($code, $secret);
        if($valid){
            $user->google2fa_secret = $code;
            $user->save();
            return redirect()->to('admin/cuenta/activar2fa')->with('success',"La autenticación en dos pasos se ha activado.");
        }else{
            return redirect()->to('admin/cuenta/activar2fa')->with('error',"Código de verificación erroneo, Por favor intente nuevamente.");
        }
    }

    public function disable2fa(Request $request){
        if (!(Hash::check($request->get('password'), Auth::user()->password))) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Su contraseña no es correcta. Por favor intente nuevamente.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();
        return response()->view('admin.mensajes.success', [
            'mensaje' => 'Se ha desactivado la autenticación en dos pasos.',
            'encabezado' => '¡Completado!',
        ], 200);
    }

    public function activar2fa(Request $request)
    {
        $user = Auth::user();
        // Initialise the 2FA class
        /*$google2fa = app('pragmarx.google2fa');

        // Add the secret key to the registration data
        $google2faCode = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeUrl(
            'SISSET',
            $user->email,
            $google2faCode
        );*/

        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        $google2faCode =  $google2fa->generateSecretKey();

        $QR_Image = $google2fa->getQRCodeInline(
            'SISSET',
            $user->email,
            $google2faCode
        );

        // Pass the QR barcode image to our view
        return view('admin.account.activar2fa', ['QR_Image' => $QR_Image, 'secret' => $google2faCode]);
    }

    public function desactivar2fa()
    {
        return view('admin.account.desactivar2fa')->render();
    }

    public function desactivarU2f()
    {
        return view('admin.account.desactivarU2f')->render();
    }

    public function disableU2f(Request $request){
        if (!(Hash::check($request->get('password'), Auth::user()->password))) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Su contraseña no es correcta. Por favor intente nuevamente.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        $user = Auth::user();
        $user->hasU2f()->delete();
        return response()->view('admin.mensajes.success', [
            'mensaje' => 'Se ha desactivado la autenticación con llave USB.',
            'encabezado' => '¡Completado!',
        ], 200);
    }
}
