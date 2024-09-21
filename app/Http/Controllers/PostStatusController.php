<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\post_status;
use Validator;
use Storage;
use Illuminate\Validation\Rule;

class PostStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $criterios = [
            '1' => 'Nombre',
        ];
        $postStatus = post_status::all();
        return view('admin.posts.listadoEstados', ['estados' => $postStatus, 'filtroEstados' => $criterios, 'sFiltroEstado' => null])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.nuevoEstado')->render();
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
            'nombre' => 'required|string|unique:post_status,name',
            'visibilidad' => ['required', Rule::in(['1', '0'])]
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            post_status::create([
                'name' => $request->nombre,
                'show_post' => $request->visibilidad
            ]);

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado el estado',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'No se ha podido crear el estado. Si el problema persiste, por favor comunicarse con soporte.',
                'encabezado' => '¡Error!',
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
        $status = post_status::find($id);
        return view('admin.posts.editarEstado', ['estado' => $status])->render();
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
            'id' => 'required|integer|exists:post_status,id', 
            'nombre' => ['required', 'string', Rule::unique('post_status', 'name')->ignore($request->id)],
            'visibilidad' => ['required', Rule::in(['1', '0'])]
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try {
            $status = post_status::find($request->id);
            $status->name = $request->nombre;
            $status->show_post = $request->visibilidad;
            $status->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado el estado',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'No se ha podido actualizar el estado. Si el problema persiste, por favor comunicarse con soporte.',
                'encabezado' => '¡Error!',
            ], 200);
        }
    }
}
