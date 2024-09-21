<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\post;
use Validator;
use Storage;
use App\post_category;
use App\post_status;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Carbon;

class PostController extends Controller
{

    public function index()
    {
        $categorias = post_category::pluck('name', 'id');
        $estados = post_status::pluck('name', 'id');
        return view('admin.posts.administrar', ['estados' => $estados, 'categorias' => $categorias])->render();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $criterios = [
            '1' => 'Título',
            '2' => 'Categoría'
        ];
        $posts = post::all();
        return view('admin.posts.listadoPosts', ['posts'=>$posts, 'filtroPublicaciones' => $criterios, 'sFiltroPublicaciones' => null])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = post_category::pluck('name', 'id');
        $estados = post_status::pluck('name', 'id');
        return view('admin.posts.nuevoPost', ['estados' => $estados, 'categorias' => $categorias]);
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
            
        ], [
            
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);            
        }                

        try{
            $slug = $request->titulo;
            if ($request->slug != null) {
                $slug = $request->slug;
            }                    

            $slug = SlugService::createSlug(post::class, 'slug', $slug);

            $publishDate = date('Y-m-d H:i:s');
            if($request->publish_date != null){
                $publishDate = Carbon::createFromFormat('Y-m-d H:i', $request->publish_date)->toDateTimeString();
            }

            $unpublishDate = $request->fecha_despublicacion;
            if ($request->fecha_despublicacion != null) {
                $unpublishDate = Carbon::createFromFormat('Y-m-d H:i', $request->fecha_despublicacion)->toDateTimeString();
            }

            $post = post::create([
                'cover_image' => $request->image,
                'title' => $request->titulo,
                'slug' => $slug,
                'post' => $request->post_data,
                'tags' => $request->etiquetas,
                'resume' => $request->extracto,
                'published_date' => $publishDate,
                'unpublished_date' => $unpublishDate,
                'post_category_id' => $request->categoria,
                'post_status_id' => $request->estado,
                'author_id' => auth()->user()->id,
                'uuid' => Uuid::generate(5, str_random(5) . 'Posts-'. $slug . '-' . date('Y-m-d H:i:s'), Uuid::NS_DNS),
                'gallery_path' => $request->galeria
            ]);

            return $this->edit($post->id, 'Se ha creado la publicación.');
        }catch(\Exception $e){
            $request->flash();
            return back()->withErrors(['No se ha podido crear la publicación. Si el problema persiste, por favor comunicarse con soporte.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $success = null)
    {
        $post = post::find($id);
        $categorias = post_category::pluck('name', 'id');
        $estados = post_status::pluck('name', 'id');
        return view('admin.posts.editarPost', ['estados' => $estados, 'categorias' => $categorias, 'post' => $post, 'success' => $success])->render();
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

        ], [

        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try {
            $post = post::find($request->id);

            $slug = null;

            if ($request->titulo != $post->title) {
                if($request->slug == null){
                    $slug = SlugService::createSlug(post_category::class, 'slug', $request->slug);
                }else{
                    $slug = SlugService::createSlug(post_category::class, 'slug', $request->titulo);
                }                
            }

            $unpublishDate = $request->fecha_despublicacion;

            if ($request->fecha_despublicacion != null) {
                $unpublishDate = Carbon::createFromFormat('Y-m-d H:i', $request->fecha_despublicacion)->toDateTimeString();
            }

            if($request->image != null){
                $post->cover_image = $request->image;
            }
            
            $post->title = $request->titulo;
            $post->post = $request->post_data;
            $post->tags = $request->etiquetas;
            $post->resume = $request->extracto;
            $post->unpublished_date = $unpublishDate;
            $post->post_category_id = $request->categoria;
            $post->post_status_id = $request->estado;
            $post->gallery_path = $request->galeria;

            if ($request->publish_date != null) {
                $post->publish_date = $request->publish_date;
            }

            $post->save();

            return $this->edit($post->id, 'Se ha actualizado la publicación.');
        } catch (\Exception $e) {
            return back()->withErrors(['No se ha podido actualizar la publicación.']);
        }
    }
}
