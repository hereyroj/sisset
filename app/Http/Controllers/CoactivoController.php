<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoactivoComparendo;
use Validator;
use Illuminate\Validation\Rule;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\CoactivoFotoMultas;

class CoactivoController extends Controller
{
    public function administrarComparendos()
    {
        return view('admin.coactivo.edictos.comparendos.administrar');
    }

    public function nuevaNotificacionComparendo()
    {
        return view('admin.coactivo.edictos.comparendos.nuevaNotificacion')->render();
    }

    public function nuevaNotificacionFotoMulta()
    {
        return view('admin.coactivo.edictos.foto_multas.nuevaNotificacion')->render();
    }

    public function crearComparendo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cc' => 'string|required|min:6|max:11',
            'edicto' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
            'publicationDate_submit' => 'required|date',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'El nombre no tiene un formato válido.',
            'cc.required' => 'No se ha especificado un número de cédula.',
            'edicto.required' => 'No se ha seleccionado el edicto.',
            'edicto.mimetypes' => 'El edicto seleccionado no tiene un formato válido.',
            'edicto.mimes' => 'El edicto seleccionado no tiene un formato válido.',
            'edicto.max' => 'El edicto no debe pesar mas de 8 MB.',
            'publicationDate_submit.required' => 'No se ha especificado la fecha de publicacion.',
            'publicationDate_submit.date' => 'La fecha de publicación especificada no tiene un formato válido.',
            'cc.numeric' => 'El formato de la cédula no es válido.',
            'cc.max' => 'La cedula tiene un límite máximo de :max caracteres.',
            'cc.min' => 'La cedula tiene un límite mínimo de :min caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $comparendo = new CoactivoComparendo();
            $comparendo->name = strtoupper($request->name);
            $comparendo->cc = $request->cc;
            $comparendo->publication_date = $request->publicationDate_submit;
            if ($comparendo->save()) {
                $comparendo->pathArchive = Storage::disk('edictos')->putFileAs('comparendos', $request->file('edicto'), $comparendo->id . '.pdf');
                $comparendo->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el comparendo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el comparendo.'],
                    'encabezado' => 'Errores en la creación:',
                ], 200);
            }
        }
    }

    public function obtenerComparendos()
    {
        $comparendos = CoactivoComparendo::orderBy('created_at', 'desc')->paginate(150);

        return view('admin.coactivo.edictos.comparendos.listadoComparendos', ['comparendos' => $comparendos])->render();
    }

    public function cargarComparendo($id)
    {
        $comparendo = CoactivoComparendo::find($id);

        return view('admin.coactivo.edictos.comparendos.cargarComparendo', ['comparendo' => $comparendo])->render();
    }

    public function editarComparendo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comparendoId' => 'required|integer|exists:coactivo_comparendo,id',
            'comparendoName' => 'required|string',
            'comparendoCc' => 'string|required|min:6|max:11',
            'comparendoEdicto' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'publicationDateEdit_submit' => 'required|date',
        ], [
            'comparendoId.required' => '',
            'comparendoId.integer' => '',
            'comparendoId.exists' => '',
            'comparendoName.required' => 'No se ha especificado un nombre.',
            'comparendoName.string' => 'EL nombre tiene un formato no válido.',
            'comparendoCc.required' => 'No se ha especificado un número de cédula.',
            'publicationDateEdit_submit.required' => 'No se ha especificado la fecha de publicación.',
            'publicationDateEdit_submit.date' => 'La fecha de publicación especificada no tiene un formato válido.',
            'comparendoCc.numeric' => 'El formato de la cédula no es válido.',
            'comparendoCc.max' => 'La cedula tiene un límite máximo de :max caracteres.',
            'comparendoCc.min' => 'La cedula tiene un límite mínimo de :min caracteres.',
            'comparendoEdicto.required' => 'No se ha seleccionado el edicto.',
            'comparendoEdicto.mimetypes' => 'El edicto seleccionado no tiene un formato válido.',
            'comparendoEdicto.mimes' => 'El edicto seleccionado no tiene un formato válido.',
            'comparendoEdicto.max' => 'El edicto no debe pesar mas de 8 MB.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $comparendo = CoactivoComparendo::find($request->comparendoId);
            $comparendo->name = strtoupper($request->comparendoName);
            $comparendo->cc = $request->comparendoCc;
            $comparendo->publication_date = $request->publicationDateEdit_submit;
            if ($comparendo->save()) {
                if($request->comparendoEdicto != null){
                    $comparendo->pathArchive = Storage::disk('edictos')->putFileAs('comparendos', $request->file('comparendoEdicto'), $comparendo->id . '.pdf');
                    $comparendo->save();
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el comparendo.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el comparendo.'],
                    'encabezado' => 'Errores en la creación:',
                ], 200);
            }
        }
    }

    public function eliminarComparendo($id)
    {
        $comparendo = CoactivoComparendo::where('id', $id)->first();
        $comparendo->delete();
        if ($comparendo->trashed()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el comparendo del sistema.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function importarComparendos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel|required',
        ], [
            'required' => 'No se ha suministrado un archivo de registros.',
            'mimetypes' => 'El archivo de importación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $ruta_archivo = 'comparendosImportados-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
            Storage::disk('imports')->putFileAs('comparendos', $request->file('comparendos'), $ruta_archivo);

            Excel::filter('chunk')->load(storage_path('app/imports/comparendos/'.$ruta_archivo))->chunk(250, function (
                $results
            ) {
                foreach ($results as $comparendo) {
                    CoactivoComparendo::create([
                        'name' => $comparendo->nombre,
                        'cc' => ceil($comparendo->numero_de_identificacion),
                        'pathArchive' => $comparendo->documentos,
                        'publication_date' => Carbon::createFromFormat('d/m/Y', $comparendo->fecha_publicacion)->toDateString(),
                    ]);
                }
            });

            return redirect()->to('admin/coactivo/edictos/comparendos/administrar');
        }
    }

    public function comparendos_filtrarBusqueda($parametro)
    {
        if (is_numeric($parametro)) {
            $comparendos = CoactivoComparendo::where('cc', 'like', '%'.$parametro.'%')->paginate(25);
        } else {
            $comparendos = CoactivoComparendo::where('name', 'like', '%'.$parametro.'%')->paginate(25);
        }

        return view('admin.coactivo.edictos.comparendos.listadoComparendos', [
            'comparendos' => $comparendos,
            'parametro' => $parametro,
        ])->render();
    }

    /*
     * Foto Multas
     */
    public function administrarFotoMultas()
    {
        return view('admin.coactivo.edictos.foto_multas.administrar');
    }

    public function crearFotoMulta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cc' => 'string|required|min:6|max:11',
            'edicto' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
            'publicationDate_submit' => 'required|date',
        ], [
            'name.required' => 'No se ha especificado un nombre.',
            'name.string' => 'EL nombre no tiene un formato válido.',
            'cc.required' => 'No se ha especificado un número de cédula.',
            'publicationDate_submit.required' => 'No se ha especificado la fecha de publicacion.',
            'publicationDate_submit.date' => 'La fecha de publicación especificada no tiene un formato válido.',
            'cc.numeric' => 'El formato de la cédula no es válido.',
            'cc.max' => 'La cedula tiene un límite máximo de :max caracteres.',
            'cc.min' => 'La cedula tiene un límite mínimo de :min caracteres.',
            'edicto.required' => 'No se ha seleccionado el edicto.',
            'edicto.mimetypes' => 'El edicto seleccionado no tiene un formato válido.',
            'edicto.mimes' => 'El edicto seleccionado no tiene un formato válido.',
            'edicto.max' => 'El edicto no debe pesar mas de 8 MB.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $fotoMulta = new CoactivoFotoMultas();
            $fotoMulta->name = strtoupper($request->name);
            $fotoMulta->cc = $request->cc;
            $fotoMulta->publication_date = $request->publicationDate_submit;
            if ($fotoMulta->save()) {
                $fotoMulta->pathArchive = Storage::disk('edictos')->putFileAs('fotomultas', $request->file('edicto'), $fotoMulta->id . '.pdf');
                $fotoMulta->save();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el fotoMulta.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el fotoMulta.'],
                    'encabezado' => 'Errores en la creación:',
                ], 200);
            }
        }
    }

    public function obtenerFotoMultas()
    {
        $fotoMultas = CoactivoFotoMultas::orderBy('created_at', 'desc')->paginate(150);

        return view('admin.coactivo.edictos.foto_multas.listadoFotoMultas', ['fotoMultas' => $fotoMultas])->render();
    }

    public function cargarFotoMulta($id)
    {
        $fotoMulta = CoactivoFotoMultas::find($id);

        return view('admin.coactivo.edictos.foto_multas.cargarFotoMulta', ['fotoMulta' => $fotoMulta])->render();
    }

    public function editarFotoMulta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fotoMultaId' => 'required|integer|exists:coactivo_foto_multa,id',
            'fotoMultaName' => 'required|string',
            'fotoMultaCc' => 'string|required|min:6|max:11',
            'fotoMultaEdicto' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'publicationDateEdit_submit' => 'required|date',
        ], [
            'fotoMultaId.required' => '',
            'fotoMultaId.integer' => '',
            'fotoMultaId.exists' => '',
            'fotoMultaName.required' => 'No se ha especificado un nombre.',
            'fotoMultaName.string' => 'EL nombre tiene un formato no válido.',
            'fotoMultaCc.required' => 'No se ha especificado un número de cédula.',
            'fotoMultaPathArchive.required' => 'No se ha especificado la ruta del archivo.',
            'publicationDateEdit_submit.required' => 'No se ha especificado la fecha de publicacion.',
            'publicationDateEdit_submit.date' => 'La fecha de publicación especificada no tiene un formato válido.',
            'fotoMultaCc.numeric' => 'El formato de la cédula no es válido.',
            'fotoMultaCc.max' => 'La cedula tiene un límite máximo de :max caracteres.',
            'fotoMultaCc.min' => 'La cedula tiene un límite mínimo de :min caracteres.',
            'fotoMultaEdicto.mimetypes' => 'El edicto seleccionado no tiene un formato válido.',
            'fotoMultaEdicto.mimes' => 'El edicto seleccionado no tiene un formato válido.',
            'fotoMultaEdicto.max' => 'El edicto no debe pesar mas de 8 MB.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $fotoMulta = CoactivoFotoMultas::find($request->fotoMultaId);
            $fotoMulta->name = strtoupper($request->fotoMultaName);
            $fotoMulta->cc = $request->fotoMultaCc;
            $fotoMulta->publication_date = $request->publicationDateEdit_submit;
            if ($fotoMulta->save()) {
                if($request->fotoMultaEdicto != null){
                    $fotoMulta->pathArchive = Storage::disk('edictos')->putFileAs('fotomultas', $request->file('fotoMultaEdicto'), $fotoMulta->id . '.pdf');
                    $fotoMulta->save();
                }
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el fotoMulta.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el fotoMulta.'],
                    'encabezado' => 'Errores en la creación:',
                ], 200);
            }
        }
    }

    public function eliminarFotoMulta($id)
    {
        $fotoMulta = CoactivoFotoMultas::where('id', $id)->first();
        $fotoMulta->delete();
        if ($fotoMulta->trashed()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado el fotoMulta del sistema.',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'],
                'encabezado' => 'Error en la solicitud',
            ], 200);
        }
    }

    public function importarFotoMultas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel|required',
        ], [
            'required' => 'No se ha suministrado un archivo de registros.',
            'mimetypes' => 'El archivo de importación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $ruta_archivo = 'fotoMultasImportadas-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
            Storage::disk('imports')->putFileAs('FotoMultas', $request->file('fotoMultas'), $ruta_archivo);

            Excel::filter('chunk')->load(storage_path('app/imports/FotoMultas/'.$ruta_archivo))->chunk(250, function (
                $results
            ) {
                foreach ($results as $fotoMulta) {
                    CoactivoFotoMultas::create([
                        'name' => $fotoMulta->nombre,
                        'cc' => ceil($fotoMulta->numero_de_identificacion),
                        'pathArchive' => $fotoMulta->documentos,
                        'publication_date' => Carbon::createFromFormat('d/m/Y', $fotoMulta->fecha_publicacion)->toDateString(),
                    ]);
                }
            });

            return redirect()->to('admin/coactivo/edictos/fotoMultas/administrar');
        }
    }

    public function fotoMultas_filtrarBusqueda($parametro)
    {
        if (is_numeric($parametro)) {
            $fotoMultas = CoactivoFotoMultas::where('cc', 'like', '%'.$parametro.'%')->paginate(25);
        } else {
            $fotoMultas = CoactivoFotoMultas::where('name', 'like', '%'.$parametro.'%')->paginate(25);
        }

        return view('admin.coactivo.edictos.foto_multas.listadoFotoMultas', [
            'fotoMultas' => $fotoMultas,
            'parametro' => $parametro,
        ])->render();
    }
}
