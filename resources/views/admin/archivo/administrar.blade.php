@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administración del archivo</title>
@endsection
 
@section('styles')
<link href="https://fonts.googleapis.com/css?family=Lato:Black&effect=emboss" rel="stylesheet">
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
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif @if(session('mensaje'))
        <div class="alert alert-success">
            {{session('mensaje')}}
        </div>
        @endif
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administración del archivo</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item"><a class="nav-link active" role="tab" aria-controls="inventario" data-toggle="tab" aria-selected="true" href="#inventario"> <i class="fa fa-btn glyphicon glyphicon-inbox"></i> Inventario de carpetas</a></li>
                        <li class="nav-item"><a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i class="fa fa-btn glyphicon glyphicon-cog"></i> Parámetros</a></li>
                        <li class="nav-item"><a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i  class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="inventario" class="tab-pane fade show active" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="realizarBusqueda();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    <select name="criterioBusqueda" class="form-control" id="criterioBusqueda" style="border-radius:0;height:40px;">
                                            <option value="nombre">Por nombre</option>
                                            <option value="serie" selected>Por serie</option>
                                        </select>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="txtCarpeta" id="txtCarpeta" placeholder="Nombre carpeta o serie" @if(isset($parametro)) value="{{$parametro}}"
                                        @endif>
                                    <button type="button" class="btn-buscar" onclick="realizarBusqueda();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="realizarBusqueda();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="crearCarpeta();">
                                            Crear carpeta
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="crearMultiplesCarpetas();">
                                            Crear mútiples carpetas
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="importarRegistros();">
                                            Importar registros
                                        </button>
                                </div>
                            </div>
                            <div class="col-md-12 resultadoBusqueda" style="margin:0;padding:0;border:none;"></div>
                        </div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="page-header">
                                        <h1>Motivos cancelación
                                            <small>matricula carpeta</small>
                                        </h1>
                                    </div>
                                    <div id="motivos_cancelacion"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="page-header">
                                        <h1>Estados
                                            <small>carpeta</small>
                                        </h1>
                                    </div>
                                    <div id="estados_carpeta"></div>
                                </div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="SolicitudesPorDias" src="{{ url('admin/reportes/archivo/SolicitudesPorDias') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesPorMeses" src="{{ url('admin/reportes/archivo/SolicitudesPorMeses') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesPorTramites" src="{{ url('admin/reportes/archivo/SolicitudesPorTramites') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SeriesRegistradas" src="{{ url('admin/reportes/archivo/SeriesRegistradas') }}" width="30%" height="auto" style="border:none; width: 30%; height: auto;"></iframe>
                            <iframe id="CarpetasTotales" src="{{ url('admin/reportes/archivo/CarpetasTotales') }}" width="30%" height="auto" margin="0 5%"
                                style="border:none; width: 30%; height: auto;"></iframe>
                            <iframe id="CarpetasPorFuera" src="{{ url('admin/reportes/archivo/CarpetasPorFuera') }}" width="30%" height="auto" style="border:none; width: 30%; height: auto;"></iframe>
                            <iframe id="CarpetasPorEstado" src="{{ url('admin/reportes/archivo/CarpetasPorEstado') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CarpetasPorClaseVehiculo" src="{{ url('admin/reportes/archivo/CarpetasPorClaseVehiculo') }}" height="100%" width="100%"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.0.3/jquery.floatThead.min.js"></script>
<script type="text/javascript" src="{{asset('js/archivo/administrar.js')}}"></script>
@endsection