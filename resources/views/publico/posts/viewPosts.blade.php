<?php 
\Jenssegers\Date\Date::setLocale('es');
?>
@extends('layouts.app') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Publicaciones - {{Setting::get('empresa_sigla')}}</title>
@endsection
 
@section('styles')
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

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

    .footer-box {
        height: 1em;
        border-top: 1px solid #ddd;
    }

    .card-title{
        font-weight: bold;
    }

    .card-img-top{
        width: 100%;
        height: 30em;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        display: block;
    }

    .body-box img{
        max-width: 100%;
    }

    .card img{
        padding: 0.25rem;
    }
</style>
@endsection
 
@section('content')
<div class="container">
    <div class="row row-eq-height">
        <div class="col-lg-9">
            <div class="dashboard-box">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h1>Publicaciones</h1>
                </div>
                <div class="body-box" style="padding:1em 0">
                    @if($posts->count() > 0)
                        @foreach ($posts as $post)
                        <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <a class="text-dark" href="/posts/{{ $post->hasCategoria->slug }}/{{$post->slug}}">{{$post->title}}</a>
                                </h3>
                                <div class="mb-1 text-muted">{{ \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('l j F Y')}} - <a class="noticia_link" href="posts/{{$post->hasCategoria->slug}}">{{$post->hasCategoria->name}}</a></div>
                                <p class="card-text mb-auto">{{ $post->resume }}</p>
                                <a href="/posts/{{$post->hasCategoria->slug}}/{{$post->slug}}">Continuar leyendo</a>
                            </div>
                            <img class="bd-placeholder-img card-img-right flex-auto d-none d-lg-block" width="200" height="250" src="{{ asset($post->cover_image) }}"></img>
                        </div>
                        @endforeach
                    @else
                        No se han encontrado Publicaciones.
                    @endif
                    <div class="text-center">
                        {{$posts->links('vendor.pagination.bootstrap-4')}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
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
</div>
@endsection