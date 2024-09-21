<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\acuerdo_pago;
use App\cm_pago;
use App\empresa_mensajeria;
use App\sancion;
use Validator;
use Illuminate\Validation\Rule;
use App\ma_devolucion_motivo;
use App\ma_finalizacion_tipo;
use App\ma_notificacion_medio;
use App\ma_notificacion_tipo;
use App\ma_notificacion_devolucion;
use App\ma_notificacion_entrega;
use App\mandamiento_finalizacion;
use App\mandamiento_medio;
use App\mandamiento_notificacion;
use App\mandamiento_pago;
use App\comparendo;

class MandamientoPagoController extends Controller
{
    public function administrar(){
        return view('admin.coactivo.mandamientos.administrar');
    }

    public function mandamientoPago_obtenerListado()
    {
        $filtros = [
            '1' => 'Número documento',
            '2' => 'Número proceso',
            '3' => 'Número mandamiento',
            '4' => 'Número notificación',
        ];
        $sFiltro = null;
        $mandamientos = mandamiento_pago::paginate(40);
        return view('admin.coactivo.mandamientos.listadoMandamientos', ['mandamientos'=>$mandamientos, 'filtros'=>$filtros,'sFiltro'=>$sFiltro])->render();
    }

    public function mandamientoPago_obtenerMandamientos($numeroComparendo)
    {
        $mandamientos = mandamiento_pago::whereHas('hasComparendo', function($query) use ($numeroComparendo){
            $query->where('numero', $numeroComparendo);
        })->orderBy('fecha_realizacion','desc')->get();
        return view('admin.coactivo.mandamientos.listadoMandamientos', ['mandamientos'=>$mandamientos])->render();
    }

    public function mandamientoPago_nuevo()
    {
        return view('admin.coactivo.mandamientos.nuevoMandamiento')->render();
    }

    public function mandamientoPago_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_proceso' => 'required|numeric',
            'consecutivo_mandamiento' => 'required|numeric|unique:mandamiento_pago,consecutivo',
            'fecha_mandamiento_submit' => 'required|date',
            'documento_mandamiento' => 'required|mimetypes:application/pdf',
            'valor' => 'required|numeric',
            'tipo_proceso' => ['required', 'numeric', Rule::in([1,2])]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $proceso = null;
        $procesoTipo = null;

