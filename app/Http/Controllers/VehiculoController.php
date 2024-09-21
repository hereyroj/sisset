<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\departamento;
use App\empresa_transporte;
use App\usuario_tipo_documento;
use App\vehiculo;
use App\vehiculo_clase_has_lt;
use App\vehiculo_linea;
use App\vehiculo_marca;
use App\vehiculo_clase;
use App\vehiculo_carroceria;
use App\vehiculo_combustible;
use App\vehiculo_nivel_servicio;
use App\vehiculo_propietario;
use App\vehiculo_radio_operacion;
use App\vehiculo_servicio;
use Validator;
use App\vehiculo_clase_letra_terminacion;
use App\vehiculo_bateria_tipo;


class VehiculoController extends Controller
{
    public function administrar()
    {
        $letras_terminacion = vehiculo_clase_letra_terminacion::all();

        return view('admin.tramites.vehiculos.administrar', ['letras_terminacion' => $letras_terminacion]);
    }

    public function editarClase($id)
    {
        $letras_terminacion = vehiculo_clase_letra_terminacion::all();
        $clase = vehiculo_clase::withTrashed()->find($id);

        return view('admin.tramites.vehiculos.editarClase', [
            'clase' => $clase,
            'letras_terminacion' => $letras_terminacion,
        ])->render();
    }

    public function editarMarca($id)
    {
        $marca = vehiculo_marca::withTrashed()->find($id);

        return view('admin.tramites.vehiculos.editarMarca', ['marca' => $marca])->render();
    }

    public function editarCarroceria($id)
    {
        $carroceria = vehiculo_carroceria::withTrashed()->find($id);

        return view('admin.tramites.vehiculos.editarCarroceria', ['carroceria' => $carroceria])->render();
    }

    public function editarCombustible($id)
    {
        $combustible = vehiculo_combustible::withTrashed()->find($id);

        return view('admin.tramites.vehiculos.editarCombustible', ['combustible' => $combustible])->render();
    }

