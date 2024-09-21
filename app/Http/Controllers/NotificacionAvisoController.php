<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\notificacion_aviso;
use App\notificacion_aviso_tipo;
use Validator;
use Storage;

class NotificacionAvisoController extends Controller
{
    public function administrar()
    {
        $criterios = [
            '1' => 'Número documento',
            '2' => 'Nombre',
        ];

        return view('admin.notificacionesAviso.administrar', ['criterios' => $criterios])->render();
    }

    public function nueva()
    {
        $tiposNotificacionesAviso = notificacion_aviso_tipo::pluck('name', 'id');

        return view('admin.notificacionesAviso.nuevaNotificacionAviso', ['tiposNotificacionesAviso' => $tiposNotificacionesAviso])->render();
    }

    public function crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento_notificacion_aviso' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
            'fecha_publicacion_submit' => 'required|date|different:fecha_desfijacion',
            'fecha_desfijacion_submit' => 'required|date|different:fecha_publicacion|after:fecha_publicacion',
            'numero_documento' => 'numeric|required',
            'nombre_notificado' => 'string|required',
            'tipo_notificacion_aviso' => 'required|integer|exists:notificacion_aviso_tipo,id',
            'numero_proceso' => 'nullable|numeric',
        ], [
            'documento_notificacion_aviso.required' => 'No se ha especificado el documento de notificación.',
            'documento_notificacion_aviso.mimetypes' => 'El anexo no tiene un formato válido. Debe ser un archivo PDF.',
            'documento_notificacion_aviso.max' => 'El anexo no debe superar el tamaño máximo permitido de 20MB.',
            'documento_notificacion_aviso.mimes' => 'El anexo no tiene un formato válido. Debe ser un archivo PDF.',
            'fecha_publicacion_submit.required' => 'No se ha especificado la fecha de publicación.',
            'fecha_publicacion_submit.date' => 'El formato de la fecha de publicación no es válido.',
            'fecha_publicacion_submit.different' => 'La fecha de publicación debe ser diferente a la fecha de desfijación.',
            'fecha_desfijacion_submit.required' => 'No se ha especificado la fecha de desfijación.',
            'fecha_desfijacion_submit.date' => 'El formato de la fecha de desfijación no es válido.',
            'fecha_desfijacion_submit.different' => 'La fecha de publicación debe ser diferente a la fecha de publicación.',
            'fecha_desfijacion_submit.after' => 'La fecha de desfijación debe ser anterior a la fecha de publicación.',
            'numero_documento.numeric' => 'El formato del número de documento no es válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento.',
            'nombre_notificado.string' => 'El formato del nombre del notificado no es válido.',
            'nombre_notificado.required' => 'No se ha especificado el nombre del notificado.',
            'tipo_notificacion_aviso.required' => 'No se ha especificado el tipo de notificación.',
            'tipo_notificacion_aviso.integer' => 'El ID del tipo de notificación no tiene un formato válido.',
            'tipo_notificacion_aviso.exists' => 'El tipo de notificación especificado no existe en el sistema.',
            'numero_proceso.numeric' => 'El valor especificado para el número de comparendo no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $notificacionAviso = new notificacion_aviso();
            $notificacionAviso->fecha_publicacion = $request->fecha_publicacion_submit;
            $notificacionAviso->fecha_desfijacion = $request->fecha_desfijacion_submit;
            $notificacionAviso->numero_documento = $request->numero_documento;
            $notificacionAviso->not_aviso_tipo_id = $request->tipo_notificacion_aviso;
            $notificacionAviso->nombre_notificado = strtoupper($request->nombre_notificado);
            $notificacionAviso->numero_proceso = $request->numero_proceso;
            $notificacionAviso->documento_notificacion = Storage::disk('notificacionesAviso')->putFile('/', $request->file('documento_notificacion_aviso'));
            
            if ($notificacionAviso->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado la notificación',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido crear la notificación. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function obtenerTodas()
    {
        $notificacionesAviso = notificacion_aviso::orderBy('created_at', 'desc')->get();

        return view('admin.notificacionesAviso.listadoNotificacionesAviso', ['notificacionesAviso' => $notificacionesAviso])->render();
    }

    public function obtenerDocumento($id)
    {
        $notificacionAviso = notificacion_aviso::with('hasTipoNotificacion')->find($id);
        $name = explode('/', $notificacionAviso->documento_notificacion);
        $headers = [
            'Content-Type: application/zip',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/notificacionesAviso/'.array_last($name)), array_last($name), $headers);
    }

    public function editarNotificacionAviso($id)
    {
        $notificacionAviso = notificacion_aviso::find($id);
        $tiposNotificacionesAviso = notificacion_aviso_tipo::pluck('name', 'id');

        return view('admin.notificacionesAviso.editarNotificacionAviso', [
            'notificacionAviso' => $notificacionAviso,
            'tiposNotificacionesAviso' => $tiposNotificacionesAviso,
        ])->render();
    }

    public function actualizarNotificacionAviso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notificacion_aviso_id' => 'required|integer|exists:notificacion_aviso,id',
            'documento_notificacion_aviso' => 'mimetypes:application/pdf|mimes:pdf|max:80000',
            'fecha_publicacion_submit' => 'required|date|different:fecha_desfijacion',
            'fecha_desfijacion_submit' => 'required|date|different:fecha_publicacion|after:fecha_publicacion',
            'numero_documento' => 'numeric|required',
            'nombre_notificado' => 'string|required',
            'tipo_notificacion_aviso' => 'required|integer|exists:notificacion_aviso_tipo,id',
        ], [
            'notificacion_aviso_id.required' => 'No se ha especificado la notificación a modificar.',
            'notificacion_aviso_id.integer' => 'La notificación especificada no tiene un formato válido.',
            'notificacion_aviso_id.exists' => 'La notificación especificada no existe.',
            'documento_notificacion_aviso.mimetypes' => 'El anexo no tiene un formato válido. Debe ser un archivo PDF.',
            'documento_notificacion_aviso.max' => 'El anexo no debe superar el tamaño máximo permitido de 20MB.',
            'documento_notificacion_aviso.mimes' => 'El anexo no tiene un formato válido. Debe ser un archivo PDF.',
            'fecha_publicacion_submit.required' => 'No se ha especificado la fecha de publicación.',
            'fecha_publicacion_submit.date' => 'El formato de la fecha de publicación no es válido.',
            'fecha_publicacion_submit.different' => 'La fecha de publicación debe ser diferente a la fecha de desfijación.',
            'fecha_desfijacion_submit.required' => 'No se ha especificado la fecha de desfijación.',
            'fecha_desfijacion_submit.date' => 'El formato de la fecha de desfijación no es válido.',
            'fecha_desfijacion_submit.different' => 'La fecha de publicación debe ser diferente a la fecha de publicación.',
            'fecha_desfijacion_submit.after' => 'La fecha de desfijación debe ser anterior a la fecha de publicación.',
            'numero_documento.numeric' => 'El formato del número de documento no es válido.',
            'numero_documento.required' => 'No se ha especificado el número de documento.',
            'nombre_notificado.string' => 'El formato del nombre del notificado no es válido.',
            'nombre_notificado.required' => 'No se ha especificado el nombre del notificado.',
            'tipo_notificacion_aviso.required' => 'No se ha especificado el tipo de notificación.',
            'tipo_notificacion_aviso.integer' => 'El ID del tipo de notificación no tiene un formato válido.',
            'tipo_notificacion_aviso.exists' => 'El tipo de notificación especificado no existe en el sistema.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            $notificacionAviso = notificacion_aviso::find($request->notificacion_aviso_id);
            $notificacionAviso->fecha_publicacion = $request->fecha_publicacion_submit;
            $notificacionAviso->fecha_desfijacion = $request->fecha_desfijacion_submit;
            $notificacionAviso->numero_documento = $request->numero_documento;
            $notificacionAviso->not_aviso_tipo_id = $request->tipo_notificacion_aviso;
            $notificacionAviso->nombre_notificado = strtoupper($request->nombre_notificado);
            $notificacionAviso->numero_proceso = $request->numero_proceso;

            if ($request->documento_notificacion != null && $request->documento_notificacion != '') {
                $notificacionAviso->documento_notificacion = Storage::disk('notificacionesAviso')->putFile('/', $request->file('documento_notificacion_aviso'));
            }
            if ($notificacionAviso->save()) {
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la notificación',
                    'encabezado' => '¡Completado!',
                ], 200);
            } else {
                return response()->view('admin.mensajes.errors', [
                    'errors'=>['No se ha podido actualizar la notificación. Si el problema persiste, por favor comunicarse con soporte.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }
        }
    }

    public function filtrar($criterio, $parametro)
    {
        $notificacionesAviso = null;
        switch ($criterio) {
            case 1:
                $notificacionesAviso = notificacion_aviso::where('numero_documento', $parametro)->get();
                break;
            case 2:
                $notificacionesAviso = notificacion_aviso::where('nombre_notificado', 'like', '%'.$parametro.'%')->get();
                break;
        }

        return view('admin.notificacionesAviso.listadoNotificacionesAviso', ['notificacionesAviso' => $notificacionesAviso])->render();
    }

    public function eliminarNotificacionAviso($id)
    {
        $notificacionAviso = notificacion_aviso::find($id);
        if ($notificacionAviso->delete()) {
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha eliminado la notificación',
                'encabezado' => '¡Completado!',
            ], 200);
        } else {
            return response()->view('admin.mensajes.errors', [
                'errors'=>['No se ha podido eliminar la notificación. Si el problema persiste, por favor comunicarse con soporte.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function obtenerListadoTiposnotificacionesAviso()
    {
        $tiposNotificacionesAviso = notificacion_aviso_tipo::paginate(15);
        return view('admin.notificacionesAviso.listadoTiposNotificacionesAviso', ['tiposNotificacionesAviso'=>$tiposNotificacionesAviso])->render();
    }

    public function nuevoTipoNotificacionAviso()
    {
        return view('admin.notificacionesAviso.nuevoTipoNotificacionAviso')->render();
    }

    public function crearTipoNotificacionAviso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:notificacion_aviso_tipo'
        ], [
            'name.required' => 'No se ha especificado el nombre para el tipo de notificación.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                notificacion_aviso_tipo::create(['name'=>strtoupper($request->name)]);
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Sanción ha sido creado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido crear el Tipo Sanción.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function editarTipoNotificacionAviso($id)
    {
        $tiposNotificacionAviso = notificacion_aviso_tipo::find($id);
        return view('admin.notificacionesAviso.editarTipoNotificacionAviso', ['tipoNotificacionAviso'=>$tiposNotificacionAviso])->render();
    }

    public function actualizarTipoNotificacionAviso(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:notificacion_aviso_tipo',
            'name' => ['required','string',Rule::unique('notificacion_aviso_tipo', 'name')->ignore($request->id)]
        ], [
            'id.required' => 'No se ha especificado el tipo de notificación a modificar.',
            'id.integer' => 'El ID del tipo de notificación a modificar no tiene un formato válido.',
            'id.exists' => 'El tipo de notificación especificado no existe en el sistema.',
            'name.required' => 'No se ha especificado el nombre para el tipo de notificación.',
            'name.string' => 'El nombre especificado no tiene un formato válido.',
            'name.unique' => 'El nombre especificado ya está en uso.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try{
                \DB::beginTransaction();
                $tiposNotificacionAviso = notificacion_aviso_tipo::find($request->id);
                $tiposNotificacionAviso->name = strtoupper($request->name);
                $tiposNotificacionAviso->save();
                \DB::commit();
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El Tipo Sanción ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                \DB::rollBack();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el Tipo Sanción.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }
}
