<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoactivoComparendo;
use Carbon\Carbon;
use App\CoactivoFotoMultas;
use App\normativa;
use App\notificacion_aviso;
use App\post;
use App\post_category;
use SEOMeta;
use OpenGraph;
use Twitter;
use SEO;

class HomeController extends Controller
{
    public function index()
    {
        $meses = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ];

        return view('publico.welcome', ['meses' => $meses]);
    }

    public function getNotificacionesAvisoPresenteAnio()
    {
        $notificacionesAviso = notificacion_aviso::with('hasTipoNotificacion')->orderBy('fecha_publicacion', 'desc')->paginate(25);

        return view('publico.notificacionesAviso.listadoNotificacionesAviso', ['notificacionesAviso' => $notificacionesAviso])->render();
    }

    public function getLatestPost()
    {
        $posts = post::whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->orderBy('published_date', 'desc')->get()->take(18);

        return view('publico.posts.ultimasPublicaciones', ['posts' => $posts])->render();
    }

    public function getPostBySlug($categoria, $slug)
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $post = post::whereHas('hasCategoria', function ($query) use ($categoria) {
            $query->where('slug', $categoria);
        })->whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->where('slug', $slug)->first();

        if($post != null){
            $related = $this->getRelatedPosts($post);

            SEOMeta::setDescription($post->resume);
            SEOMeta::addMeta('article:published_time', $post->published_date->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', $post->hasCategoria->name, 'property');
            SEOMeta::addKeyword($post->tags);

            OpenGraph::setDescription($post->resume);
            OpenGraph::setTitle($post->title);
            OpenGraph::setUrl(url('post/'.$post->hasCategoria->slug.'/'.$post->slug));
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', 'es-co');
            OpenGraph::addProperty('locale:alternate', ['es-co', 'es-es']);

            OpenGraph::addImage(asset($post->cover_image));
            OpenGraph::addImage(['url' => asset($post->cover_image), 'size' => 300]);
            OpenGraph::addImage(asset($post->cover_image), ['height' => 300, 'width' => 300]);

            Twitter::setTitle($post->title);
            Twitter::setSite('@');

            OpenGraph::setTitle('Article')
                ->setDescription($post->resume)
                ->setType('article')
                ->setArticle([
                    'published_time' => $post->published_date->toW3CString(),
                    'modified_time' => $post->updated_at->toW3CString(),
                    'section' => $post->hasCategoria->name,
                    'tag' => $post->tags,
                ]);
        }

        return view('publico.posts.viewPost', ['post' => $post, 'categorias' => $categorias, 'archive' => $archive, 'related' => $related])->render();
    }

    public function getPostsByCategory($categoria)
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $post = post::whereHas('hasCategoria', function ($query) use ($categoria) {
            $query->where('slug', $categoria);
        })->whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->orderBy('published_date', 'desc')->paginate(18);

        return view('publico.posts.viewPosts', ['posts' => $post, 'categorias' => $categorias, 'archive' => $archive])->render();
    }

    public function getAllPosts()
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $posts = post::whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->orderBy('published_date', 'desc')->paginate(18);
        return view('publico.posts.viewPosts', ['posts' => $posts, 'categorias' => $categorias, 'archive' => $archive])->render();
    }

    public function getPostsByYm($y, $m)
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $date = Carbon::createFromFormat('Y-m-d', $y.'-'.$m.'-01');
        $posts = post::whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->whereBetween('published_date', [$date->toDateTimeString(), $y.'-'.$m.'-'.$date->daysInMonth.' 11:59:59'])->orderBy('published_date', 'desc')->paginate(18);
        return view('publico.posts.viewPosts', ['posts' => $posts, 'categorias' => $categorias, 'archive' => $archive])->render();
    }

    private function getArchive()
    {
        \Jenssegers\Date\Date::setLocale('es');
        $archive = [];
        $date = \Jenssegers\Date\Date::now();
        for($i = 0; $i < 12; $i++){
            $total = post::whereBetween('published_date', [$date->year . '-' . $date->month . '-1', $date->year . '-' . $date->month . '-' . $date->daysInMonth])->count();
            $archive[$i] = [$date->format('F Y').' ('.$total.')', $date->year.'/'.$date->month];
            $date->subMonth(1);
        }
        return $archive;
    }

    private function getRelatedPosts($post)
    {
        $posts = post::where('title', 'like', '%'.$post->title.'%')->where('slug', '!=', $post->slug)->orderBy('published_date', 'desc')->get()->take(3);
        if($posts->count() <= 0){
        $posts = post::wherehas('hasCategoria', function($query) use ($post){
                $query->where('slug', $post->hasCategoria->slug);
            })->where('slug', '!=', $post->slug)->orderBy('published_date', 'desc')->get()->take(3);
        }
        return $posts;
    }

    public function getPostsByTag($tag)
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $post = post::whereHas('hasEstado', function($query){
            $query->where('show_post', 1);
        })->where('tags', 'like', '%'.$tag.'%')->orderBy('published_date', 'desc')->paginate(18);
        return view('publico.posts.viewPosts', ['posts' => $post, 'categorias' => $categorias, 'archive' => $archive])->render();
    }

    public function getPostsByQuery(Request $request)
    {
        $archive = $this->getArchive();
        $categorias = post_category::all();
        $posts = post::whereHas('hasEstado', function($query) use ($request){
            $query->where('show_post', 1);
        })->where('title', 'like', '%'.$request->input('query').'%')->paginate(18);
        return view('publico.posts.viewPosts', ['posts' => $posts, 'categorias' => $categorias, 'archive' => $archive])->render();
    }

    public function getNormativas()
    {
        $normativas = normativa::with('hasTipo')->orderBy('fecha_expedicion', 'desc')->paginate(25);

        return view('publico.normativas.listadoNormativas', ['normativas' => $normativas])->render();
    }
}
