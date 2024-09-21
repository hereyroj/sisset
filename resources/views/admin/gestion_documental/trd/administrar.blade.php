@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar TRD</title>
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

    .center-th {
        text-align: center;
        vertical-align: middle !important;
    }

    table tr td {
        word-wrap: normal;
    }

    table ul li {
        width: 100%;
        padding: 2px;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Tablas de Retención Documental</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="series" data-toggle="tab" aria-selected="true" href="#series"><i class="fa fa-btn glyphicon glyphicon-list"></i> Series</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="subseries" data-toggle="tab" aria-selected="false" href="#subseries"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Subseries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="tipodocumentos" data-toggle="tab" aria-selected="false" href="#tiposdocumentos"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Tipos documentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="series" class="tab-pane fade show active" role="tabpanel"></div>
                        <div id="subseries" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    {!! Form::select('dpbSerie', $series, null, ['class'=>'form-control', 'id'=>'dpbSerie', 'style' => 'border-radius:0;height:40px;'])
                                    !!}
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoEdictos" onclick="obtenerSubSeries();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="serieBusqueda" id="serieBusqueda" placeholder="Buscar por placa, código o empresa" @if(isset($parametro))
                                        value="{{$parametro}}" @endif>
                                    <button type="button" class="btn-buscar" onclick="subserieBusqueda();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerSubSeries();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" id="btnCrearSerie" onclick="crearSubSerie();">
                                            <i class="fas fa-sync"></i> Crear sub-serie
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="tiposdocumentos" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    {!! Form::select('dpbSSerie', $series, null, ['class'=>'form-control', 'id'=>'dpbSSerie', 'style' => 'border-radius:0;height:40px;'])
                                    !!}
                                </div>
                                <div>
                                    <select name="dpbSubSerie" id="dpbSubSerie" class="form-control" style="border-radius:0;height:40px;"></select>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoEdictos" onclick="obtenerTipos();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="serieBusqueda" id="serieBusqueda" placeholder="Buscar por placa, código o empresa" @if(isset($parametro))
                                        value="{{$parametro}}" @endif>
                                    <button type="button" class="btn-buscar" onclick="tipoBusqueda();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="tipoSeries();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" id="btnCrearSerie" onclick="crearTipo();">
                                            <i class="fas fa-sync"></i> Crear tipo
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/gestion_documental/trd/administrar.js')}}"></script>
@endsection