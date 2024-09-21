@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<title>Editar Publicación</title>
@endsection
 
@section('styles')
<style>
    .pickadate-root {
        position: relative;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/jquery-tag-editor/jquery.tag-editor.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/datetimepicker/build/jquery.datetimepicker.min.css')}}"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
<link href="vendor/tam-emoji/css/emoji.css" rel="stylesheet">
@endsection
 
@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (isset($success))
    <div class="alert alert-success" role="alert"><strong>{!! $success !!}</div>
 @endif        
<div class="container-fluid">
    <div class="row">        
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar Publicación</div>
                <div class="card-body">
                    <form enctype="multipart/form-data" method="POST" action="{{url('/admin/posts/editarPublicacion')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$post->id}}">
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="group col-lg-4">
                                <label class="control-label">Título</label>
                                <input type="text" class="form-control" name="titulo" required value="{{$post->title}}">
                            </div>
                            <div class="group col-lg-4">
                                <label class="control-label">Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{$post->slug}}">
                            </div>
                            <div class="group col-lg-4">
                                <label class="control-label">Fecha publicación</label>
                                <input type="text" class="form-control" name="fecha_publicacion" id="fecha_publicacion" value="{{$post->published_date->format('Y-m-d H:i')}}">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="group col-lg-4">
                                <label class="control-label">Categoría</label> {{Form::select('categoria', $categorias, $post->post_categry_id, ['class'=>'form-control',
                                'id'=>'categoria', 'required'])}}
                            </div>
                            <div class="group col-lg-4">
                                <label class="control-label">Estado</label> {{Form::select('estado', $estados, $post->post_status_id, ['class'=>'form-control',
                                'id'=>'estado', 'required'])}}
                            </div>
                            <div class="group col-lg-4">
                                <label class="control-label">Fecha despublicación</label>
                                <input type="text" class="form-control" name="fecha_despublicacion" id="fecha_despublicacion" @if($post->unpublished_date != null) value="{{$post->unpublished_date->format('Y-m-d H:i')}}" @endif>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="form-group col-lg-12">
                                <textarea id="post_data" name="post_data" required></textarea>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="form-group col-lg-12">
                                <label class="control-label">Imágen de portada</label>
                                <div class="input-group">                                    
                                    <input type="text" id="image_label" class="form-control" name="image"
                                        aria-label="Image" aria-describedby="button-image" value="{{ $post->cover_image }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="button-image">Seleccionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="control-label">Galería (directorio)</label>
                                <input type="text" id="galeria" class="form-control" name="galeria" value="{{ $post->gallery_path }}">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="form-group col-lg-12">
                                <label class="control-label">Extracto</label>
                                <textarea id="extracto" class="form-control" name="extracto">{{$post->resume}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="control-label">Etiquetas</label>
                                <input type="text" id="etiquetas" class="form-control" name="etiquetas" value="{{$post->tags}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <input type="submit" class="btn btn-success" value="Guardar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('js/vendor/summernote/lang/summernote-es-ES.js')}}"></script>
<script src="{{asset('js/vendor/jquery-tag-editor/jquery.tag-editor.min.js')}}"></script>
<script src="{{asset('js/vendor/jquery-tag-editor/jquery.caret.min.js')}}"></script>
<script src="{{asset('js/vendor/datetimepicker/build/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('vendor/summernote-table-headers-master/summernote-table-headers.js')}}"></script>
<script type="text/javascript" src="{{asset('js/posts/editarPost.js')}}"></script>
@if(old('post_data') != null)
<script type="text/javascript">
setData({!!old('post_data') !!});
</script>
@else 
<script type="text/javascript">
setData({!!json_encode($post->post) !!});
</script>
@endif
@endsection