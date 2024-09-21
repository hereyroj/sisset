@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<title>Administrar Publicaciones</title>
@endsection
 
@section('styles')
<style>
    .pickadate-root {
        position: relative;
    }

    .btn-actualizar {
        border-radius: 0 !important;
        min-height: 40px !important;
    }

    .cabecera-tabla {
        min-height: 40px;
        max-height: 40px;
    }

    .cabecera-tabla div {
        float: left;
        display: block;
    }

    .field-search {
        background-color: #5cb85c;
        padding: 4px 6px;
        width: 378px;
        min-height: 40px !important;
    }

    .field-search input {
        border: none;
        width: 300px;
        min-height: 32px !important;
        vertical-align: middle;
        padding-left: 5px;
    }

    .btn-buscar {
        width: 32px;
        height: 32px;
        background-color: #2e6da4;
        color: #fff;
        border: none;
        vertical-align: middle;
        margin-left: -3px;
    }

    .btn-restaurar {
        width: 32px;
        height: 32px;
        background-color: #d43f3a;
        color: #fff;
        border: none;
        vertical-align: middle;
        margin-left: -4px;
    }

    td {
        text-align: center;
    }
</style>
<link href="{{asset('js/vendor/summernote/summernote-bs4.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/jquery-tag-editor/jquery.tag-editor.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/vendor/datetimepicker/build/jquery.datetimepicker.min.css')}}" />
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Publicaciones</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="publicaciones" data-toggle="tab" aria-selected="true" href="#publicaciones"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="categorias" data-toggle="tab" aria-selected="false" href="#categorias"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Categorías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="estados" data-toggle="tab" aria-selected="false" href="#estados"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Estados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="publicaciones" class="tab-pane fade show active" role="tabpanel"></div>
                        <div id="categorias" class="tab-pane fade" role="tabpanel"></div>
                        <div id="estados" class="tab-pane fade" role="tabpanel"></div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{asset('js/vendor/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('js/vendor/summernote/lang/summernote-es-ES.js')}}"></script>
<script src="{{asset('js/vendor/jquery-tag-editor/jquery.tag-editor.min.js')}}"></script>
<script src="{{asset('js/vendor/jquery-tag-editor/jquery.caret.min.js')}}"></script>
<script src="{{asset('js/vendor/datetimepicker/build/jquery.datetimepicker.full.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/posts/administrar.js')}}"></script>
@endsection