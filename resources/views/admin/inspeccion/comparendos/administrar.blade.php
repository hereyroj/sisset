@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar Comparendos</title>
@endsection
 
@section('styles')
<style>
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
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Comparendos</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrar" data-toggle="tab" aria-selected="true" href="#administrar"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i  class="fa fa-btn glyphicon glyphicon-cog"></i> Parámetros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrar" class="tab-pane fade show active" role="tabpanel">
                            <div class="row table-bar">
                                <div class="col-sm-12 col-mg-6 col-lg-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-primary" type="button" title="Actualizar" onclick="obtenerComparendos();"><i class="fas fa-sync-alt"></i> Actualizar</button>
                                        </div>
                                        <div class="input-group-prepend">
                                            {!! Form::select('filtroComparendos', $filtros, $sFiltro, ['class'=>'custom-select', 'id'=>'filtroComparendos']) !!}
                                        </div>
                                        <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="filtrar-comparendos" id="filtrarComparendos">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" title="Buscar" onclick="filtrarComparendos();"><i class="fas fa-search"></i></button>
                                            <button class="btn btn-danger" type="button" title="Limpiar" onclick="obtenerComparendos();"><i class="fas fa-times"></i></button>
                                            <button class="btn btn-info" type="button" title="Nuevo" onclick="nuevoComparendo();"><i class="fas fa-plus"></i> Nuevo</button>
                                            <button class="btn btn-warning" type="button" title="Sancionar" onclick="sancionarComparendos();"><i class="fas fa-stamp"></i> Sancionar</button>
                                            <button class="btn btn-danger" type="button" title="Embriaguéz" onclick="sancionarEmbriaguez();"><i class="fas fa-beer"></i> Embriaguéz</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="listadoComparendos" class=""></div>
                        </div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6" id="infracciones"></div>
                                <div class="col-md-3" id="tiposComparendos"></div>
                                <div class="col-md-3" id="tiposInmovilizaciones"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" id="tiposInfractores"></div>
                                <div class="col-md-3" id="listadoEntidades"></div>
                                <div class="col-md-3" id="listadoLicenciaCategorias"></div>
                            </div>                                            
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="ComparendosPorAñosYMeses" src="{{ url('admin/reportes/inspeccion/ComparendosPorAñosYMeses') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="ComparendosPorMeses" src="{{ url('admin/reportes/inspeccion/ComparendosPorTipos') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/inspeccion/comparendos/administrar.js')}}"></script>
@endsection