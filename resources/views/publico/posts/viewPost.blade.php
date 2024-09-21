<?php 
\Jenssegers\Date\Date::setLocale('es');
?>
@extends('layouts.app') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{$post->title}}</title>
{!! SEO::generate(true) !!}
@endsection
 
@section('styles')
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
<link rel="stylesheet" href="{{asset('vendor/Gallery-master/css/blueimp-gallery.min.css')}}" />

<style>
    .center-th {
        text-align: center;
        vertical-align: middle !important;
    }

    .iconos {
        text-align: center;
    }

    body {
        background-color: #DADADA;
    }

    .footer-box{
        min-height: 1em;
        border-top: 1px solid #ddd;
        padding: 5px;
    }

    .body-box img{
        max-width: 100%;
    }

    .card{
        margin: 5px;
        display: inline-block;
    }
</style>
@endsection
 
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="dashboard-box">
                @if($post != null)
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h2>{{$post->title}}</h2>
                    {{ \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('F j, Y')}} - <a class="noticia_link" href="posts/{{$post->hasCategoria->slug}}">{{$post->hasCategoria->name}}</a>
                    <hr>
                </div>
                <div class="body-box" style="padding:1em 0">
                    {!! $post->post !!}
                    @if($post->gallery_path != null)
                        <?php 
                        $imagenes = array_filter(Storage::files('public'.$post->gallery_path), function ($file){
                                        return preg_match('/\.(jpg|png|jpeg|bmp)$/', $file);
                        });
                        ?>
                        @if(count($imagenes) > 0)
                        <h4>Galería</h4>
                        <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
                            <div class="slides"></div>
                            <h3 class="title"></h3>
                            <a class="prev">‹</a>
                            <a class="next">›</a>
                            <a class="close">×</a>
                            <a class="play-pause"></a>
                            <ol class="indicator"></ol>
                        </div>
                        <div id="links" class="links">                        
                        @foreach ($imagenes as $file)
                        <a href="{{str_replace('public', '/storage', $file)}}" title="{{$post->title.'_'.$loop->index}}" data-gallery>
                            <img src="{{str_replace('public', '/storage', $file)}}" alt="{{$post->title.'_'.$loop->index}}" style="width:75px;height:75px"/>
                        </a>  
                        @endforeach
                        </div>
                        @endif
                    @endif
                </div>
                @if($post->tags != null)
                <div class="footer-box">
                    <h5>Etiquetas</h5>
                    @foreach(explode(',', $post->tags) as $tag)
                    <a href="/posts/tag/{{$tag}}" class="btn btn-sm btn-outline-primary">{{$tag}}</a> @endforeach
                </div>
                @endif
                @else 
                <h2>La publicación no existe o no está disponible.</h2>
                @endif                
            </div>   
            <div class="dashboard-box" style="margin-top: 2em;">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h3>Publicaciones relacionadas</h3>
                </div>
                <div class="body-box mx-auto" style="padding: 1em; min-height:1em;">                    
                    @foreach ($related as $post)
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset($post->cover_image) }}" alt="{{$post->title}}" class="card-img-top" alt="{{$post->title}}">
                        <div class="card-body">
                            <h5 class="card-title">{{$post->title}}</h5>
                            <p class="card-text"><small class="text-muted">{{\Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('F jS')}} | {{ \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->published_date)->diffForHumans()}}</small>|
                                <a class="noticia_link" href="posts/{{$post->hasCategoria->slug}}"><small class="text-muted">{{$post->hasCategoria->name}}</small></a></p>
                            <a href="/posts/{{$post->hasCategoria->slug}}/{{$post->slug}}" class="btn btn-primary">Leer más</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>         
        </div>
        <div class="col-lg-2">
            <div class="dashboard-box" style="height: 100%;">
                <div class="body-box">
                    <form method="POST" action="{{url('posts/search')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="button-search" name="query">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit" id="button-search" title="Buscar"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <h4>Categorías</h4>                    
                    <ul class="list-unstyled">
                        @foreach ($categorias as $categoria)
                        <li><a href="{{url('posts/'.$categoria->slug)}}">{{$categoria->name}}</a></li>
                        @endforeach
                    </ul>  
                    <h4>Archivo</h4>
                    <ul class="list-unstyled">
                        @foreach ($archive as $date)
                        <li><a href="{{url('posts/date/'.$date[1])}}">{{$date[0]}}</a></li>
                        @endforeach
                    </ul>    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('vendor/Gallery-master/js/blueimp-gallery.min.js')}}"></script>  
<script type="text/javascript" src="{{asset('js/publico/posts/viewPost.js')}}"></script>
@endsection