    public function nuevaClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreClase' => 'required|string|unique:vehiculo_clase,name',
            'lst' => 'array|required_if:requiereLetra,yes',
            'requiereLetra' => 'required|string',
            'pre_asignable' => ['required',Rule::in(['SI','NO'])]
        ], [
            'nombreClase.required' => 'El nombre del clase del vehículo es obligatorio.',
            'nombreClase.string' => 'El formato del nombre proporcionado no es válido',
            'nombreClase.unique' => 'El nombre ya existe en la base de datos',
            'lst.array' => 'El formato de las letras de teminación no son válidas',
            'requiereLetra.required' => 'Debe especificar si la clase requiere una letra en el final de la placa.',
            'lst.required_if' => 'Se ha indicado que la clase requiere tener letras al final de la placa, pero no se ha especificado letra alguna.',
            'pre_asignable.required' => 'El valor para el campo Pre-asignable es obligatorio.',
            'pre_asignable.in' => 'El valor especificado para el campo Pre-asignable no tiene un valor válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $clase = new vehiculo_clase();
            $clase->name = strtoupper($request->nombreClase);
            $clase->required_letter = $request->requiereLetra;
            $clase->pre_asignable = $request->pre_asignable;
            if ($clase->save()) {
                if ($request->requiereLetra == 'yes') {
                    foreach ($request->lst as $lt) {
                        $clase->hasLetrasTerminacion()->attach([
                            'letra_terminacion_id' => $lt,
                            'vehiculo_clase_id' => $clase->id,
                        ]);
                    }
                }

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el clase de vehículo.',
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

    public function nuevaMarca(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreMarca' => 'required|string|unique:vehiculo_marca,name',
        ], [
            'required' => 'El nombre de la marca es obligatorio.',
            'string' => 'El formato del nombre proporcionado no es válido',
            'unique' => 'El nombre ya existe en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $marca = new vehiculo_marca();
            $marca->name = strtoupper($request->nombreMarca);
            if ($marca->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la marca.',
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

    public function nuevoCombustible(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreCombustible' => 'required|string|unique:vehiculo_combustible,name',
        ], [
            'required' => 'El nombre del combustible es obligatorio.',
            'string' => 'El formato del nombre proporcionado no es válido',
            'unique' => 'El nombre ya existe en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $combustible = new vehiculo_combustible();
            $combustible->name = strtoupper($request->nombreCombustible);
            if ($combustible->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el combustible.',
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

    public function nuevaCarroceria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreCarroceria' => 'required|string|unique:vehiculo_carroceria,name',
        ], [
            'required' => 'El nombre de la carroceria es obligatorio.',
            'string' => 'El formato del nombre proporcionado no es válido',
            'unique' => 'El nombre ya existe en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $carroceria = new vehiculo_carroceria();
            $carroceria->name = strtoupper($request->nombreCarroceria);
            if ($carroceria->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el clase de carroceria.',
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

    public function obtenerCarrocerias()
    {
        if (\Defender::hasRole('Administrador')) {
            $carrocerias = vehiculo_carroceria::withTrashed()->orderBy('name', 'asc')->paginate(15);
        } else {
            $carrocerias = vehiculo_carroceria::orderBy('name', 'asc')->paginate(15);
        }

        return view('admin.tramites.vehiculos.listadoCarrocerias', ['carrocerias' => $carrocerias])->render();
    }

    public function obtenerClases()
    {
        if (\Defender::hasRole('Administrador')) {
            $clases = vehiculo_clase::withTrashed()->orderBy('name', 'asc')->paginate(15);
        } else {
            $clases = vehiculo_clase::orderBy('name', 'asc')->paginate(15);
        }

        return view('admin.tramites.vehiculos.listadoClases', ['clases' => $clases])->render();
    }

    public function obtenerCombustibles()
    {
        if (\Defender::hasRole('Administrador')) {
            $combustibles = vehiculo_combustible::withTrashed()->orderBy('name', 'asc')->paginate(12);
        } else {
            $combustibles = vehiculo_combustible::orderBy('name', 'asc')->paginate(12);
        }

        return view('admin.tramites.vehiculos.listadoCombustibles', ['combustibles' => $combustibles])->render();
    }

    public function obtenerMarcas()
    {
        if (\Defender::hasRole('Administrador')) {
            $marcas = vehiculo_marca::withTrashed()->orderBy('name', 'asc')->paginate(15);
        } else {
            $marcas = vehiculo_marca::orderBy('name', 'asc')->paginate(51);
        }

        return view('admin.tramites.vehiculos.listadoMarcas', ['marcas' => $marcas])->render();
    }

    public function actualizarClase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idClase' => 'required|exists:vehiculo_clase,id|integer',
            'nombreClase' => ['required', 'string', Rule::unique('vehiculo_clase', 'name')->ignore($request->idClase)],
            'lst' => 'array|required_if:requiereLetra,yes',
            'requiereLetra' => 'required|string',
            'pre_asignable' => ['required',Rule::in(['SI','NO'])]
        ], [
            'idClase.required' => 'No se ha especificado una clase.',
            'idClase.integer' => 'El ID de la clase no tiene un formato válido',
            'idClase.exists' => 'El ID de la clase no esta registrada en el sistemas',
            'nombreClase.required' => 'El nombre del clase del vehículo es obligatorio.',
            'nombreClase.string' => 'El formato del nombre proporcionado no es válido',
            'nombreClase.unique' => 'El nombre ya existe en la base de datos',
            'lst.array' => 'El formato de las letras de teminación no son válidas',
            'requiereLetra.required' => 'Debe especificar si la clase requiere una letra en el final de la placa.',
            'requiereLetra.string' => 'El formato del campo "Requiere letra de terminación", no tiene un formato válido.',
            'lst.required_if' => 'Se ha indicado que la clase requiere tener letras al final de la placa, pero no se ha suministrado letra alguna.',
            'pre_asignable.required' => 'El valor para el campo Pre-asignable es obligatorio.',
            'pre_asignable.in' => 'El valor especificado para el campo Pre-asignable no tiene un valor válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $clase = vehiculo_clase::withTrashed()->find($request->idClase);
            $clase->name = strtoupper($request->nombreClase);
            $clase->required_letter = $request->requiereLetra;
            $clase->pre_asignable = $request->pre_asignable;
            if ($clase->save()) {
                $clase->hasLetrasTerminacion()->detach();
                if ($request->requiereLetra == 'yes') {
                    foreach ($request->lst as $lt) {
                        $clase->hasLetrasTerminacion()->attach($lt ,[
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el clase de vehículo.',
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

    public function actualizarMarca(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idMarca' => 'required|integer|exists:vehiculo_marca,id',
            'nombreMarca' => ['required', 'string', Rule::unique('vehiculo_marca', 'name')->ignore($request->idMarca)],
        ], [
            'nombreMarca.required' => 'El nombre de la marca es obligatorio.',
            'nombreMarca.string' => 'El formato del nombre proporcionado no es válido',
            'nombreMarca.unique' => 'El nombre ya existe en la base de datos',
            'idMarca.integer' => 'El formato del Id de la marca no es válido',
            'idMarca.required' => 'No se suministrós un Id correcto',
            'idMarca.exists' => 'El Id de la marca no está registrado en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $marca = vehiculo_marca::find($request->idMarca);
            $marca->name = strtoupper($request->nombreMarca);
            if ($marca->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la marca.',
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

    public function actualizarCombustible(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCombustible' => 'required|integer|exists:vehiculo_combustible,id',
            'nombreCombustible' => [
                'required',
                'string',
                Rule::unique('vehiculo_combustible', 'name')->ignore($request->idCombustible),
            ],
        ], [
            'nombreCombustible.required' => 'El nombre de la combustible es obligatorio.',
            'nombreCombustible.string' => 'El formato del nombre proporcionado no es válido',
            'nombreCombustible.unique' => 'El nombre ya existe en la base de datos',
            'idCombustible.integer' => 'El formato del Id de la combustible no es válido',
            'idCombustible.required' => 'No se suministrós un Id correcto',
            'idCombustible.exists' => 'El Id de la combustible no está registrado en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $combustible = vehiculo_combustible::withTrashed()->find($request->idCombustible);
            $combustible->name = strtoupper($request->nombreCombustible);
            if ($combustible->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el combustible.',
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

    public function actualizarCarroceria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCarroceria' => 'required|integer|exists:vehiculo_carroceria,id',
            'nombreCarroceria' => [
                'required',
                'string',
                Rule::unique('vehiculo_carroceria', 'name')->ignore($request->idCarroceria),
            ],
        ], [
            'nombreCarroceria.required' => 'El nombre de la carroceria es obligatorio.',
            'nombreCarroceria.string' => 'El formato del nombre proporcionado no es válido',
            'nombreCarroceria.unique' => 'El nombre ya existe en la base de datos',
            'idCarroceria.integer' => 'El formato del Id de la carroceria no es válido',
            'idCarroceria.required' => 'No se suministrós un Id correcto',
            'idCarroceria.exists' => 'El Id de la carroceria no está registrado en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $carroceria = vehiculo_carroceria::withTrashed()->find($request->idCarroceria);
            $carroceria->name = strtoupper($request->nombreCarroceria);
            if ($carroceria->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el clase de carroceria.',
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

    public function eliminarMarca($id)
    {
        $marca = vehiculo_marca::find($id);
        if ($marca == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La marca especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $marca->delete();
            if ($marca->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado la marca.',
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

    public function restaurarMarca($id)
    {
        $marca = vehiculo_marca::withTrashed()->find($id);
        if ($marca == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La marca especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $marca->restore();
            if ($marca->trashed()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado la marca.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
    }

    public function eliminarClase($id)
    {
        $clase = vehiculo_clase::find($id);
        if ($clase == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La clas especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $clase->delete();
            if ($clase->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el clase de vehículo.',
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

    public function restaurarClase($id)
    {
        $clase = vehiculo_clase::withTrashed()->find($id);
        if ($clase == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La clas especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $clase->restore();
            if ($clase->trashed()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado el clase de vehículo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
    }

    public function obtenerLetrasClaseVehiculo($id)
    {
        $claseLetras = vehiculo_clase::where('id', $id)->with('obtenerLetras')->first();

        return $claseLetras->obtenerLetras;
    }

    public function eliminarCombustible($id)
    {
        $combustible = vehiculo_combustible::find($id);
        if ($combustible == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El combustible especificado no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $combustible->delete();
            if ($combustible->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el combustible de vehículo.',
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

    public function restaurarCombustible($id)
    {
        $combustible = vehiculo_combustible::withTrashed()->find($id);
        if ($combustible == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El combustible especificado no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $combustible->restore();
            if ($combustible->trashed()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado el combustible de vehículo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
    }

    public function eliminarCarroceria($id)
    {
        $carroceria = vehiculo_carroceria::find($id);
        if ($carroceria == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carrocería especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $carroceria->delete();
            if ($carroceria->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el carroceria de vehículo.',
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

    public function restaurarCarroceria($id)
    {
        $carroceria = vehiculo_carroceria::withTrashed()->find($id);
        if ($carroceria == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carrocería especificada no existe..'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $carroceria->restore();
            if ($carroceria->trashed()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado el carroceria de vehículo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
    }

    public function crearMarca()
    {
        return view('admin.tramites.vehiculos.nuevaMarca')->render();
    }

    public function crearClase()
    {
        $letras_terminacion = vehiculo_clase_letra_terminacion::all();

        return view('admin.tramites.vehiculos.nuevaClase', ['letras_terminacion' => $letras_terminacion])->render();
    }

    public function crearCarroceria()
    {
        return view('admin.tramites.vehiculos.nuevaCarroceria')->render();
    }

    public function crearCombustible()
    {
        return view('admin.tramites.vehiculos.nuevoCombustible')->render();
    }

    public function obtenerVehiculos()
    {
        $filtros = [
            '1' => 'Placa',
            '2' => 'Motor',
            '3' => 'Chasis',
            '4' => 'Razón social',
            '5' => 'Número interno',
            '6' => 'Doc. Propietario'
        ];
        $sFiltro = null;
        $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.tramites.vehiculos.listadoVehiculos', ['vehiculos'=>$vehiculos,'filtros'=>$filtros,'sFiltro'=>$sFiltro])->render();
    }

    public function nuevoVehiculo()
    {
        $carrocerias = vehiculo_carroceria::orderBy('name')->pluck('name', 'id');
        $combustibles = vehiculo_combustible::orderBy('name')->pluck('name', 'id');
        $clases = vehiculo_clase::orderBy('name')->pluck('name', 'id');
        $marcas = vehiculo_marca::orderBy('name')->pluck('name', 'id');
        $tiposBaterias = vehiculo_bateria_tipo::orderBy('name')->pluck('name', 'id');
        $tiposDocumentos = usuario_tipo_documento::orderBy('name')->pluck('name', 'id');
        $departamentos = departamento::orderBy('name')->pluck('name', 'id');

        return view('admin.tramites.vehiculos.nuevoVehiculo', ['carrocerias'=>$carrocerias, 'combustibles'=>$combustibles, 'clases'=>$clases, 'marcas' => $marcas, 'tiposBaterias'=>$tiposBaterias, 'tiposDocumentosIdentidad' => $tiposDocumentos, 'departamentos' => $departamentos])->render();
    }

    public function crearVehiculo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placa' => 'required|max:8|string|min:6|unique:vehiculo,placa',
            'tipoVehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'tipoCarroceria' => 'required|integer|exists:vehiculo_carroceria,id',
            'marcaVehiculo' => 'required|integer|exists:vehiculo_marca,id',
            'lineaVehiculo' => 'required|integer|exists:vehiculo_linea,id',
            'modeloVehiculo' => 'required|numeric',
            'claseCombustible' => 'required|integer|exists:vehiculo_combustible,id',
            'numeroMotor' => 'required|string|unique:vehiculo,numero_motor',
            'numeroChasis' => 'required|string|unique:vehiculo,numero_chasis',
            'capacidadPasajeros' => 'required|integer',
            'capacidadToneladas' => 'required|numeric',
            'tipoBateria' => 'nullable|integer|exists:vehiculo_bateria_tipo,id',
            'bateriaCapacidad' => 'nullable|numeric',
            'colorVehiculo' => 'required|string',
            'puertasVehiculo' => 'required|numeric',
            'tipo_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'numero_documento' => 'required|numeric',
            'telefono' => 'required|numeric',
            'departamento' => 'required|integer|exists:departamento,id',
            'municipio' => 'required|integer|exists:municipio,id',
            'direccion' => 'required|string',
            'nombre' => 'required|string'
        ], [
            'placa.unique' => 'La placa especificada ya está registrada en el sistema.',
            'placa.required' => 'No se ha especificado una placa.',
            'placa.max' => 'La placa debe tener un máximo de :max caracteres.',
            'placa.min' => 'La placa debe tener un mínimo de :min caracteres.',
            'tipoVehiculo.required' => 'No se ha especificado el tipo de vehículo.',
            'tipoVehiculo.integer' => 'El ID del tipo de vehículo especificado no tiene un formato válido.',
            'tipoVehiculo.exists' => 'El tipo de vehículo espécificado no existe en la base de datos.',
            'tipoCarroceria.required' => 'No se ha especificado el tipo de carrocería.',
            'tipoCarroceria.integer' => 'El ID del tipo de carrocería especificada no existe en la base de datos.',
            'tipoCarroceria.exists' => 'El tipo de carrocería especificada no existe en la base de datos.',
            'marcaVehiculo.required' => 'No se ha especificado la marca del vehículo.',
            'marcaVehiculo.integer' => 'El ID de la marca del vehículo no tiene un formato válido.',
            'marcaVehiculo.exists' => 'La marca de vehículo especificada no existe en la base de datos.',
            'lineaVehiculo.required' => 'No se ha especificado la línea del vehículo.',
            'lineaVehiculo.integer' => 'El ID de la línea del vehículo especificada no tiene un formato válido.',
            'lineaVehiculo.exists' => 'La línea del vehículo especificada no existe en el sistema.',
            'claseCombustible.required' => 'No se ha especificado la clase de combustible.',
            'claseCombustible.integer' => 'El ID de la clase de combustible no tiene un formato válido.',
            'claseCombustible.exists' => 'La clase de combustible especificada no existe en la base de datos.',
            'modeloVehiculo.required' => 'No se ha especificado el modelo del vehículo.',
            'modeloVehiculo.numeric' => 'El modelo del vehículo especificado no tiene un formato válido.',
            'capacidadPasajeros.required' => 'No se ha especificado la capacidad de asajeros del vehículo.',
            'capacidadPasajeros.integer' => 'La capacidad de pasajeros especificada no tiene un formato válido.',
            'capacidadPasajeros.required_without_all' => 'No se ha especificado la capacidad de pasajeros o toneladas. Se debe especificar alguna de ellas.',
            'capacidadToneladas.required' => 'No se ha espécificado la capacidad de carga del vehículo.',
            'capacidadToneladas.numeric' => 'La capacidad de toneladas especificada no tiene un formato válido.',
            'capacidadToneladas.required_without_all' => 'No se ha especificado la capacidad de toneladas o pasajeros. Se debe especificar alguna de ellas.',
            'numeroMotor.required' => 'No se ha especificado un número de motor.',
            'numeroMotor.unique' => 'El número de motor especificado ya está en uso.',
            'numeroMotor.string' => 'El número de motor especificado no tiene un formato válido.',
            'numeroChasis.required' => 'No se ha especificado un número de chasis.',
            'numeroChasis.unique' => 'El número de chasis especificado ya está en uso.',
            'numeroChasis.string' => 'El número de chasis especificado no tiene un formato válido.',
            'colorVehiculo.required' => 'No se ha especificado el color.',
            'colorVehiculo.string' => 'El color especificado no es válido.',
            'puertasVehiculo.required' => 'No se han especificado la cantidad de puertas del vehículo.',
            'puertasVehiculo.numeric' => 'El valor especificado para la cantidad de puertas del vehículo no tiene un formato válido.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identidad del propietario.',
            'tipo_documento.integer' => 'El ID del tipo de documento de identidad del propietario especificado no tiene un formato válido.',
            'tipo_documento.exists' => 'El tipo de documento de identidad del propietario especificado no existe en el sistema.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad del propietario.',
            'numero_documento.numeric' => 'El número de documento de identidad del propietario especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el número de teléfono del propietario.',
            'telefono.numeric' => 'El número de teléfono del propietario especificado no tiene un formato válido.',
            'departamento.required' => 'No se ha especificado el departamento de residencia  del propietario.',
            'departamento.integer' => 'El ID del departamento de residencia del propietario especificado no tiene un formato válido.',
            'departamento.exists' => 'El departamento de residencia del propietario especificado no existe en el sistema.',
            'municipio.required' => 'No se ha especificado el municipio de residencia del propietario.',
            'municipio.integer' => 'El ID del municipio de residencia del propietario especificado no tiene un formato válido.',
            'municipio.exists' => 'El municipio de residencia del propietario especificado no existe en el sistema.',
            'direccion.required' => 'No se ha especificado la dirección deresidencia del propietario .',
            'direccion.string' => 'La dirección de residencia del propietario especificada no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado el nombre del propietario.',
            'nombre.string' => 'El nombre del propietario especificado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!'
            ], 200);
        }
        $claseVehiculo = vehiculo_clase::find($request->tipoVehiculo); 
        $tipoBateria = null;
        $capacidadBateria = null;
        if ($claseVehiculo->name == 'ELECTRICO') {
            if($request->tipoBateria == null || $request->bateriaCapacidad == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se han especificado correctamente los parámetros de Tipo batería y Capacidad batería para esta clase de vehículo Eléctrico.'],
                    'encabezado' => '¡Errores!'
                ], 200);
            }else{
                $tipoBateria = $request->tipoBateria;
                $capacidadBateria = $request->bateriaCapacidad;
            }
        }else{
            try{
                $vehiculo = vehiculo::create([
                    'numero_motor' => strtoupper($request->numeroMotor),
                    'numero_chasis' => strtoupper($request->numeroChasis),
                    'placa' => strtoupper($request->placa),
                    'modelo' => $request->modeloVehiculo,
                    'capacidad_pasajeros' => $request->capacidadPasajeros,
                    'capacidad_toneladas' => $request->capacidadToneladas,
                    'vehiculo_clase_id' => $request->tipoVehiculo,
                    'vehiculo_carroceria_id' => $request->tipoCarroceria,
                    'vehiculo_marca_id' => $request->marcaVehiculo,
                    'vehiculo_linea_id' => $request->lineaVehiculo,
                    'vehiculo_combustible_id' => $request->claseCombustible,
                    'vehiculo_bateria_tipo_id' => $tipoBateria,
                    'bateria_capacidad_watts' => $capacidadBateria,
                    'color' => $request->colorVehiculo,
                    'puertas' => $request->puertasVehiculo,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $propietario = vehiculo_propietario::where('tipo_documento_id', $request->tipo_documento)->where('numero_documento', $request->numero_documento)->first();
                if($propietario != null){
                    $propietario->hasVehiculos()->attach($vehiculo->id, ['estado'=>1]);
                }else{
                    $propietario = vehiculo_propietario::create([
                        'nombre' => strtoupper($request->nombre),
                        'numero_documento' => $request->numero_documento,
                        'tipo_documento_id' => $request->tipo_documento,
                        'telefono' => $request->telefono,
                        'correo_electronico' => $request->correo,
                        'departamento_id' => $request->departamento,
                        'municipio_id' => $request->municipio,
                        'direccion' => strtoupper($request->direccion)
                    ]);
                    $propietario->hasVehiculos()->attach($vehiculo->id, ['estado'=>1]);
                }

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el vehículo y se ha vinculado el propietario exitosamente.',
                    'encabezado' => 'Completado!',
                ], 200);
            }catch (\Exception $e){
                echo $e->getMessage();
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido crear el vehículo. Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => '¡Error!'
                ], 200);
            }
        }
    }

    public function obtenerVehiculo($id)
    {
        $vehiculo = vehiculo::find($id);
        $carrocerias = vehiculo_carroceria::orderBy('name')->pluck('name', 'id');
        $combustibles = vehiculo_combustible::orderBy('name')->pluck('name', 'id');
        $clases = vehiculo_clase::orderBy('name')->pluck('name', 'id');
        $marcas = vehiculo_marca::orderBy('name')->pluck('name', 'id');
        $tiposBaterias = vehiculo_bateria_tipo::orderBy('name')->pluck('name', 'id');

        return view('admin.tramites.vehiculos.editarVehiculo', ['carrocerias'=>$carrocerias, 'combustibles'=>$combustibles, 'clases'=>$clases, 'marcas' => $marcas, 'vehiculo'=>$vehiculo, 'tiposBaterias'=>$tiposBaterias])->render();
    }

    public function guardarCambiosVehiculo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehiculo_id' => 'required|integer|exists:vehiculo,id',
            'placa' => ['required','max:8','string','min:6',Rule::unique('vehiculo', 'placa')->ignore($request->vehiculo_id)],
            'tipoVehiculo' => 'required|integer|exists:vehiculo_clase,id',
            'tipoCarroceria' => 'required|integer|exists:vehiculo_carroceria,id',
            'marcaVehiculo' => 'required|integer|exists:vehiculo_marca,id',
            'lineaVehiculo' => 'required|integer|exists:vehiculo_linea,id',
            'modeloVehiculo' => 'required|numeric',
            'claseCombustible' => 'required|integer|exists:vehiculo_combustible,id',
            'numeroMotor' => ['required','string',Rule::unique('vehiculo', 'numero_motor')->ignore($request->vehiculo_id)],
            'numeroChasis' => ['required','string',Rule::unique('vehiculo', 'numero_chasis')->ignore($request->vehiculo_id)],
            'capacidadPasajeros' => 'required|integer',
            'capacidadToneladas' => 'required|numeric',
            'tipoBateria' => 'nullable|integer|exists:vehiculo_bateria_tipo,id',
            'bateriaCapacidad' => 'nullable|numeric',
            'colorVehiculo' => 'required|string',
            'puertasVehiculo' => 'required|numeric'
        ], [
            'vehiculo_id.required' => 'No se ha especificado el vehículo a modificar.',
            'vehiculo_id.integer' => 'El ID del vehículo especificado no tiene un formato válido.',
            'vehiculo_id.exists' => 'El vehículo especificado no existe en el sistema.',
            'placa.unique' => 'La placa especificada ya está registrada en el sistema.',
            'placa.required' => 'No se ha especificado una placa.',
            'placa.max' => 'La placa debe tener un máximo de :max caracteres.',
            'placa.min' => 'La placa debe tener un mínimo de :min caracteres.',
            'tipoVehiculo.required' => 'No se ha especificado el tipo de vehículo.',
            'tipoVehiculo.integer' => 'El ID del tipo de vehículo especificado no tiene un formato válido.',
            'tipoVehiculo.exists' => 'El tipo de vehículo espécificado no existe en la base de datos.',
            'tipoCarroceria.required' => 'No se ha especificado el tipo de carrocería.',
            'tipoCarroceria.integer' => 'El ID del tipo de carrocería especificada no existe en la base de datos.',
            'tipoCarroceria.exists' => 'El tipo de carrocería especificada no existe en la base de datos.',
            'marcaVehiculo.required' => 'No se ha especificado la marca del vehículo.',
            'marcaVehiculo.integer' => 'El ID de la marca del vehículo no tiene un formato válido.',
            'marcaVehiculo.exists' => 'La marca de vehículo especificada no existe en la base de datos.',
            'lineaVehiculo.required' => 'No se ha especificado la línea del vehículo.',
            'lineaVehiculo.integer' => 'El ID de la línea del vehículo especificada no tiene un formato válido.',
            'lineaVehiculo.exists' => 'La línea del vehículo especificada no existe en el sistema.',
            'claseCombustible.required' => 'No se ha especificado la clase de combustible.',
            'claseCombustible.integer' => 'El ID de la clase de combustible no tiene un formato válido.',
            'claseCombustible.exists' => 'La clase de combustible especificada no existe en la base de datos.',
            'modeloVehiculo.required' => 'No se ha especificado el modelo del vehículo.',
            'modeloVehiculo.numeric' => 'El modelo del vehículo especificado no tiene un formato válido.',
            'capacidadPasajeros.required' => 'No se ha especificado la capacidad de asajeros del vehículo.',
            'capacidadPasajeros.integer' => 'La capacidad de pasajeros especificada no tiene un formato válido.',
            'capacidadToneladas.required' => 'No se ha espécificado la capacidad de carga del vehículo.',
            'capacidadToneladas.numeric' => 'La capacidad de toneladas especificada no tiene un formato válido.',
            'numeroMotor.required' => 'No se ha especificado un número de motor.',
            'numeroMotor.unique' => 'El número de motor especificado ya está en uso.',
            'numeroMotor.string' => 'El número de motor especificado no tiene un formato válido.',
            'numeroChasis.required' => 'No se ha especificado un número de chasis.',
            'numeroChasis.unique' => 'El número de chasis especificado ya está en uso.',
            'numeroChasis.string' => 'El número de chasis especificado no tiene un formato válido.',
            'colorVehiculo.required' => 'No se ha especificado el color.',
            'colorVehiculo.string' => 'El color especificado no es válido.',
            'puertasVehiculo.required' => 'No se han especificado la cantidad de puertas del vehículo.',
            'puertasVehiculo.numeric' => 'El valor especificado para la cantidad de puertas del vehículo no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!'
            ], 200);
        }
        $claseVehiculo = vehiculo_clase::find($request->tipoVehiculo); 
        $tipoBateria = null;
        $capacidadBateria = null;
        if ($claseVehiculo->name == 'ELECTRICO') {
            if($request->tipoBateria == null || $request->bateriaCapacidad == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se han especificado correctamente los parámetros de Tipo batería y Capacidad batería para esta clase de vehículo Eléctrico.'],
                    'encabezado' => '¡Errores!'
                ], 200);
            }else{
                $tipoBateria = $request->tipoBateria;
                $capacidadBateria = $request->bateriaCapacidad;
            }
        }else{
            try{
                $vehiculo = vehiculo::find($request->vehiculo_id);
                $vehiculo->numero_motor = strtoupper($request->numeroMotor);
                $vehiculo->numero_chasis = strtoupper($request->numeroChasis);
                $vehiculo->placa = strtoupper($request->placa);
                $vehiculo->modelo = $request->modeloVehiculo;
                $vehiculo->capacidad_pasajeros = $request->capacidadPasajeros;
                $vehiculo->capacidad_toneladas = $request->capacidadToneladas;
                $vehiculo->vehiculo_clase_id = $request->tipoVehiculo;
                $vehiculo->vehiculo_carroceria_id = $request->tipoCarroceria;
                $vehiculo->vehiculo_marca_id = $request->marcaVehiculo;
                $vehiculo->vehiculo_linea_id = $request->lineaVehiculo;
                $vehiculo->vehiculo_combustible_id = $request->claseCombustible;
                $vehiculo->vehiculo_bateria_tipo_id = $tipoBateria;
                $vehiculo->bateria_capacidad_watts = $capacidadBateria;
                $vehiculo->color = $request->colorVehiculo;
                $vehiculo->puertas = $request->puertasVehiculo;
                $vehiculo->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han guardado los cambios del vehículo exitosamente.',
                    'encabezado' => 'Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido modificar el vehículo. Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => '¡Error!'
                ], 200);
            }
        }
    }

    public function vincularEmpresa($id)
    {
        $empresasTransporte = empresa_transporte::pluck('name', 'id');
        $nivelServicio = vehiculo_nivel_servicio::pluck('name', 'id');
        $radioOperacion = vehiculo_radio_operacion::pluck('name', 'id');
        return view('admin.tramites.vehiculos.vincularEmpresa', ['empresasTransporte'=>$empresasTransporte, 'nivelServicio'=>$nivelServicio, 'radioOperacion'=>$radioOperacion, 'vehiculo_id'=>$id])->render();
    }

    public function vincularUnaEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehiculo_id' => 'integer|required|exists:vehiculo,id',
            'empresaTransporte' => 'integer|required|exists:empresa_transporte,id',
            'nivelServicio' => 'integer|required|exists:vehiculo_nivel_servicio,id',
            'radioOperacion' => 'integer|required|exists:vehiculo_radio_operacion,id',
            'numeroInterno' => 'required|numeric',
            'fechaVinculacion_submit' => 'date|required'
        ], [
            'vehiculo_id.integer' => 'El ID del vehículo especificado no tiene un formato válido.',
            'vehiculo_id.required' => 'No se ha especificado el vehículo al que se vinculará la empresa especificada.',
            'vehiculo_id.exists' => 'El vehículo especificado no existe en el sistema.',
            'empresaTransporte.integer' => 'El ID de la empresa transportadora especificada no tiene un formato válido.',
            'empresaTransporte.required' => 'No se ha especificado la empresa transportadora a vincular.',
            'empresaTransporte.exists' => 'La empresa de transporte especificada no existe en el sistema.',
            'nivelServicio.integer' => 'El ID del nivel del servicio especificado no tiene un formato válido.',
            'nivelServicio.required' => 'No se ha especificado el nivel del servicio para el vehículo.',
            'nivelServicio.exists' => 'El nivel de servicio especificado para el vehículo no existe en el sistema.',
            'radioOperacion.integer' => 'El ID del radio de operación no tiene un formato válido.',
            'radioOperacion.required' => 'No se ha especificado el radio de operación del vehículo.',
            'radioOperacion.exists' => 'El radio de operación especificado no tiene un formato válido.',
            'numeroInterno.required' => 'No se ha especificado el número interno.',
            'numeroInterno.numeric' => 'El número interno especificado no existe en el sistema.',
            'fechaVinculacion_submit.required' => 'No se ha especificado la fecha de vinculación del vehículo a la empresa.',
            'fechaVinculacion_submit.date' => 'La fecha de vinculación del vehículo especificada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!'
            ], 200);
        }else{
            \DB::beginTransaction();
            $registroActivo = \DB::table('vehiculo_empresa_transporte')->where('numero_interno', $request->numeroInterno)->where('estado', 1)->where('empresa_transporte_id', $request->empresaTransporte)->first();
            if($registroActivo != null){
                if($request->desvincularActual){
                    \DB::table('vehiculo_empresa_transporte')->where('numero_interno', $request->numeroInterno)->where('estado', 1)->where('empresa_transporte_id', $request->empresaTransporte)->update(['estado'=>0,'fecha_retiro'=>$request->fechaDesvinculacion_submit]);            
                }else{
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['Ya existe una asignación activa del número interno especificado con la misma empresa de transporte.'],
                        'encabezado' => '¡Errores!'
                    ], 200);
                }                
            }
            $success = false;            
            try{
                $vehiculo = vehiculo::find($request->vehiculo_id);
                if($vehiculo->hasEmpresaActiva() != null){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El vehículo ya se encuentra vinculado a una empresa. Antes debe desvincularlo.'],
                        'encabezado' => '¡Errores!'
                    ], 200);
                }
                $vehiculo->hasEmpresasTransporte()->attach($request->empresaTransporte, [
                    'nivel_servicio_id' => $request->nivelServicio,
                    'radio_operacion_id' => $request->radioOperacion,
                    'numero_interno' => $request->numeroInterno,
                    'fecha_afiliacion' => $request->fechaVinculacion_submit,
                    'estado' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }
        }

        if($success === true){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha vinculado la empresa exitosamente.',
                'encabezado' => 'Completado!',
            ], 200);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors'=>['No se ha podido vincular la empresa. Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => '¡Error!'
            ], 200);
        }
    }

    public function verVinculacion($id)
    {
        try{
            $vehiculo = vehiculo::find($id);
            $empresasTransporte = empresa_transporte::pluck('name', 'id');
            $nivelServicio = vehiculo_nivel_servicio::pluck('name', 'id');
            $radioOperacion = vehiculo_radio_operacion::pluck('name', 'id');
            return view('admin.tramites.vehiculos.verVinculacion', ['empresasTransporte'=>$empresasTransporte, 'nivelServicio'=>$nivelServicio, 'radioOperacion'=>$radioOperacion, 'vehiculo'=>$vehiculo])->render();
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors'=>['No se ha podido vincular la empresa. Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => '¡Error!'
            ], 200);
        }
    }

    public function cambiosVinculacionEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehiculo_id' => 'integer|required|exists:vehiculo,id',
            'empresaTransporte' => 'integer|required|exists:empresa_transporte,id',
            'nivelServicio' => 'integer|required|exists:vehiculo_nivel_servicio,id',
            'radioOperacion' => 'integer|required|exists:vehiculo_radio_operacion,id',
            'numeroInterno' => 'required|numeric',
            'fechaVinculacion_submit' => 'date|required',
            'fechaRetiro_submit' => 'nullable|date'
        ], [
            'vehiculo_id.integer' => 'El ID del vehículo especificado no tiene un formato válido.',
            'vehiculo_id.required' => 'No se ha especificado el vehículo al que se vinculará la empresa especificada.',
            'vehiculo_id.exists' => 'El vehículo especificado no existe en el sistema.',
            'empresaTransporte.integer' => 'El ID de la empresa transportadora especificada no tiene un formato válido.',
            'empresaTransporte.required' => 'No se ha especificado la empresa transportadora a vincular.',
            'empresaTransporte.exists' => 'La empresa de transporte especificada no existe en el sistema.',
            'nivelServicio.integer' => 'El ID del nivel del servicio especificado no tiene un formato válido.',
            'nivelServicio.required' => 'No se ha especificado el nivel del servicio para el vehículo.',
            'nivelServicio.exists' => 'El nivel de servicio especificado para el vehículo no existe en el sistema.',
            'radioOperacion.integer' => 'El ID del radio de operación no tiene un formato válido.',
            'radioOperacion.required' => 'No se ha especificado el radio de operación del vehículo.',
            'radioOperacion.exists' => 'El radio de operación especificado no tiene un formato válido.',
            'numeroInterno.required' => 'No se ha especificado el número interno.',
            'numeroInterno.numeric' => 'El número interno especificado no existe en el sistema.',
            'fechaVinculacion_submit.required' => 'No se ha especificado la fecha de vinculación del vehículo a la empresa.',
            'fechaVinculacion_submit.date' => 'La fecha de vinculación del vehículo especificada no tiene un formato válido.',
            'fechaRetiro_submit.date' => 'La fecha de retiro especificada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => '¡Errores!'
            ], 200);
        }else{
            $registros = \DB::table('vehiculo_empresa_transporte')->where('numero_interno', $request->numeroInterno)->where('estado', 1)->where('empresa_transporte_id', $request->empresaTransporte)->where('vehiculo_id', '!=', $request->vehiculo_id)->count();
            if($registros > 0){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Ya existe una asignación activa del número interno especificado con la misma empresa de transporte.'],
                    'encabezado' => '¡Errores!'
                ], 200);
            }
            $success = false;
            \DB::beginTransaction();
            try{
                $fechaRetiro = null;
                $vehiculo = vehiculo::find($request->vehiculo_id);
                $estado = 1;
                if($request->fechaRetiro_submit != ' ' && $request->fechaRetiro_submit != null){
                    $fechaRetiro = $request->fechaRetiro_submit;
                    $estado = 0;
                }
                $vehiculo->hasEmpresasTransporte()->updateExistingPivot($vehiculo->hasEmpresaActiva()->id, [
                    'nivel_servicio_id' => $request->nivelServicio,
                    'radio_operacion_id' => $request->radioOperacion,
                    'numero_interno' => $request->numeroInterno,
                    'fecha_afiliacion' => $request->fechaVinculacion_submit,
                    'fecha_retiro' => $fechaRetiro,
                    'estado' => $estado,
                    'empresa_transporte_id' => $request->empresaTransporte
                ]);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }
        }

        if($success === true){
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha realizado los cambios exitosamente.',
                'encabezado' => 'Completado!',
            ], 200);
        }else{
            return response()->view('admin.mensajes.errors', [
                'errors'=>['No se ha podido realizar los cambios. Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => '¡Error!'
            ], 200);
        }
    }

    public function crearServicio()
    {
        $clases_vehiculos = vehiculo_clase::all();
        return view('admin.tramites.vehiculos.nuevoServicio', ['clases_vehiculos'=>$clases_vehiculos])->render();
    }

    public function nuevoServicio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreServicio' => 'required|string|unique:vehiculo_servicio,name',
            'placa_consecutivo' => ['required','string',Rule::in(['SI','NO'])]
        ], [
            'nombreServicio.required' => 'El nombre de la servicio es obligatorio.',
            'nombreServicio.string' => 'El formato del nombre proporcionado no es válido',
            'nombreServicio.unique' => 'El nombre ya existe en la base de datos',
            'placa_consecutivo.required' => 'No se ha especificado si se requiere que la placa sea asignada consecutivamente.',
            'placa_consecutivo.string' => 'El valor para el campo Placa Consecutiva no tiene un formato válido.',
            'placa_consecutivo.in' => 'El valor para el campo Placa Consecutiva no es permitido, debe ser: SI o NO.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = false;
            $ignoradas = 0;
            \DB::beginTransaction();
            try{
                $servicio = new vehiculo_servicio();
                $servicio->name = strtoupper($request->nombreServicio);
                $servicio->placa_consecutivo = $request->placa_consecutivo;
                $servicio->save();
                $servicio->hasClasesVinculadas()->sync($request->clases);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if ($success) {
                if($ignoradas >= 1){
                    if($ignoradas < count($request->clases)){
                        return response()->view('admin.mensajes.success', [
                            'mensaje' => 'Se ha creado el servicio, pero no se han podido vincular '.$ignoradas.' clases. Recuerda que debes diligenciar todos los campos por cada clase seleccionada.',
                            'encabezado' => '¡Completado!',
                        ], 200);
                    }else{
                        return response()->view('admin.mensajes.success', [
                            'mensaje' => 'Se ha creado el servicio, y se ha podido vincular ninguna de las clases especificadas. Recuerda que debes diligenciar todos los campos por cada clase seleccionada.',
                            'encabezado' => '¡Completado!',
                        ], 200);
                    }
                }else{
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado el servicio, junto con todas las clases seleccionadas.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarServicio($id)
    {
        $servicio = vehiculo_servicio::with('hasClasesVinculadas')->find($id);
        $vehiculos_clases = vehiculo_clase::all();
        return view('admin.tramites.vehiculos.editarServicio', ['servicio'=>$servicio, 'clases_vehiculos'=>$vehiculos_clases])->render();
    }

    public function obtenerServicios()
    {
        if (\Defender::hasRole('Administrador')) {
            $servicios = vehiculo_servicio::withTrashed()->orderBy('name', 'asc')->paginate(15);
        } else {
            $servicios = vehiculo_servicio::orderBy('name', 'asc')->paginate(15);
        }

        return view('admin.tramites.vehiculos.listadoServicios', ['servicios' => $servicios])->render();
    }

    public function eliminarServicio($id)
    {
        $servicio = vehiculo_servicio::find($id);
        if ($servicio == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carrocería especificada no existe.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $servicio->delete();
            if ($servicio->trashed()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el servicio de vehículo.',
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

    public function restaurarServicio($id)
    {
        $servicio = vehiculo_servicio::withTrashed()->find($id);
        if ($servicio == null) {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La carrocería especificada no existe..'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        } else {
            $servicio->restore();
            if ($servicio->trashed()) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            } else {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha activado el servicio de vehículo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }
        }
    }

    public function actualizarServicio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idServicio' => 'required|integer|exists:vehiculo_servicio,id',
            'nombreServicio' => ['required','string',Rule::unique('vehiculo_servicio', 'name')->ignore($request->idServicio)],
            'placa_consecutivo' => ['required','string',Rule::in(['SI','NO'])],
            'clases' => 'array|required|min:1'
        ], [
            'nombreServicio.required' => 'El nombre de la servicio es obligatorio.',
            'nombreServicio.string' => 'El formato del nombre proporcionado no es válido',
            'nombreServicio.unique' => 'El nombre ya existe en la base de datos',
            'idServicio.integer' => 'El formato del Id de la servicio no es válido',
            'idServicio.required' => 'No se suministrós un Id correcto',
            'idServicio.exists' => 'El Id de la servicio no está registrado en la base de datos',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $success = false;
            $ignoradas = 0;
            \DB::beginTransaction();
            try{
                $servicio = vehiculo_servicio::withTrashed()->find($request->idServicio);
                $servicio->name = strtoupper($request->nombreServicio);
                $servicio->placa_consecutivo = $request->placa_consecutivo;
                $servicio->save();
                $servicio->hasClasesVinculadas()->sync($request->clases);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if ($success) {
                if($ignoradas >= 1){
                    if($ignoradas < count($request->clases)){
                        return response()->view('admin.mensajes.success', [
                            'mensaje' => 'Se ha modificado el servicio, pero no se han podido vincular '.$ignoradas.' clases. Recuerda que debes diligenciar todos los campos por cada clase seleccionada.',
                            'encabezado' => '¡Completado!',
                        ], 200);
                    }else{
                        return response()->view('admin.mensajes.success', [
                            'mensaje' => 'Se ha creado el servicio, y se ha podido vincular ninguna de las clases especificadas. Recuerda que debes diligenciar todos los campos por cada clase seleccionada.',
                            'encabezado' => '¡Completado!',
                        ], 200);
                    }
                }else{
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha modificado el servicio, junto con todas las clases seleccionadas.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function filtrarVehiculos($parametro, $tipo)
    {
        $vehiculos = null;
        switch ($tipo) {
            case 1:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')->where('placa',$parametro)->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 2:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')->where('numero_motor','like','%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 3:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')->where('numero_chasis','like','%'.$parametro.'%')->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 4:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')
                    ->whereHas('hasEmpresasTransporte', function ($query) use ($parametro){
                        $query->where('estado',1)->where('name','like','%'.$parametro.'%');
                    })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 5:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')
                    ->whereHas('hasEmpresasTransporte', function ($query) use ($parametro){
                        $query->where('estado',1)->where('numero_interno','like','%'.$parametro.'%');
                    })->orderBy('created_at', 'desc')->paginate(50);
                break;
            case 6:
                $vehiculos = vehiculo::with('hasTOS', 'hasTipoVehiculo', 'hasTipoCarroceria', 'hasClaseCombustible', 'hasMarca')
                    ->whereHas('hasPropietarios', function ($query) use ($parametro){
                        $query->where('estado',1)->where('numero_documento','like','%'.$parametro.'%');
                    })->orderBy('placa', 'asc')->paginate(50);
                break;
        }

        if (count($vehiculos) > 0) {
            $filtros = [
                '1' => 'Placa',
                '2' => 'Motor',
                '3' => 'Chasis',
                '4' => 'Razón social',
                '5' => 'Número interno',
                '6' => 'Doc. Propietario'
            ];
            $sFiltro = $tipo;

            return view('admin.tramites.vehiculos.listadoVehiculos', ['vehiculos'=>$vehiculos,'filtros'=>$filtros,'sFiltro'=>$sFiltro])->render();
        } else {
            return null;
        }
    }

    public function obtenerLineasJSON($id)
    {
        $lineas = vehiculo_linea::orderBy('nombre', 'asc')->where('vehiculo_marca_id', $id)->pluck('nombre','id');
        return $lineas->toJson();
    }

    public function nuevaLinea()
    {
        $marcas = vehiculo_marca::orderBy('name','asc')->pluck('name','id');
        return view('admin.tramites.vehiculos.nuevaLinea',['marcas'=>$marcas])->render();
    }

    public function crearLinea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string',
            'cilindraje' => 'required|numeric',
            'marca' => 'required|integer|exists:vehiculo_marca,id'
        ], [
            'nombre.required' => 'No se ha especificado el nombre de la línea.',
            'nombre.string' => 'El nombre de la línea especificado no tiene un formato válido.',
            'cilindraje.required' => 'No se ha especificado el cilindraje.',
            'cilindraje.numeric' => 'El cilindraje especificado no tiene un formato válido.',
            'marca.required' => 'No se ha especificado la marca al que pertenece la línea.',
            'marca.integer' => 'El ID de la marca especificado no tiene un formato válido.',
            'marca.exists' => 'La marca especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                vehiculo_linea::create([
                    'nombre' => strtoupper($request->nombre),
                    'cilindraje' => $request->cilindraje,
                    'vehiculo_marca_id' => $request->marca
                ]);
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la línea exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarLinea($id)
    {
        $marcas = vehiculo_marca::orderBy('name','asc')->pluck('name','id');
        $linea = vehiculo_linea::find($id);
        return view('admin.tramites.vehiculos.editarLinea', ['marcas'=>$marcas, 'linea'=>$linea])->render();
    }

    public function actualizarLinea(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_linea,id',
            'nombre' => 'required|string',
            'cilindraje' => 'required|numeric',
            'marca' => 'required|integer|exists:vehiculo_marca,id'
        ], [
            'id.required' => 'No se ha especificado la línea a modificar.',
            'id.integer' => 'El ID de la línea especificada no tiene un formato válido.',
            'id.exists' => 'La línea especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado el nombre de la línea.',
            'nombre.string' => 'El nombre de la línea especificado no tiene un formato válido.',
            'cilindraje.required' => 'No se ha especificado el cilindraje.',
            'cilindraje.numeric' => 'El cilindraje especificado no tiene un formato válido.',
            'marca.required' => 'No se ha especificado la marca al que pertenece la línea.',
            'marca.integer' => 'El ID de la marca especificado no tiene un formato válido.',
            'marca.exists' => 'La marca especificado no existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $linea = vehiculo_linea::find($request->id);
                $linea->nombre =  strtoupper($request->nombre);
                $linea->cilindraje = $request->cilindraje;
                $linea->vehiculo_marca_id = $request->marca;
                $linea->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la línea exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function obtenerLineas()
    {
        $lineas = vehiculo_linea::orderBy('nombre', 'asc')->paginate(50);
        return view('admin.tramites.vehiculos.listadoLineas', ['lineas' => $lineas])->render();
    }

    public function obtenerPropietarios($id)
    {
        $vehiculo = vehiculo::with('hasPropietarios')->find($id);
        return view('admin.tramites.vehiculos.listadoPropietarios', ['vehiculo'=>$vehiculo])->render();
    }

    public function nuevoPropietrario($id)
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::pluck('name','id');
        $departamentos = departamento::pluck('name','id');
        return view('admin.tramites.vehiculos.nuevoPropietario', ['tiposDocumentosIdentidad'=>$tiposDocumentosIdentidad,'departamentos'=>$departamentos,'id'=>$id])->render();
    }

    public function crearPropietario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo,id',
            'tipo_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'numero_documento' => 'required|numeric',
            'telefono' => 'required|numeric',
            'departamento' => 'required|integer|exists:departamento,id',
            'municipio' => 'required|integer|exists:municipio,id',
            'direccion' => 'required|string',
            'nombre' => 'required|string',
            'correo' => 'nullable|email'
        ], [
            'id.required' => 'No se ha especificado el vehículo a relacionar.',
            'id.integer' => 'El ID del vehículo especificado no tiene un formato válido.',
            'id.exists' => 'El vehículo especificado no existe en el sistema.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identidad.',
            'tipo_documento.integer' => 'El ID del tipo de documento de identidad especificado no tiene un formato válido.',
            'tipo_documento.exists' => 'El tipo de documento de identidad especificado no existe en el sistema.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el número de teléfono.',
            'telefono.numeric' => 'El número de teléfono especificado no tiene un formato válido.',
            'departamento.required' => 'No se ha especificado el departamento.',
            'departamento.integer' => 'El ID del departamento especificado no tiene un formato válido.',
            'departamento.exists' => 'El departamento especificado no existe en el sistema.',
            'municipio.required' => 'No se ha especificado el municipio.',
            'municipio.integer' => 'El ID del municipio especificado no tiene un formato válido.',
            'municipio.exists' => 'El municipio especificado no existe en el sistema.',
            'direccion.required' => 'No se ha especificado la dirección.',
            'direccion.string' => 'La dirección especificada no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado el nombre del propietario.',
            'nombre.string' => 'El nombre del propietario especificado no tiene un formato válido.',
            'correo.email' => 'El correo especificado no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $propietario = vehiculo_propietario::where('tipo_documento_id', $request->tipo_documento)->where('numero_documento', $request->numero_documento)->first();
                if($propietario != null){
                    $propietario->hasVehiculos()->attach($request->id, ['estado'=>1]);
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Ya existe un propietario con los datos especificados. Se vinculado el propietario exitosamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }else{
                    $propietario = vehiculo_propietario::create([
                        'nombre' => strtoupper($request->nombre),
                        'numero_documento' => $request->numero_documento,
                        'tipo_documento_id' => $request->tipo_documento,
                        'telefono' => $request->telefono,
                        'departamento_id' => $request->departamento,
                        'municipio_id' => $request->municipio,
                        'direccion' => strtoupper($request->direccion),
                        'correo_electronico' => $request->correo
                    ]);
                    $propietario->hasVehiculos()->attach($request->id, ['estado'=>1]);
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado y vinculado el propietario exitosamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                }
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarPropietrario($id)
    {
        $tiposDocumentosIdentidad = usuario_tipo_documento::pluck('name','id');
        $departamentos = departamento::pluck('name','id');
        $propietario = vehiculo_propietario::find($id);
        return view('admin.tramites.vehiculos.editarPropietario', ['tiposDocumentosIdentidad'=>$tiposDocumentosIdentidad,'departamentos'=>$departamentos,'propietario'=>$propietario])->render();
    }

    public function actualizarPropietario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_propietario,id',
            'tipo_documento' => 'required|integer|exists:usuario_tipo_documento,id',
            'numero_documento' => 'required|numeric',
            'telefono' => 'required|numeric',
            'departamento' => 'required|integer|exists:departamento,id',
            'municipio' => 'required|integer|exists:municipio,id',
            'direccion' => 'required|string',
            'nombre' => 'required|string',
            'correo' => 'nullable|email'
        ], [
            'id.required' => 'No se ha especificado el propietario a modificar.',
            'id.integer' => 'El ID del propietario especificado no tiene un formato válido.',
            'id.exists' => 'El propietario especificado no existe en el sistema.',
            'tipo_documento.required' => 'No se ha especificado el tipo de documento de identidad.',
            'tipo_documento.integer' => 'El ID del tipo de documento de identidad especificado no tiene un formato válido.',
            'tipo_documento.exists' => 'El tipo de documento de identidad especificado no existe en el sistema.',
            'numero_documento.required' => 'No se ha especificado el número de documento de identidad.',
            'numero_documento.numeric' => 'El número de documento de identidad especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el número de teléfono.',
            'telefono.numeric' => 'El número de teléfono especificado no tiene un formato válido.',
            'departamento.required' => 'No se ha especificado el departamento.',
            'departamento.integer' => 'El ID del departamento especificado no tiene un formato válido.',
            'departamento.exists' => 'El departamento especificado no existe en el sistema.',
            'municipio.required' => 'No se ha especificado el municipio.',
            'municipio.integer' => 'El ID del municipio especificado no tiene un formato válido.',
            'municipio.exists' => 'El municipio especificado no existe en el sistema.',
            'direccion.required' => 'No se ha especificado la dirección.',
            'direccion.string' => 'La dirección especificada no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado el nombre del propietario.',
            'nombre.string' => 'El nombre del propietario especificado no tiene un formato válido.',
            'correo.email' => 'El correo especificado no es válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                $propietario = vehiculo_propietario::find($request->id);
                $propietario->nombre = strtoupper($request->nombre);
                $propietario->numero_documento = $request->numero_documento;
                $propietario->tipo_documento_id = $request->tipo_documento;
                $propietario->telefono = $request->telefono;
                $propietario->departamento_id = $request->departamento;
                $propietario->municipio_id = $request->municipio;
                $propietario->direccion = strtoupper($request->direccion);
                $propietario->correo_electronico = $request->correo;
                $propietario->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el propietario exitosamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                echo $e->getMessage();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function retirarPropietario($propietarioId, $vehiculoId)
    {
        try{
            $propietario = vehiculo_propietario::find($propietarioId);
            $vehiculo = $propietario->hasVehiculos()->where('id', $vehiculoId)->first();
            $vehiculo->pivot->estado = 0;
            $vehiculo->pivot->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha desvinculado el propietario exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido desvincular al propietario.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function vincularPropietario($propietarioId, $vehiculoId)
    {
        try{
            $propietario = vehiculo_propietario::find($propietarioId);
            $vehiculo = $propietario->hasVehiculos()->where('id', $vehiculoId)->first();
            $vehiculo->pivot->estado = 1;
            $vehiculo->pivot->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha vinculado el propietario exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido vincular al propietario.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function obtenerTiposBaterias()
    {
        $tiposBaterias = vehiculo_bateria_tipo::all();
        return view('admin.tramites.vehiculos.listadoTiposBaterias', ['tiposBaterias' => $tiposBaterias])->render();
    }

    public function nuevoTipoBateria()
    {
        return view('admin.tramites.vehiculos.nuevoTipoBateria')->render();
    }

    public function crearTipoBateria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:vehiculo_bateria_tipo'
        ], [
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            vehiculo_bateria_tipo::create([
                'name' => strtoupper($request->name)
            ]);
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el tipo de bateria correctamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el tipo de bateria.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function editarTipoBateria($id)
    {
        $tipo = vehiculo_bateria_tipo::find($id);
        return view('admin.tramites.vehiculos.editarTipoBateria', ['tipo'=>$tipo])->render();
    }

    public function actualizarTipoBateria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:vehiculo_bateria_tipo',
            'name' => 'required|string|unique:vehiculo_bateria_tipo'
        ], [
            'id.required' => 'No se ha especificado el tipo a modificar.',
            'id.integer' => 'El ID del tipo bateria especificado no tiene un formato válido.',
            'id.exists' => 'El tip de bateria a modificar no existe.',
            'name.required' => 'No se ha especificado el nombre.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya existe en el sistema.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $tipo = vehiculo_bateria_tipo::find($request->id);
            $tipo->name = strtoupper($request->name);
            $tipo->save();
            
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el tipo de bateria exitosamente.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el tipo de bateria.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }    
}
