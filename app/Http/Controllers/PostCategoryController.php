<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\post_category;
use Validator;
use Storage;
use Illuminate\Validation\Rule;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $criterios = [
            '1' => 'Nombre'
        ];
        $categories = post_category::all();
        return view('admin.posts.listadoCategorias', ['categorias' => $categories, 'criterios' => $criterios, 'sFiltroCategorias' => null])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = post_category::pluck('name', 'id');
        $categories->prepend('Ninguna', '');
        return view('admin.posts.nuevaCategoria', ['categorias' => $categories])->render();
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
            'nombre' => 'required|string|unique:post_category,name',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|mimes:jpeg,bmp,png',
            'categoria_superior' => 'nullable|integer|exists:post_category,id'
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $slug = $request->nombre;
            if($request->slug != null){
                $slug = $request->slug;
            }
            
            $category = post_category::create([
                'name' => $request->nombre,
                'slug' => SlugService::createSlug(post_category::class, 'slug', $slug),
                'description' => $request->descripcion,
                'parent_category_id' => $request->categoria_superior 
            ]);

            if($request->icono != null){
                $category->icon = Storage::disk('post')->putFile('/categories', $request->icono);
                $category->save();
            }

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha creado la categoría',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch(\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'No se ha podido crear la categoría. Si el problema persiste, por favor comunicarse con soporte.',
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
        $categories = post_category::pluck('name', 'id');
        $categories->prepend('Ninguna', '');
        $category = post_category::find($id);
        return view('admin.posts.editarCategoria', ['categoria' => $category, 'categorias' => $categories])->render();
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
            'id' => 'required|integer|exists:post_category,id',
            'nombre' => ['required','string', Rule::unique('post_category', 'name')->ignore($request->id)],
            'icono' => 'nullable|mimes:jpeg,bmp,png',
            'categoria_superior' => 'nullable|integer|exists:post_category,id'
        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try {
            $category = post_category::find($request->id);
            $category->name = $request->nombre;
            $category->description = $request->descripcion;
            $category->parent_category_id = $request->categoria_superior;

            if ($request->imagen_portada != null) {
                $post->cover_image = Storage::disk('post')->putFile('/posts', $request->imagen_portada);
            }

            if($request->slug != $category->slug && $request->slug != null){
                $category->slug = SlugService::createSlug(post::class, 'slug', $request->slug);                
            }

            $category->save();

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha actualizado la categoría',
                'encabezado' => '¡Completado!',
            ], 200);
        } catch (\Exception $e) {
            return response()->view('admin.mensajes.errors', [
                'No se ha podido actualizar la categoría. Si el problema persiste, por favor comunicarse con soporte.',
                'encabezado' => '¡Error!',
            ], 200);
        }
    }
}