        if($request->tipo_proceso == 1){
            $proceso = comparendo::where('numero', $request->numero_proceso)->first();

            if($proceso == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo de número '.$request->numero_proceso.' especificado no existe.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            $procesoTipo = 'App\\comparendo';

            if($proceso->hasAcuerdoPago->count() > 0){
                if($proceso->hasAcuerdoPago->first()->getEstado() == 'VIGENTE' || $proceso->hasAcuerdoPago->first()->getEstado() == 'ERROR'){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El comparendo especificado tiene un acuerdo de pago vigente o con errores.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            }

            if($proceso->hasSancion == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo especificado no está sancionado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            if($proceso->hasPago != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo especificado tiene un pagor egistrado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
        }else{
            $proceso = acuerdo_pago::where('numero_acuerdo', $request->numero_proceso)->first();

            if($proceso == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El acuerdo de pago de número '.$request->numero_proceso.' especificado no existe.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            if($proceso->hasSancion == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El acuerdo de pago de número '.$request->numero_proceso.' especificado no está sancionado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            $procesoTipo = 'App\\acuerdo_pago';
        }

        if($proceso->hasMandamientoPago != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El proceso especificado ya tiene un mandamiento de pago.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $mandamiento = mandamiento_pago::create([
                'consecutivo' => $request->consecutivo_mandamiento,
                'fecha_mandamiento' => $request->fecha_mandamiento_submit,
                'proceso_id' => $proceso->id,
                'proceso_type' => $procesoTipo,
                'valor' => $request->valor
            ]);

            $documentoMandamiento = \Storage::disk('mandamientos')->putFile($mandamiento->id, $request->documento_mandamiento);

            $mandamiento->documento = $documentoMandamiento;
            $mandamiento->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el mandamiento de pago.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el mandamiento de pago.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mandamientoPago_editar($id)
    {
        $mandamiento = mandamiento_pago::find($id);
        return view('admin.coactivo.mandamientos.editarMandamiento', ['mandamiento'=>$mandamiento])->render();
    }

    public function mandamientoPago_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'consecutivo_mandamiento' => ['required','numeric',Rule::unique('mandamiento_pago','consecutivo')->ignore($request->id)],
            'fecha_mandamiento_submit' => 'required|date',
            'documento_mandamiento' => 'mimetypes:application/pdf',
            'valor' => 'required|numeric',
            'numero_proceso' => 'required|numeric',
            'tipo_proceso' => ['required', 'numeric', Rule::in([1,2])]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $proceso = null;
        $procesoTipo = null;
        $documentoMandamiento = null;

        if($request->tipo_proceso == 1){
            $proceso = comparendo::where('numero', $request->numero_proceso)->first();

            if($proceso == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo de número '.$request->numero_proceso.' especificado no existe.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            $procesoTipo = 'App\\comparendo';

            if($proceso->hasAcuerdoPago->count() > 0){
                if($proceso->hasAcuerdoPago->first()->getEstado() == 'VIGENTE' || $proceso->hasAcuerdoPago->first()->getEstado() == 'ERROR'){
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['El comparendo especificado tiene un acuerdo de pago vigente o con errores.'],
                        'encabezado' => 'Errores en la validación:',
                    ], 200);
                }
            }

            if($proceso->hasSancion == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El comparendo especificado no está sancionado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
        }else{
            $proceso = acuerdo_pago::where('numero_acuerdo', $request->numero_proceso)->first();

            if($proceso == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El acuerdo de pago de número '.$request->numero_proceso.' especificado no existe.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            if($proceso->hasSancion == null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El acuerdo de pago de número '.$request->numero_proceso.' especificado no está sancionado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }

            $procesoTipo = 'App\\acuerdo_pago';
        }

        $mandamiento = mandamiento_pago::find($request->id);

        if($proceso->hasMandamientoPago != null && $mandamiento->proceso_type != $procesoTipo){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El comparendo especificado ya tiene un mandamiento de pago.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $mandamiento->consecutivo = $request->consecutivo_mandamiento;
            $mandamiento->fecha_mandamiento = $request->fecha_mandamiento_submit;
            $mandamiento->valor = $request->valor;
            $mandamiento->proceso_id = $proceso->id;
            $mandamiento->proceso_type = $procesoTipo;
            
            if($request->documento_mandamiento != null){
                $documentoMandamiento = \Storage::disk('mandamientos')->putFile($mandamiento->id, $request->documento_mandamiento);
                $mandamiento->documento = $documentoMandamiento;
            }

            $mandamiento->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el mandamiento de pago.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el mandamiento de pago.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mandamientoNotificaciones_obtenerListado($mandamientoId)
    {
        $mandamiento = mandamiento_pago::find($mandamientoId);     
        return view('admin.coactivo.mandamientos.listadoNotificaciones', ['mandamiento'=>$mandamiento])->render();
    }

    public function mandamientoNotificaciones_nueva($mandamientoId)
    {
        $mandamiento = mandamiento_pago::find($mandamientoId);
        if($mandamiento->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar una nueva notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        $tiposUsados = array_flatten(\DB::table('mandamiento_notificacion')->where('mandamiento_pago_id', $mandamientoId)->pluck('ma_notificacion_tipo_id'));
        $medios = mandamiento_medio::pluck('name','id');
        $empresas = empresa_mensajeria::pluck('name','id');
        $tipos = ma_notificacion_tipo::whereNotIn('id', $tiposUsados)->pluck('name','id');
        return view('admin.coactivo.mandamientos.nuevaNotificacion', ['id'=>$mandamientoId, 'medios' => $medios, 'empresas' => $empresas, 'tipos' => $tipos])->render();
    }

    public function mandamientoNotificaciones_crear(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'consecutivo' => 'required|string|unique:mandamiento_notificacion,consecutivo',
            'tipo_notificacion' => 'required|integer|exists:ma_notificacion_tipo,id',
            'fecha_notificacion_submit' => 'required|date',
            'fecha_max_presentacion_submit' => 'required|date',
            'notificacion_medio' => 'required|integer|exists:mandamiento_medio,id',
            'empresa_mensajeria' => 'nullable|integer|exists:empresa_mensajeria,id',
            'numero_guia' => 'nullable|numeric',
            'documento' => 'required|mimetypes:application/pdf',
            'pantallazo_runt' => 'nullable|mimes:jpeg,bmp,png|mimetypes:image/bmp,image/jpeg,image/png'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $mandamiento = mandamiento_pago::find($request->id);
        if($mandamiento->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar una nueva notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        $medio = mandamiento_medio::find($request->notificacion_medio);
        if($medio->requiere_guia == 1 && $request->numero_guia == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El medio de notificación requiere que se especifique el número de guía.'],
                'encabezado' => '¡Error!',
            ], 200);
        }elseif($medio->requiere_guia == 2){
            $request->empresa_mensajeria = null;
            $request->numero_guia = null;
        }

        try{
            $notificacion = mandamiento_notificacion::create([
                'ma_notificacion_tipo_id' => $request->tipo_notificacion,
                'mandamiento_pago_id' => $request->id,
                'consecutivo' => $request->consecutivo,
                'fecha_notificacion' => $request->fecha_notificacion_submit,
                'fecha_max_presentacion' => $request->fecha_max_presentacion_submit
            ]);

            $notificacion->documento = \Storage::disk('mandamientos')->putFile($notificacion->id.'/notificaciones', $request->documento);

            if($request->pantallazo_runt){
                $notificacion->pantallazo_runt = \Storage::disk('mandamientos')->putFile($request->id, $request->pantallazo_runt);
            }

            $notificacion->save();

            ma_notificacion_medio::create([
                'numero_guia' => $request->numero_guia,
                'empresa_mensajeria_id' => $request->empresa_mensajeria,
                'mandamiento_notificacion_id' => $notificacion->id,
                'mandamiento_medio_id' => $request->notificacion_medio
            ]);

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la notificación.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mandamientoNotificaciones_editar($id)
    {
        $notificacion = mandamiento_notificacion::find($id);
        $tiposUsados = array_flatten(\DB::table('mandamiento_notificacion')->where('id', $id)->where('ma_notificacion_tipo_id', '!=', $notificacion->ma_notificacion_tipo_id)->pluck('ma_notificacion_tipo_id'));
        $tipos = ma_notificacion_tipo::whereNotIn('id', $tiposUsados)->pluck('name','id');
        $medios = mandamiento_medio::pluck('name','id');
        $empresas = empresa_mensajeria::pluck('name','id');
        return view('admin.coactivo.mandamientos.editarNotificacion', ['notificacion'=>$notificacion, 'medios' => $medios, 'empresas' => $empresas, 'tipos'=>$tipos])->render();
    }

    public function mandamientoNotificaciones_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_notificacion,id',
            'consecutivo' => ['required','numeric', Rule::unique('mandamiento_notificacion','consecutivo')->ignore($request->id)],
            'tipo_notificacion' => 'required|integer|exists:ma_notificacion_tipo,id',
            'fecha_notificacion_submit' => 'required|date',
            'fecha_max_presentacion_submit' => 'required|date',
            'notificacion_medio' => 'required|integer|exists:mandamiento_medio,id',
            'empresa_mensajeria' => 'nullable|integer|exists:empresa_mensajeria,id',
            'numero_guia' => 'nullable|string',
            'documento' => 'mimetypes:application/pdf'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $notificacion = mandamiento_notificacion::find($request->id);
        if($notificacion->hasMandamientoPago->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede editar la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        $medio = mandamiento_medio::find($request->notificacion_medio);
        if($medio->requiere_guia == 1 && $request->numero_guia == null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El medio de notificación requiere que se especifique el número de guía.'],
                'encabezado' => '¡Error!',
            ], 200);
        }elseif($medio->requiere_guia == 2){
            $request->empresa_mensajeria = null;
            $request->numero_guia = null;
        }

        try{
            $notificacion = mandamiento_notificacion::find($request->id);
            $notificacion->ma_notificacion_tipo_id = $request->tipo_notificacion;
            $notificacion->consecutivo = $request->consecutivo;
            $notificacion->fecha_notificacion = $request->fecha_notificacion_submit;
            $notificacion->fecha_max_presentacion = $request->fecha_max_presentacion_submit;

            if($request->pantallazo_runt != null){
                $notificacion->pantallazo_runt = \Storage::disk('mandamientos')->putFile($notificacion->mandamiento_pago_id, $request->documento_mandamiento);;
            }

            $notificacion->save();
            
            if($request->documento != null){
                $notificacion->documento = \Storage::disk('mandamientos')->putFile($notificacion->id.'/notificaciones', $request->documento);
                $notificacion->save();
            }
            
            $notificacion->hasMedio->numero_guia = $request->numero_guia;
            $notificacion->hasMedio->empresa_mensajeria_id = $request->empresa_mensajeria;
            $notificacion->hasMedio->mandamiento_medio_id = $request->notificacion_medio;
            $notificacion->hasMedio->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la notificación.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function devolucionNotificacion_nueva($id)
    {
        $notificacion = mandamiento_notificacion::find($id);
        if($notificacion->hasMandamientoPago->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar la devolución a la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        if($notificacion->hasEntrega != null || $notificacion->hasDevolucion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La notificación cuenta con una entrega o devolución. Por tal motivo no se puede registrar la devolución.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        $motivos = ma_devolucion_motivo::pluck('name','id');
        return view('admin.coactivo.mandamientos.nuevaDevolucion', ['id'=>$id, 'motivos'=>$motivos])->render();
    }

    public function devolucionNotificacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_notificacion,id',
            'motivo_devolucion' => 'required|integer|exists:ma_devolucion_motivo,id',
            'fecha_devolucion_submit' => 'required|date',
            'observacion' => 'required|string'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $notificacion = mandamiento_notificacion::find($request->id);
        if($notificacion->hasMandamientoPago->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar la devolución a la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        if($notificacion->hasEntrega != null || $notificacion->hasDevolucion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La notificación cuenta con una entrega o devolución. Por tal motivo no se puede registrar la devolución.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        try{
            ma_notificacion_devolucion::create([
                'fecha_devolucion' => $request->fecha_devolucion_submit,
                'observacion' => $request->observacion,
                'mandamiento_notificacion_id' => $request->id,
                'ma_devolucion_motivo_id' => $request->motivo_devolucion
            ]);

            return response()->view('admin.mensajes.success', [
                    'mensaje' => '',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => [''],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function devolucionNotificacion_editar($id)
    {
        $devolucion = ma_notificacion_devolucion::find($id);
        $motivos = ma_devolucion_motivo::pluck('name','id');
        return view('admin.coactivo.mandamientos.editarDevolucion', ['devolucion'=>$devolucion, 'motivos'=>$motivos])->render();
    }

    public function devolucionNotificacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ma_notificacion_devolucion,id',
            'motivo_devolucion' => 'required|integer|exists:ma_devolucion_motivo,id',
            'fecha_devolucion_submit' => 'required|date',
            'observacion' => 'required|string'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $devolucion = ma_notificacion_devolucion::find($request->id);
            $devolucion->fecha_devolucion = $request->fecha_devolucion_submit;
            $devolucion->observacion = $request->observacion;
            $devolucion->ma_devolucion_motivo_id = $request->motivo_devolucion;
            $devolucion->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la devolución.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la devolución.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function devolucionNotificacion_obtener($id)
    {
        $devolucion = ma_notificacion_devolucion::find($id);
        return view('admin.coactivo.mandamientos.verDevolucion', ['devolucion'=>$devolucion])->render();
    }

    public function motivosDevolucion_obtenerListado()
    {
        $motivos = ma_devolucion_motivo::paginate(15);
        return view('admin.coactivo.mandamientos.listadoMotivosDevolucion', ['motivos'=>$motivos])->render();
    }

    public function motivosDevolucion_nuevo()
    {
        return view('admin.coactivo.mandamientos.nuevoMotivoDevolucion')->render();
    }

    public function motivosDevolucion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:ma_devolucion_motivo,name'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            ma_devolucion_motivo::create([
                'name' => $request->nombre
            ]);

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el motivo de devolución.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el motivo de devolución.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function motivosDevolucion_editar($id)
    {
        $motivo = ma_devolucion_motivo::find($id);
        return view('admin.coactivo.mandamientos.editarMotivoDevolucion', ['motivo'=>$motivo])->render();
    }

    public function motivosDevolucion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ma_devolucion_motivo',
            'nombre' => ['required','string',Rule::unique('ma_devolucion_motivo','name')->ignore($request->id)]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $motivo = ma_devolucion_motivo::find($request->id);
            $motivo->name = $request->nombre;
            $motivo->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el motivo de devolución.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el motivo de devolución.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mandamientoFinalizacion_obtener($id)
    {
        $finalizacion = mandamiento_finalizacion::find($id);
        return view('admin.coactivo.mandamientos.verFinalizacion', ['finalizacion'=>$finalizacion])->render();
    }

    public function mandamientoFinalizacion_nuevo($id)
    {
        $tipos = ma_finalizacion_tipo::pluck('name','id');
        return view('admin.coactivo.mandamientos.nuevaFinalizacion', ['id'=>$id, 'tipos'=>$tipos])->render();
    }

    public function mandamientoFinalizacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'tipo_finalizacion' => 'required|integer|exists:ma_finalizacion_tipo,id',
            'fecha_finalizacion_submit' => 'required|date'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $mandamiento = mandamiento_pago::find($request->id);
        if($mandamiento->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido registrar la finalización debido a que el mandamiento de pago ya cuanta con una.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        try{
            $finalizacion = mandamiento_finalizacion::create([
                'ma_finalizacion_tipo_id' => $request->tipo_finalizacion,
                'mandamiento_pago_id' => $request->id,
                'fecha_finalizacion' => $request->fecha_finalizacion_submit,
                'observacion' => $request->observacion
            ]);

            if($request->documento != null){
                $documentoFinalizacion = \Storage::disk('mandamientos')->putFile($finalizacion->mandamiento_pago_id, $request->documento);
                $finalizacion->documento = $documentoFinalizacion;
                $finalizacion->save();
            }

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la finalización.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido registrar la finalización.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mandamientoFinalizacion_editar($id)
    {
        $finalizacion = mandamiento_finalizacion::find($id);
        $tipos = ma_finalizacion_tipo::pluck('name','id');
        return view('admin.coactivo.mandamientos.editarFinalizacion', ['finalizacion'=>$finalizacion, 'tipos'=>$tipos])->render();    
    }

    public function mandamientoFinalizacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_finalizacion,id',
            'tipo_finalizacion' => 'required|integer|exists:ma_finalizacion_tipo,id',
            'fecha_finalizacion_submit' => 'required|date'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $finalizacion = mandamiento_finalizacion::find($request->id);
            $finalizacion->ma_finalizacion_tipo_id = $request->tipo_finalizacion;
            $finalizacion->fecha_finalizacion = $request->fecha_finalizacion_submit;
            $finalizacion->observacion = $request->observacion;
            $finalizacion->save();

            if($request->documento != null){
                $documentoFinalizacion = \Storage::disk('mandamientos')->putFile($finalizacion->mandamiento_pago_id, $request->documento);
                $finalizacion->documento = $documentoFinalizacion;
                $finalizacion->save();
            }

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la finalización.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la finalización.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function entregaNotificacion_obtener($id)
    {
        $entrega = ma_notificacion_entrega::find($id);
        return view('admin.coactivo.mandamientos.verEntregaNotificacion', ['entrega'=>$entrega])->render();
    }

    public function entregaNotificacion_nuevo($id)
    {
        $notificacion = mandamiento_notificacion::find($id);
        if($notificacion->hasMandamientoPago->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar la entrega a la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        if($notificacion->hasEntrega != null || $notificacion->hasDevolucion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La notificación cuenta con una entrega o devolución. Por tal motivo no se puede registrar la entrega.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        return view('admin.coactivo.mandamientos.nuevaEntregaNotificacion', ['id'=>$id])->render();
    }

    public function entregaNotificacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_notificacion,id',
            'fecha_entrega_submit' => 'required|date',
            'observacion' => 'required|string'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        $notificacion = mandamiento_notificacion::find($request->id);
        if($notificacion->hasMandamientoPago->hasFinalizacion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['El mandamiento de pago ya cuenta con una finalización. Por tal motivo no se puede registrar la entrega a la notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
        if($notificacion->hasEntrega != null || $notificacion->hasDevolucion != null){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['La notificación cuenta con una entrega o devolución. Por tal motivo no se puede registrar la entrega.'],
                'encabezado' => '¡Error!',
            ], 200);
        }

        try{
            ma_notificacion_entrega::create([
                'fecha_entrega' => $request->fecha_entrega_submit,
                'observacion' => $request->observacion,
                'mandamiento_notificacion_id' => $request->id,
                'documento_entrega' => \Storage::disk('mandamientos')->putFile($notificacion->mandamiento_pago_id, $request->documento_entrega)
            ]);

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la entrega.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => [''],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function entregaNotificacion_editar($id)
    {
        $entrega = ma_notificacion_entrega::find($id);
        return view('admin.coactivo.mandamientos.editarEntregaNotificacion', ['entrega' => $entrega])->render();    
    }

    public function entregaNotificacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ma_notificacion_entrega,id',
            'fecha_entrega_submit' => 'required|date',
            'observacion' => 'required|string'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $entrega = ma_notificacion_entrega::find($request->id);
            $entrega->fecha_entrega = $request->fecha_entrega_submit;
            $entrega->observacion = $request->observacion;

            if($request->documento_entrega != null){
                $entrega->documento_entrega = \Storage::disk('mandamientos')->putFile($entrega->hasMandamientoNotificacion->mandamiento_pago_id, $request->documento_entrega);
            }

            $entrega->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado la entrega.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la entrega.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function tiposFinalizacion_obtenerListado()
    {
        $tipos = ma_finalizacion_tipo::paginate(15);
        return view('admin.coactivo.mandamientos.listadoTiposFinalizacion', ['tipos'=>$tipos])->render();
    }

    public function tiposFinalizacion_nuevo()
    {
        return view('admin.coactivo.mandamientos.nuevoTipoFinalizacion')->render();
    }

    public function tiposFinalizacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:ma_finalizacion_tipo,name'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            ma_finalizacion_tipo::create([
                'name' => $request->nombre
            ]);

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el tipo de finalización.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el tipo de finalización.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function tiposFinalizacion_editar($id)
    {
        $tipo = ma_finalizacion_tipo::find($id);
        return view('admin.coactivo.mandamientos.editarTipoFinalizacion', ['tipo'=>$tipo])->render();        
    }

    public function tiposFinalizacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ma_finalizacion_tipo,id',
            'nombre' => ['required','string',Rule::unique('ma_finalizacion_tipo','name')->ignore($request->id)]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $tipo = ma_finalizacion_tipo::find($request->id);
            $tipo->name = $request->nombre;
            $tipo->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el tipo de finalización.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el tipo de finalización.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function tiposNotificacion_obtenerListado()
    {
        $tipos = ma_notificacion_tipo::paginate(15);
        return view('admin.coactivo.mandamientos.listadoTiposNotificacion', ['tipos'=>$tipos])->render();
    }

    public function tiposNotificacion_nuevo()
    {
        return view('admin.coactivo.mandamientos.nuevoTipoNotificacion')->render();
    }

    public function tiposNotificacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:ma_notificacion_tipo,name',
            'cant_dias' => 'required|integer',
            'tipo_dia' => ['required','string',Rule::in(['c','h'])]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            ma_notificacion_tipo::create([
                'name' => $request->nombre,
                'dia_cantidad' => $request->cant_dias,
                'dia_tipo' => $request->tipo_dia
            ]);
            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el tipo de notificación.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el tipo de notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function tiposNotificacion_editar($id)
    {
        $tipo = ma_notificacion_tipo::find($id);
        return view('admin.coactivo.mandamientos.editarTipoNotificacion', ['tipo'=>$tipo])->render();        
    }

    public function tiposNotificacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ma_notificacion_tipo,id',
            'nombre' => ['required','string',Rule::unique('ma_notificacion_tipo','id')->ignore($request->id)],
            'cant_dias' => 'required|integer',
            'tipo_dia' => ['required','string',Rule::in(['c','h'])]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $tipo = ma_notificacion_tipo::find($request->id);
            $tipo->name = $request->nombre;
            $tipo->dia_cantidad = $request->cant_dias;
            $tipo->dia_tipo = $request->tipo_dia;
            $tipo->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el tipo de notificación.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el tipo de notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mediosNotificacion_obtenerListado()
    {
        $medios = mandamiento_medio::paginate(15);
        return view('admin.coactivo.mandamientos.listadoMediosNotificacion', ['medios'=>$medios])->render();
    }

    public function mediosNotificacion_nuevo()
    {
        return view('admin.coactivo.mandamientos.nuevoMedioNotificacion')->render();
    }

    public function mediosNotificacion_crear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|unique:mandamiento_medio,name',
            'requiere_guia' => 'required|boolean'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            mandamiento_medio::create([
                'name' => $request->nombre,
                'requiere_guia' => $request->requiere_guia
            ]);

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el medio de notificación.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el medio de notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function mediosNotificacion_editar($id)
    {
        $medio = mandamiento_medio::find($id);
        return view('admin.coactivo.mandamientos.editarMedioNotificacion', ['medio'=>$medio])->render();        
    }

    public function mediosNotificacion_actualizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_medio,id',
            'nombre' => ['required','string', Rule::unique('mandamiento_medio','name')->ignore($request->id)],
            'requiere_guia' => 'required|boolean'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $medio = mandamiento_medio::find($request->id);
            $medio->name = $request->nombre;
            $medio->requiere_guia = $request->requiere_guia;
            $medio->save();

            return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha actualizado el medio de notificación.',
                    'encabezado' => '¡Completado!',
                ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el medio de notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function obtenerDocumentoMandamiento($id)
    {
        $mandamiento = mandamiento_pago::find($id);
        $name = explode('/', $mandamiento->documento);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/mandamientos/'.$mandamiento->documento), array_last($name), $headers);
    }

    public function obtenerMandamientoNotificacion($id)
    {
        $notificacion = mandamiento_notificacion::find($id);
        $name = explode('/', $notificacion->documento);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/mandamientos/'.$notificacion->documento), array_last($name), $headers);
    }

    public function obtenerPantalalzoRuntMandamientoNotificacion($id)
    {
        $notificacion = mandamiento_notificacion::find($id);
        $name = explode('/', $notificacion->pantallazo_runt);
        $headers = [
            'Content-Type: image/jpeg,image/bmp,image/png',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/mandamientos/'.$notificacion->pantallazo_runt), array_last($name), $headers);
    }

    public function vincularComparendo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'numero_comparendo' => 'required|numeric'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $comparendo = comparendo::where('numero', $request->numero_comparendo)->first();
            if($comparendo != null){
                $mandamiento = mandamiento_pago::find($request->id);
                $mandamiento->comparendo_id = $comparendo->id;
                $mandamiento->save();
                return response()->view('admin.mensajes.success', [
                        'mensaje' => 'Se ha vinculado el comparendo número '.$request->numero_comparendo.'.',
                        'encabezado' => '¡Completado!',
                    ], 200);               
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido vincular el comparendo número '.$request->numero_comparendo.' debido a que no existe en el sistema un comparendo con el número especificado.'],
                    'encabezado' => '¡Error!',
                ], 200);
            }            
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el medio de notificación.'],
                'encabezado' => '¡Error!',
            ], 200);
        }
    }

    public function verComparendo($id)
    {
        $comparendo = comparendo::find($id);
        return view('admin.coactivo.mandamientos.verComparendo', ['comparendo'=>$comparendo])->render();
    }

    public function mandamientoFinalizacion_obtenerDocumento($id)
    {
        $finalizacion = mandamiento_finalizacion::find($id);
        $name = explode('/', $finalizacion->documento);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/mandamientos/'.$finalizacion->documento), array_last($name), $headers);
    }

    public function verSancion($id)
    {
        $sancion = sancion::find($id);
        $name = explode('/', $sancion->documento_sancion);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/sanciones/'.$sancion->documento_sancion), array_last($name), $headers);
    }

    public function obtenerDocumentoEntrega($id)
    {
        $entrega = ma_notificacion_entrega::find($id);
        $name = explode('/', $entrega->documento_entrega);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/mandamientos/'.$entrega->documento_entrega), array_last($name), $headers);
    }

    public function verAcuerdoPago($id)
    {
        $acuerdoPago = acuerdo_pago::find($id);
        return view('admin.coactivo.mandamientos.verAcuerdoPago', ['acuerdoPago'=>$acuerdoPago])->render();
    }

    public function obtenerDocumentoAcuerdoPago($id)
    {
        $acuerdoPago = acuerdo_pago::find($id);
        $name = explode('/', $acuerdoPago->acuerdo);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/acuerdosPagos/'.$acuerdoPago->acuerdo), array_last($name), $headers);
    }

    public function verProcesoAcuerdoPago($id)
    {
        $acuerdoPago = acuerdo_pago::find($id);
        if($acuerdoPago->hasComparendos->count() > 0){
            return view('admin.coactivo.mandamientos.verComparendosAcuerdoPago', ['comparendos'=>$acuerdoPago->hasComparendos])->render();
        }else{
            return view('admin.coactivo.mandamientos.verMandamientoPago', ['mandamientos'=>$acuerdoPago->hasMandamientosPagos])->render();
        }
    }

    public function realizarPago($mandamiento_id)
    {
        return view('admin.coactivo.mandamientos.registroPago', ['mandamiento_id'=>$mandamiento_id])->render();
    }

    public function registrarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mandamiento_id' => 'required|integer|exists:mandamiento_pago,id',
            'fecha_pago_submit' => 'required|date',
            'valor' => 'required|numeric',
            'descuento_valor' => 'nullable|numeric',
            'intereses' => 'nullable|numeric',
            'interes_descuento' => 'nullable|numeric',
            'cobro_adicional' => 'nullable|numeric',
            'numero_factura' => 'required|numeric',
            'numero_consignacion' => 'required|numeric',
            'consignacion' => 'required|mimetypes:application/pdf|mimes:pdf'
        ], [
            'mandamiento_id.required' => 'No se ha especificado el mandamiento a pagar.',
            'mandamiento_id.integer' => 'El ID del mandamiento especificado no tiene un formato válido.',
            'mandamiento_id.exists' => 'El mandamiento especificado a pagar no existe en el sistema.',

            'consignacion.required' => 'No se ha suministrado la consignación del pago.',
            'consignacion.mimetypes' => 'El documento de la consignación suministrada no tiene un formato válido.',
            'consignacion.mimes' => 'El documento de la consignación suministrada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $mandamiento = mandamiento_pago::find($request->mandamiento_id);
            if($mandamiento->hasAcuerdoPago->count() > 0){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El mandamiento de pago está en proceso de acuerdo de pago, lo cual no permite registrar su pago.'],
                    'encabezado' => 'Restricción:',
                ], 200);
            }

            if($mandamiento->hasPago != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['El mandamiento de pago ya tiene un pago registrado.'],
                    'encabezado' => 'Restricción:',
                ], 200);
            }
            $success = false;
            try{
                \DB::beginTransaction();
                cm_pago::create([
                    'fecha_pago' => $request->fecha_pago_submit,
                    'valor_intereses' => $request->intereses,
                    'descuento_intereses' => $request->intereses_descuento,
                    'numero_factura' => $request->numero_factura,
                    'numero_consignacion' => $request->numero_consignacion,
                    'valor' => $request->valor,
                    'descuento_valor' => $request->descuento_valor,
                    'cobro_adicional' => $request->cobro_adicional,
                    'proceso_id' => $request->mandamiento_id,
                    'proceso_type' => 'App\\mandamiento_pago',
                    'consignacion' => \Storage::disk('mandamientos')->putFile($request->mandamiento_id, $request->file('consignacion'))
                ]);
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if($success == true){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El pago ha sido registrado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido registrar el pago.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function verPago($mandamiento_id)
    {
        $mandamiento = mandamiento_pago::find($mandamiento_id);
        return view('admin.coactivo.mandamientos.verPago', ['pago'=>$mandamiento->hasPago])->render();
    }

    public function editarPago($mandamiento_id)
    {
        $mandamiento = mandamiento_pago::find($mandamiento_id);
        return view('admin.coactivo.mandamientos.editarPago', ['pago'=>$mandamiento->hasPago])->render();
    }

    public function actualizarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cm_pago,id',
            'fecha_pago_submit' => 'required|date',
            'valor' => 'required|numeric',
            'descuento_valor' => 'nullable|numeric',
            'intereses' => 'nullable|numeric',
            'interes_descuento' => 'nullable|numeric',
            'cobro_adicional' => 'nullable|numeric',
            'numero_factura' => 'required|numeric',
            'numero_consignacion' => 'required|numeric',
            'consignacion' => 'mimetypes:application/pdf|mimes:pdf'
        ], [
            'consignacion.mimetypes' => 'El documento de la consignación suministrada no tiene un formato válido.',
            'consignacion.mimes' => 'El documento de la consignación suministrada no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            $success = false;
            try{
                \DB::beginTransaction();
                $pago = cm_pago::find($request->id);
                $pago->fecha_pago = $request->fecha_pago_submit;
                $pago->valor_intereses = $request->intereses;
                $pago->descuento_intereses = $request->intereses_descuento;
                $pago->numero_factura = $request->numero_factura;
                $pago->numero_consignacion = $request->numero_consignacion;
                $pago->valor = $request->valor;
                $pago->descuento_valor = $request->descuento_valor;
                $pago->cobro_adicional = $request->cobro_adicional;
                if($request->consignacion != null) {
                    $pago->consignacion = \Storage::disk('mandamientos')->putFile($pago->proceso_id, $request->file('consignacion'));
                }
                $pago->save();
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                \DB::rollBack();
            }

            if($success == true){
                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'El pago ha sido actualizado satisfactoriamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['No se ha podido actualizar el pago.'],
                    'encabezado' => 'Errores en el proceso:',
                ], 200);
            }
        }
    }

    public function filtrarMandamientos($valor, $filtro)
    {
        $mandamientos = [];
        switch ($filtro){
            case 1: $mandamientos = mandamiento_pago::whereHas('getAcuerdoPago', function($query) use ($valor){
                $query->whereHas('hasDeudor', function ($query2) use($valor){
                    $query2->where('numero_documento', $valor);
                });
            })->orWhereHas('getComparendo', function($query) use ($valor) {
                $query->whereHas('hasInfractor', function ($query2) use ($valor) {
                    $query2->where('numero_documento', $valor);
                });
            })->orderBy('created_at','desc')->paginate(50);
            break;
            case 2: $mandamientos = mandamiento_pago::whereHas('getAcuerdoPago', function($query) use ($valor){
                $query->where('numero_acuerdo', $valor);
            })->orWhereHas('getComparendo', function($query) use ($valor) {
                $query->where('numero', $valor);
            })->orderBy('created_at','desc')->paginate(50);
            break;
            case 3: $mandamientos = mandamiento_pago::where('consecutivo', $valor)->orderBy('created_at','desc')->paginate(50);
            break;
            case 4: $mandamientos = mandamiento_pago::whereHas('hasNotificaciones', function($query) use ($valor){
                $query->where('consecutivo', $valor);
            })->orderBy('created_at','desc')->paginate(50);
            break;
        }
        $filtros = [
            '1' => 'Número documento',
            '2' => 'Número proceso',
            '3' => 'Número mandamiento',
            '4' => 'Número notificación',
        ];
        $sFiltro = $filtro;
        return view('admin.coactivo.mandamientos.listadoMandamientos', ['mandamientos'=>$mandamientos, 'filtros'=>$filtros,'sFiltro'=>$sFiltro])->render();
    }
}
