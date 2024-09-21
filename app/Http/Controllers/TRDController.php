<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\dependencia;
use App\trd_documento_serie;
use App\trd_documento_subserie;
use App\trd_documento_tipo;
use Validator;
use Illuminate\Validation\Rule;

class TRDController extends Controller
{
    public function administrar()
    {
        $series = trd_documento_serie::pluck('name', 'id');
        $subseries = trd_documento_subserie::pluck('name', 'id');
        $tipos = trd_documento_tipo::pluck('name', 'id');
        $dependencias = dependencia::pluck('name', 'id');

        return view('admin.gestion_documental.trd.administrar', [
            'series' => $series,
            'subseries' => $subseries,
            'tipos' => $tipos,
            'dependencias' => $dependencias,
        ]);
    }

    public function nuevaSerie()
    {
        $dependencias = dependencia::pluck('name', 'id');

        return view('admin.gestion_documental.trd.crearSerie', ['dependencias' => $dependencias])->render();
    }

    public function nuevaSubSerie()
    {
        $series = trd_documento_serie::pluck('name', 'id');

        return view('admin.gestion_documental.trd.crearSubSerie', ['series' => $series])->render();
    }

    public function nuevoTipoDocumento()
    {
        $series = trd_documento_serie::pluck('name', 'id');

        return view('admin.gestion_documental.trd.crearTipoDocumento', ['series' => $series])->render();
    }

    public function obtenerSeries($format)
    {
        if ($format == 'json') {
            return trd_documento_serie::select('name', 'id')->get()->toJson();
        } else {
            return view('admin.gestion_documental.trd.listadoSeries', ['series' => trd_documento_serie::with('hasSubSeries', 'hasDependencia')->paginate(50)])->render();
        }
    }

    public function obtenerSubSeries($serie_id, $format)
    {
        if ($format == 'json') {
            return trd_documento_subserie::where('trd_documento_serie_id', $serie_id)->whereHas('hasSerie')->select('name', 'id')->get()->toJson();
        } else {
            return view('admin.gestion_documental.trd.listadoSubSeries', ['subseries' => trd_documento_subserie::with('hasTipos', 'hasSerie')->where('trd_documento_serie_id', $serie_id)->whereHas('hasSerie')->paginate(50)])->render();
        }
    }

    public function obtenerTiposDocumentos($sub_serie_id, $format)
    {
        if ($format == 'json') {
            return trd_documento_tipo::select('name', 'id')->where('trd_documento_subserie_id', $sub_serie_id)->get()->toJson();
        } else {
            return view('admin.gestion_documental.trd.listadoTipos', ['tipos' => trd_documento_tipo::with('hasSubSerie')->where('trd_documento_subserie_id', $sub_serie_id)->paginate(50)])->render();
        }
    }

    public function obtenerSerie($id)
    {
        $dependencias = dependencia::pluck('name', 'id');

        return view('admin.gestion_documental.trd.editarSerie', [
            'serie' => trd_documento_serie::find($id),
            'dependencias' => $dependencias,
        ])->render();
    }

    public function obtenerSubSerie($id)
    {
        $series = trd_documento_serie::pluck('name', 'id');

        return view('admin.gestion_documental.trd.editarSubSerie', [
            'subSerie' => trd_documento_subserie::find($id),
            'series' => $series,
        ])->render();
    }

    public function obtenerTipoDocumento($id)
    {
        $series = trd_documento_serie::pluck('name', 'id');

        return view('admin.gestion_documental.trd.editarTipoDocumento', [
            'tipoDocumento' => trd_documento_tipo::with('hasSubSerie')->find($id),
            'series' => $series,
        ])->render();
    }

