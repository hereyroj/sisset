@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar vehículos</title>
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
                <div class="card-header">Administrar vehículos</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="vehiculos" data-toggle="tab" aria-selected="true" href="#vehiculos"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="marcas" data-toggle="tab" aria-selected="false" href="#marcas"><i class="fa fa-btn glyphicon glyphicon-list"></i> Marcas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="clases" data-toggle="tab" aria-selected="false" href="#clases"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Clases</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="carrocerias" data-toggle="tab" aria-selected="false" href="#carrocerias"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Carrocerías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="combustibles" data-toggle="tab" aria-selected="false" href="#combustibles"><i class="fa fa-btn glyphicon glyphicon-fire"></i> Combustibles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="servicios" data-toggle="tab" aria-selected="false" href="#servicios"><i class="fa fa-btn glyphicon glyphicon-briefcase"></i> Servicios</a></li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="lineas" data-toggle="tab" aria-selected="false" href="#lineas"><i class="fa fa-btn glyphicon glyphicon-briefcase"></i> Líneas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="tiposBaterias" data-toggle="tab" aria-selected="false" href="#tiposBaterias"><i class="fa fa-btn glyphicon glyphicon-briefcase"></i> Tipos Baterías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li class="nav-item">
                    </ul>
                    <div class="tab-content">
                        <div id="vehiculos" class="tab-pane fade show active" role="tabpanel">

                        </div>
                        <div id="marcas" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMarcas();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre" @if(isset($parametro)) value="{{$parametro}}" @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarMarca();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerMarcas();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaMarca();">
                                            <i class="fas fa-sync"></i> Crear marca
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="clases" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerClases();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre carpeta o serie" @if(isset($parametro)) value="{{$parametro}}"
                                        @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarClase();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerClases();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaClase();">
                                            <i class="fas fa-sync"></i> Crear clase
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="carrocerias" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerCarrocerias();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre carpeta o serie" @if(isset($parametro)) value="{{$parametro}}"
                                        @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarCarroceria();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerCarrocerias();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaCarroceria();"> carrocería</button>
                                </div>
                            </div>
                        </div>
                        <div id="combustibles" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerCombustibles();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre carpeta o serie" @if(isset($parametro)) value="{{$parametro}}"
                                        @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarCombustible();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerCombustibles();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoCombustible();">
                                            <i class="fas fa-sync"></i> Crear combustible
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="servicios" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerServicios();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre" @if(isset($parametro)) value="{{$parametro}}" @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarServicio();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerServicios();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoServicio();">
                                            <i class="fas fa-sync"></i> Crear servicio
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="lineas" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerLineas();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre" @if(isset($parametro)) value="{{$parametro}}" @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarLinea();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerLineas();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaLinea();">
                                            <i class="fas fa-sync"></i> Crear línea
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="tiposBaterias" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposBaterias();">
                                                                    <i class="fas fa-sync"></i> Actualizar
                                                                </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre carpeta o serie" @if(isset($parametro)) value="{{$parametro}}"
                                        @endif>
                                    <button type="button" class="btn-buscar" onclick="buscarTipoBateria();">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerTiposBaterias();">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoTipoBateria();">
                                                                    <i class="fas fa-sync"></i> Crear tipo batería
                                                                </button>
                                </div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="VehiculosPorMarca" src="{{ url('admin/reportes/vehiculos/VehiculosPorMarca') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="VehiculosPorClase" src="{{ url('admin/reportes/vehiculos/VehiculosPorClase') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="VehiculosPorCombustible" src="{{ url('admin/reportes/vehiculos/VehiculosPorCombustible') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="VehiculosPorCarroceria" src="{{ url('admin/reportes/vehiculos/VehiculosPorCarroceria') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="VehiculosPorNivelServicio" src="{{ url('admin/reportes/vehiculos/VehiculosPorNivelServicio') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="VehiculosPorServicio" src="{{ url('admin/reportes/vehiculos/VehiculosPorServicio') }}" height="100%" width="100%"
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
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/administrar.js')}}"></script>
@endsection