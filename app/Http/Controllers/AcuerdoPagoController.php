<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\acuerdo_pago;
use App\acuerdo_pago_cuota;
use App\acuerdo_pago_deudor;
use App\comparendo;
use App\mandamiento_pago;
use App\usuario_tipo_documento;
use Validator;
use Illuminate\Validation\Rule;

class AcuerdoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filtros = [
            '1' => 'Número documento',
            '2' => 'Número proceso',
            '3' => 'Número acuerdo',
        ];
        $sFiltro = null;
        return view('admin.inspeccion.acuerdos_pagos.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro])->render();      
    }

    public function getAll()
    {
        $acuerdosPagos = acuerdo_pago::orderBy('fecha_acuerdo', 'desc')->paginate(50);
        return view('admin.inspeccion.acuerdos_pagos.listado', ['acuerdosPagos'=>$acuerdosPagos])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tiposDocumentos = usuario_tipo_documento::orderBy('name')->pluck('name','id');
        return view('admin.inspeccion.acuerdos_pagos.nuevo', ['tiposDocumentos'=>$tiposDocumentos])->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_acuerdo' => 'required|numeric',
            'valor_total' => 'required|numeric',
            'pago_inicial' => 'required|numeric',
            'acuerdo' => 'required|mimetypes:application/pdf|mimes:pdf|max:80000',
            'cant_cuotas' => 'required|numeric',
            'nombre_deudor' => 'required|string',
            'tipo_documento_deudor' => 'required|integer|exists:usuario_tipo_documento,id',
            'numero_documento_deudor' => 'required|numeric',
            'telefono_deudor' => 'required|numeric',
            'correo_deudor' => 'nullable|email',
            'direccion_deudor' => 'required|string',
            'tipo_acuerdo' => ['required','integer',Rule::in([1,2])],
            'procesos' => 'required|array|min:1',
            'fecha_acuerdo_submit' => 'required|date'
        ], [
            
        ]);

        if ($validator->fails()) {
            $request->flash();
            $tiposDocumentos = usuario_tipo_documento::orderBy('name')->pluck('name','id');
            return view('admin.inspeccion.acuerdos_pagos.nuevo', ['tiposDocumentos'=>$tiposDocumentos])->withErrors($validator->errors()->all())->render();
        }
        $documentoAcuerdo = null;
        try{    
            \DB::beginTransaction();
            $acuerdoPago = acuerdo_pago::create([
                'numero_acuerdo' => $request->numero_acuerdo,
                'valor_total' => $request->valor_total,
                'pago_inicial' => $request->pago_inicial,
                'cuotas' => $request->cant_cuotas,
                'fecha_acuerdo' => $request->fecha_acuerdo_submit,
                'incumplido' => false,
                'cancelado' => false,
                'vigente' => true
            ]);

            $documentoAcuerdo = \Storage::disk('acuerdosPagos')->putFile($acuerdoPago->id, $request->acuerdo);
            $acuerdoPago->acuerdo = $documentoAcuerdo;
            $acuerdoPago->save();

            for($i = 1; $i <= $request->cant_cuotas; $i++){
                acuerdo_pago_cuota::create([
                    'valor' => $request->input('cuota'.$i.'_valor'),
                    'fecha_vencimiento' => $request->input('cuota'.$i.'_fecha_vencimiento_submit'),
                    'pendiente' => true,
                    'acuerdo_pago_id' => $acuerdoPago->id
                ]);
            }

            acuerdo_pago_deudor::create([
                'nombre' => $request->nombre_deudor,
                'tipo_documento_id' => $request->tipo_documento_deudor,
                'numero_documento' => $request->numero_documento_deudor,
                'telefono' => $request->telefono_deudor,
                'correo_electronico' => $request->correo_deudor,
                'direccion' => $request->direccion_deudor,
                'acuerdo_pago_id' => $acuerdoPago->id
            ]);

            if($request->tipo_acuerdo == 1){
                $comparendos_validos = [];
                $comparendos_invalidos = [];
                foreach($request->procesos as $proceso){
                    $comparendo = comparendo::where('numero', $proceso)->first();
                    if($comparendo->hasAcuerdoPago->count() > 0 || $comparendo->hasMandamientoPago != null || $comparendo->hasPago != null){
                        array_push($comparendos_invalidos, $proceso);
                    }else{
                        array_push($comparendos_validos, $comparendo->id);
                    }
                }
                if(count($comparendos_validos) == 0){
                    \DB::rollBack();
                    \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear el acuerdo de pago debido a que ninguno de los comparendos especificados son válidos.'],
                        'encabezado' => 'Error en el proceso.',
                    ], 200);
                }
                foreach ($comparendos_validos as $comparendo){
                    \DB::table('acuerdo_pago_proceso')->insert([
                        'acuerdo_pago_id' => $acuerdoPago->id,
                        'proceso_id' => $comparendo,
                        'proceso_type' => 'App\\comparendo'
                    ]);
                }
            }else{
                $mandamientos_validos = [];
                $mandamientos_invalidos = [];
                foreach($request->procesos as $proceso){
                    $mandamiento = mandamiento_pago::where('consecutivo', $proceso)->first();
                    if($mandamiento->hasAcuerdoPago->count() > 0 || $mandamiento->hasFinalizacion != null || $mandamiento->hasPago != null){
                        array_push($mandamientos_invalidos, $proceso);
                    }else{
                        array_push($mandamientos_validos, $mandamiento->id);
                    }
                }
                if(count($mandamientos_validos) == 0){
                    \DB::rollBack();
                    \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear el acuerdo de pago debido a que ninguno de los cmandamientos especificados son válidos.'],
                        'encabezado' => 'Error en el proceso.',
                    ], 200);
                }
                foreach ($mandamientos_validos as $mandamiento){
                    \DB::table('acuerdo_pago_proceso')->insert([
                        'acuerdo_pago_id' => $acuerdoPago->id,
                        'proceso_id' => $mandamiento,
                        'proceso_type' => 'App\\mandamiento_pago'
                    ]);
                }
            }

            \DB::commit();
            $message = null;
            if($request->tipo_acuerdo == 1){
                if(count($comparendos_invalidos) > 0){
                    $comparendos = null;
                    foreach ($comparendos_invalidos as $comparendo){
                        $comparendos = $comparendos + '<li>'.$comparendo.'</li>';
                    }
                    $message = 'Se ha registrado el acuerdo de pago, pero algunos comparendos no pudieron ser vinculados: <br><ul>'.$comparendos.'</ul>';
                }else{
                    $message = 'Se ha registrado el acuerdo de pago.';
                }
            }else{
                if(count($mandamientos_invalidos) > 0){
                    $mandamientos = null;
                    foreach ($mandamientos_invalidos as $mandamiento){
                        $mandamientos = $mandamientos + '<li>'.$mandamiento.'</li>';
                    }
                    $message = 'Se ha registrado el acuerdo de pago, pero algunos mandamientos no pudieron ser vinculados: <br><ul>'.$mandamientos.'</ul>';
                }else{
                    $message = 'Se ha registrado el acuerdo de pago.';
                }
            }
            return response()->view('admin.mensajes.success', [
                'mensaje' => $message,
                'encabezado' => '¡Completado!',
            ], 200);    
        }catch(\Exception $e){
            \DB::rollBack();
            \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido crear el acuerdo de pago.'],
                'encabezado' => 'Error en el proceso.',
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tiposDocumentos = usuario_tipo_documento::orderBy('name')->pluck('name','id');
        $acuerdoPago = acuerdo_pago::find($id);
        return view('admin.inspeccion.acuerdos_pagos.editar', ['tiposDocumentos'=>$tiposDocumentos, 'acuerdoPago'=>$acuerdoPago])->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:acuerdo_pago,id',
            'numero_acuerdo' => 'required|numeric',
            'valor_total' => 'required|numeric',
            'pago_inicial' => 'required|numeric',
            'cant_cuotas' => 'required|numeric',
            'nombre_deudor' => 'required|string',
            'tipo_documento_deudor' => 'required|integer|exists:usuario_tipo_documento,id',
            'numero_documento_deudor' => 'required|numeric',
            'telefono_deudor' => 'required|numeric',
            'correo_deudor' => 'nullable|email',
            'direccion_deudor' => 'required|string',
            'comparendos' => 'required|array|min:1',
            'fecha_acuerdo_submit' => 'required|date'
        ], [

        ]);

        if ($validator->fails()) {
            $request->flash();
            $tiposDocumentos = usuario_tipo_documento::orderBy('name')->pluck('name','id');
            return view('admin.inspeccion.acuerdos_pagos.nuevo', ['tiposDocumentos'=>$tiposDocumentos])->withErrors($validator->errors()->all())->render();
        }

        $documentoAcuerdo = null;

        try{    
            \DB::beginTransaction();
            $acuerdoPago = acuerdo_pago::find($request->id);
            $acuerdoPago->numero_acuerdo = $request->numero_acuerdo;
            $acuerdoPago->valor_total = $request->valor_total;
            $acuerdoPago->pago_inicial = $request->pago_inicial;
            $acuerdoPago->cuotas = $request->cant_cuotas;
            $acuerdoPago->fecha_acuerdo = $request->fecha_acuerdo_submit;
            $acuerdoPago->save();

            $deudor = $acuerdoPago->hasDeudor;
            $deudor->nombre = $request->nombre_deudor;
            $deudor->tipo_documento_id = $request->tipo_documento_deudor;
            $deudor->numero_documento = $request->numero_documento_deudor;
            $deudor->telefono = $request->telefono_deudor;
            $deudor->correo_electronico = $request->correo_deudor;
            $deudor->direccion = $request->direccion_deudor;
            $deudor->save();

            if($request->acuerdo != null){
                $documentoAcuerdo = \Storage::disk('acuerdosPagos')->putFile($acuerdoPago->id, $request->acuerdo);
                $acuerdoPago->acuerdo = $documentoAcuerdo;
            }

            $acuerdoPago->hasComparendos()->detach();
            $acuerdoPago->hasmandamientosPagos()->detach();

            if($request->tipo_acuerdo == 1){
                $comparendos_validos = [];
                $comparendos_invalidos = [];
                foreach($request->procesos as $proceso){
                    $comparendo = comparendo::where('numero', $proceso)->first();
                    if($comparendo->hasAcuerdoPago->count() > 0 || $comparendo->hasMandamientoPago != null){
                        array_push($comparendos_invalidos, $proceso);
                    }else{
                        array_push($comparendos_validos, $comparendo->id);
                    }
                }
                if(count($comparendos_validos) == 0){
                    \DB::rollBack();
                    \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear el acuerdo de pago debido a que ninguno de los comparendos especificados son válidos.'],
                        'encabezado' => 'Error en el proceso.',
                    ], 200);
                }
                foreach ($comparendos_validos as $comparendo){
                    \DB::table('acuerdo_pago_proceso')->insert([
                        'acuerdo_pago_id' => $acuerdoPago->id,
                        'proceso_id' => $comparendo,
                        'proceso_type' => 'App\\comparendo'
                    ]);
                }
            }else{
                $mandamientos_validos = [];
                $mandamientos_invalidos = [];
                foreach($request->procesos as $proceso){
                    $mandamiento = mandamiento_pago::where('consecutivo', $proceso)->first();
                    if($mandamiento->hasAcuerdoPago->count() > 0 || $mandamiento->hasFinalizacion != null){
                        array_push($mandamientos_invalidos, $proceso);
                    }else{
                        array_push($mandamientos_validos, $mandamiento->id);
                    }
                }
                if(count($mandamientos_validos) == 0){
                    \DB::rollBack();
                    \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
                    return response()->view('admin.mensajes.errors', [
                        'errors' => ['No se ha podido crear el acuerdo de pago debido a que ninguno de los cmandamientos especificados son válidos.'],
                        'encabezado' => 'Error en el proceso.',
                    ], 200);
                }
                foreach ($mandamientos_validos as $mandamiento){
                    \DB::table('acuerdo_pago_proceso')->insert([
                        'acuerdo_pago_id' => $acuerdoPago->id,
                        'proceso_id' => $mandamiento,
                        'proceso_type' => 'App\\mandamiento_pago'
                    ]);
                }
            }

            \DB::commit();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el acuerdo de pago.',
                'encabezado' => '¡Completado!',
            ], 200);    
        }catch(\Exception $e){
            \DB::rollBack();
            if($documentoAcuerdo != null){
                \Storage::disk('acuerdosPagos')->delete($documentoAcuerdo);
            }
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el acuerdo de pago.'],
                'encabezado' => 'Error en el proceso.',
            ], 200);
        }
    }

    public function getCuotas($id)
    {
        $acuerdoPago = acuerdo_pago::find($id);
        return view('admin.inspeccion.acuerdos_pagos.cuotas', ['cuotas'=>$acuerdoPago->hasCuotas])->render();
    }

    public function editCuota($id)
    {
        $cuota = acuerdo_pago_cuota::find($id);
        return view('admin.inspeccion.acuerdos_pagos.editarCuota', ['cuota'=>$cuota])->render();
    }

    public function updateCuota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:acuerdo_pago_cuota,id',
            'valor' => 'required|numeric',
            'fecha_vencimiento_submit' => 'required|date'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $cuota = acuerdo_pago_cuota::find($request->id);
            $cuota->valor = $request->valor;
            $cuota->fecha_vencimiento = $request->fecha_vencimiento_submit;
            $cuota->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la cuota.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar la cuota.'],
                'encabezado' => 'Error en la creación',
            ], 200);
        }
    }

    public function pagarCuota($id)
    {
        $cuota = acuerdo_pago_cuota::find($id);
        return view('admin.inspeccion.acuerdos_pagos.pagarCuota', ['cuota' => $cuota])->render();
    }

    public function registrarPagoCuota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'fecha_pago_submit' => 'required|date',
            'consignacion' => 'required|mimetypes:application/pdf',
            'factura_sintrat' => 'required|mimetypes:application/pdf',
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $cuota = acuerdo_pago_cuota::find($request->id);
            if($cuota->fecha_pago != null){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['La cuota ya tiene un pago registrado.'],
                    'encabezado' => 'Errores en la validación:',
                ], 200);
            }
            $cuota->consignacion_factura = \Storage::disk('acuerdosPagos')->putFile($cuota->acuerdo_pago_id, $request->consignacion);
            $cuota->factura_sintrat = \Storage::disk('acuerdosPagos')->putFile($cuota->acuerdo_pago_id, $request->factura_sintrat);
            $cuota->fecha_pago = $request->fecha_pago_submit;
            $cuota->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha registrado el pago.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido registrar el pago.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function editPagoCuota($id)
    {
        $cuota = acuerdo_pago_cuota::find($id);
        return view('admin.inspeccion.acuerdos_pagos.editarPagoCuota', ['cuota' => $cuota])->render();
    }

    public function updatePagoCuota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:mandamiento_pago,id',
            'fecha_pago_submit' => 'required|date',
            'consignacion' => 'mimetypes:application/pdf',
            'factura_sintrat' => 'mimetypes:application/pdf',
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $cuota = acuerdo_pago_cuota::find($request->id);
            if($request->consignacion != null){
                $cuota->consignacion_factura = \Storage::disk('acuerdosPagos')->putFile($cuota->acuerdo_pago_id, $request->consignacion);
            }

            if($request->factura_sintrat != null){
                $cuota->factura_sintrat = \Storage::disk('acuerdosPagos')->putFile($cuota->acuerdo_pago_id, $request->factura_sintrat);
            }

            $cuota->fecha_pago = $request->fecha_pago_submit;
            $cuota->save();
            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el pago.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['No se ha podido actualizar el pago.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
    }

    public function obtenerConsignacionCuota($id)
    {
        $cuota = acuerdo_pago_cuota::find($id);
        $name = explode('/', $cuota->consginacion_factura);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/acuerdosPagos/'.$cuota->consignacion_factura), array_last($name), $headers);
    }

    public function obtenerFacturaCuota($id)
    {
        $cuota = acuerdo_pago_cuota::find($id);
        $name = explode('/', $cuota->factura_sintrat);
        $headers = [
            'Content-Type: application/pdf',
            'Content-Disposition: attachment; filename="'.array_last($name).'"',
        ];

        return Response()->download(storage_path('app/acuerdosPagos/'.$cuota->factura_sintrat), array_last($name), $headers);
    }

    public function verDeudor($id)
    {
        $deudor = acuerdo_pago_deudor::where('acuerdo_pago_id', $id)->first();
        return view('admin.inspeccion.acuerdos_pagos.verDeudor', ['deudor'=>$deudor])->render();
    }

    public function filtrarAcuerdosPagos($valor, $filtro)
    {
        $acuerdosPagos = [];
        switch ($filtro){
            case 1: $acuerdosPagos = acuerdo_pago::whereHas('getComparendos', function($query) use ($valor){
                $query->whereHas('hasInfractor', function ($query2) use($valor){
                    $query2->where('numero_documento', $valor);
                })->orWhereHas('hasVehiculo', function ($query3) use ($valor) {
                    $query3->where('prop_numero_documento', $valor);
                });
            })->orWhereHas('getMandamientosPagos', function($query) use ($valor) {
                $query->whereHas('getComparendo', function ($query2) use ($valor) {
                    $query2->whereHas('hasVehiculo', function ($query3) use ($valor) {
                        $query3->where('prop_numero_documento', $valor);
                    })->orWhereHas('hasInfractor', function ($query4) use ($valor) {
                        $query4->where('numero_documento', $valor);
                    });
                });
            })->orWhereHas('hasDeudor', function($query) use ($valor){
                $query->where('numero_documento', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 2: $acuerdosPagos = acuerdo_pago::whereHas('getComparendos', function($query) use ($valor){
                $query->where('numero', $valor);
            })->orWhereHas('getMandamientosPagos', function($query) use ($valor) {
                $query->where('consecutivo', $valor);
            })->orderBy('created_at','desc')->paginate(50);
                break;
            case 3: $acuerdosPagos = acuerdo_pago::where('numero_acuerdo', $valor)->orderBy('created_at','desc')->paginate(50);
                break;
        }

        return view('admin.inspeccion.acuerdos_pagos.listado', ['acuerdosPagos'=>$acuerdosPagos])->render();
    }
}
