@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Ventanilla - Procesar solicitud</title>
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
                <div class="card-header">Procesar solicitudes</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="mis_tramites" data-toggle="tab" aria-selected="true" href="#mis_tramites"><i class="fa fa-btn glyphicon glyphicon-list"></i> Solicitudes sin asignar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="mis_tramites" class="tab-pane fade show active" role="tabpanel">
                             <div class="row table-bar">
                                <div class="col-sm-12 col-mg-6 col-lg-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-primary" type="button" title="Actualizar" onclick="obtenerMisTramitesAsignados();"><i class="fas fa-sync-alt"></i> Actualizar</button>
                                        </div>
                                        <div class="input-group-prepend">
                                            {!! Form::select('filtroSolicitudes', $filtros, $sFiltro, ['class'=>'custom-select', 'id'=>'filtroSolicitudes']) !!}
                                        </div>
                                        <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="filtrar-comparendos" id="filtrarSolicitudes">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" title="Buscar" onclick="filtrarSolicitudes();"><i class="fas fa-search"></i></button>
                                            <button class="btn btn-danger" type="button" title="Limpiar" onclick="obtenerMisTramitesAsignados();"><i class="fas fa-times"></i></button>
                                            <button class="btn btn-info" type="button" title="Llamar turno" onclick="llamarTurno();"><i class="fas fa-plus"></i> Llamar</button>
                                            <button class="btn btn-secondary" type="button" title="Rellamar turno" onclick="reLlamarTurno();"><i class="fas fa-plus"></i> Re-llamar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="VentanillaFuncionarioSolicitudesAtendidasPorAñosYMeses" src="{{ url('admin/reportes/tramites/VentanillaFuncionarioSolicitudesAtendidasPorAñosYMeses') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/ventanilla.js')}}"></script>
<script type="text/ecmascript" src="{{asset('js/tramites/solicitudes/es_ventanilla.js')}}"></script>
@endsection