    public function crearSerie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dependencia' => 'required|integer|exists:dependencia,id',
            'nombre' => 'required|unique:trd_documento_serie,name',
        ], [
            'dependencia.required' => 'No se ha especificado la dependencia a la que pertenecerá la serie.',
            'dependencia.integer' => 'El ID de la dependencia no tiene un formato válido.',
            'dependencia.exists' => 'La dependencia especificada no existe en el sistema.',
            'nombre.required' => 'No ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $serie = trd_documento_serie::create([
                'dependencia_id' => $request->dependencia,
                'name' => $request->nombre,
            ]);

            if ($serie != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la serie correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha creado la serie.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function crearSubSerie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serie' => 'required|exists:trd_documento_serie,id|integer',
            'nombre' => 'required|unique:trd_documento_subserie,name',
            'gestion' => 'required_without:central',
            'central' => 'required_without:gestion',
            'conservacion' => 'required_without_all:eliminacion,digitalizar,seleccion|max:1|string',
            'eliminacion' => 'required_without_all:conservacion,digitalizar,seleccion|max:1|string',
            'digitalizar' => 'required_without_all:conservacion,eliminacion,seleccion|max:1|string',
            'seleccion' => 'required_without_all:conservacion,eliminacion,digitalizar|max:1|string',
            'descripcion' => 'required|string',
        ], [
            'serie.required' => 'Se debe especificar la serie a la que pertenecerá la sub serie.',
            'serie.exists' => 'La serie especificada no existe en el sistema.',
            'serie.integer' => 'El ID de la serie especificada no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está en uso.',
            'gestion.required_without' => 'Se debe especificar si la retención será en el archivo de gestión o central.',
            'central.required_without' => 'Se debe especificar si la retención será en el archivo central o de gestión.',
            'conservacion.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'eliminacion.required_without' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'digitalizar.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'seleccion.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'descripcion.required' => 'No se ha especificado una descripción.',
            'descripcion.string' => 'La descripción especificada no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            //variable de control de errores de checkbox
            $errors = null;
            /*
             * Validación de opciones de checkbox
             */
            if (! isset($request->gestion) && ! isset($request->central)) {
                $errors = ['Se debe seleccionar al menos una opción para la retención.'];
            }

            if (! isset($request->conservacion) && ! isset($request->eliminacion) && ! isset($request->digitalizar) && ! isset($request->seleccion)) {
                if ($errors != null) {
                    $errors = array_prepend($errors, 'Se debe seleccionar al menos una opción para la disposición final.');
                } else {
                    $errors = ['Se debe seleccionar al menos una opción para la disposición final.'];
                }
            }

            if ($errors != null) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => $errors,
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            } else {
                $subserie = trd_documento_subserie::create([
                    'trd_documento_serie_id' => $request->serie,
                    'name' => $request->nombre,
                    'archivo_gestion' => $request->gestion,
                    'archivo_central' => $request->central,
                    'conservacion_total' => $request->conservacion,
                    'eliminacion' => $request->eliminacion,
                    'digitalizar' => $request->digitalizar,
                    'seleccion' => $request->seleccion,
                    'descripcion' => $request->descripcion,
                ]);

                if ($subserie != null) {
                    return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha creado la sub serie correctamente.',
                        'encabezado' => '¡Completado!',
                    ], 200);
                } else {
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear la sub serie.'],
                        'encabezado' => 'Error en la solicitud',
                    ], 200);
                }
            }
        }
    }

    public function crearTipoDocumento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subserie' => 'required|integer|exists:trd_documento_subserie,id',
            'nombre' => 'required|unique:trd_documento_tipo,name',
        ], [
            'subserie.required' => 'No se ha especificado el ID de la sub serie a la que pertenecerá el tipo de documento.',
            'subserie.integer' => 'El ID de la sub serie especificada no tiene un formato válido.',
            'subserie.exists' => 'El ID de la sub serie especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está siendo usado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $tipo = trd_documento_tipo::create([
                'trd_documento_subserie_id' => $request->subserie,
                'name' => $request->nombre,
            ]);

            if ($tipo != null) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el tipo documento correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha creado el tipo documento.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarSerie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serie_id' => 'required|integer|exists:trd_documento_serie,id',
            'dependencia' => 'required|integer|exists:dependencia,id',
            'nombre' => ['required', Rule::unique('trd_documento_serie', 'name')->ignore($request->serie_id, 'id')],
        ], [
            'serie_id.required' => 'No se ha especificado el ID de la serie a actualizar.',
            'serie_id.integer' => 'El ID de la serie especificada no tiene un formato válido.',
            'serie_id.exists' => 'El ID de la serie especificada no existe en el sistema.',
            'dependencia.required' => 'No se ha especificado la dependencia a la que pertenecerá la serie.',
            'dependencia.integer' => 'El ID de la dependencia no tiene un formato válido.',
            'dependencia.exists' => 'La dependencia especificada no existe en el sistema.',
            'nombre.required' => 'No ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está en uso.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $serie = trd_documento_serie::find($request->serie_id);
            $serie->name = $request->nombre;
            $serie->dependencia_id = $request->dependencia;

            if ($serie->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la serie correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha actualizado la serie.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarSubSerie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subserie_id' => 'required|integer|exists:trd_documento_subserie,id',
            'serie' => 'required|exists:trd_documento_serie,id|integer',
            'nombre' => [
                'required',
                Rule::unique('trd_documento_subserie', 'name')->ignore($request->subserie_id, 'id'),
            ],
            'gestion' => 'required_without:central',
            'central' => 'required_without:gestion',
            'conservacion' => 'required_without_all:eliminacion,digitalizar,seleccion',
            'eliminacion' => 'required_without_all:conservacion,digitalizar,seleccion',
            'digitalizar' => 'required_without_all:conservacion,eliminacion,seleccion',
            'seleccion' => 'required_without_all:conservacion,eliminacion,digitalizar',
            'descripcion' => 'required|string',
        ], [
            'subserie_id.required' => 'No se ha especificado el ID de la sub serie a actualizar.',
            'subserie_id.integer' => 'El ID de la sub serie especificado no tiene un formato válido.',
            'subserie_id.exists' => 'El ID de la sub serie especifica no existe en el sistema.',
            'serie.required' => 'Se debe especificar la serie a la que pertenecerá la sub serie.',
            'serie.exists' => 'La serie especificada no existe en el sistema.',
            'serie.integer' => 'El ID de la serie especificada no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está en uso.',
            'gestion.required_without' => 'Se debe especificar si la retención será en el archivo de gestión o central.',
            'central.required_without' => 'Se debe especificar si la retención será en el archivo central o de gestión.',
            'conservacion.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'eliminacion.required_without' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'digitalizar.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'seleccion.required_without_all' => 'Se debe seleccionar al menos un elemento para la disposición final.',
            'descripcion.required' => 'No se ha especificado una descripción.',
            'descripcion.string' => 'La descripción especificada no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $subserie = trd_documento_subserie::find($request->subserie_id);
            $subserie->trd_documento_serie_id = $request->serie;
            $subserie->name = $request->nombre;
            $subserie->archivo_gestion = $request->gestion;
            $subserie->archivo_central = $request->central;
            $subserie->conservacion_total = $request->conservacion;
            $subserie->eliminacion = $request->eliminacion;
            $subserie->digitalizar = $request->digitalizar;
            $subserie->seleccion = $request->seleccion;
            $subserie->descripcion = $request->descripcion;

            if ($subserie->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la sub serie correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha actualizado la sub serie.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function editarTipoDocumento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_id' => 'required|integer|exists:trd_documento_tipo,id',
            'subserie' => 'required|integer|exists:trd_documento_subserie,id',
            'nombre' => [
                'required',
                'unique:trd_documento_tipo,name',
                Rule::unique('trd_documento_tipo', 'name')->ignore($request->tipo_id, 'id'),
            ],
        ], [
            'tipo_id.required' => 'No se ha especificado el ID del tipo documento a actualizar.',
            'tipo_id.integer' => 'El ID especificado del tipo documento no tiene un formato válido.',
            'tipo_id.exists' => 'El Id especificado del tipo documento no existe en el sistema.',
            'subserie.required' => 'No se ha especificado el ID de la sub serie a la que pertenecerá el tipo de documento.',
            'subserie.integer' => 'El ID de la sub serie especificada no tiene un formato válido.',
            'subserie.exists' => 'El ID de la sub serie especificada no existe en el sistema.',
            'nombre.required' => 'No se ha especificado un nombre.',
            'nombre.unique' => 'El nombre especificado ya está siendo usado.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $tipo = trd_documento_tipo::find($request->tipo_id);
            $tipo->name = $request->nombre;
            $tipo->trd_documento_subserie_id = $request->subserie;

            if ($tipo->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el tipo documento correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha actualizado el tipo documento.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        }
    }

    public function eliminarSerie($id)
    {
        if (auth()->user()->hasRole('Administrador')) {
            $serie = trd_documento_serie::find($id);
            $serie->hasSubSeries()->delete();
            if ($serie->delete()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado la serie correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha eliminado la serie.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No tiene permiso para realizar esta acción.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function eliminarSubSerie($id)
    {
        if (auth()->user()->hasRole('Administrador')) {
            $subserie = trd_documento_subserie::find($id);
            $subserie->hasTipos()->delete();
            if ($subserie->delete()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado la sub serie correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha eliminado la sub serie.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No tiene permiso para realizar esta acción.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function eliminarTipo($id)
    {
        if (auth()->user()->hasRole('Administrador')) {
            $tipo = trd_documento_tipo::find($id);
            if ($tipo->delete()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha eliminado el tipo documento correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha eliminado el tipo documento.'],
                    'encabezado' => 'Error en la solicitud',
                ], 200);
            }
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No tiene permiso para realizar esta acción.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }
}
