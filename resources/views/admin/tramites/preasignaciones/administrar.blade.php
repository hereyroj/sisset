@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar Pre-Asignaciones</title>
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
                <div class="card-header">Administrar Pre-Asignaciones</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="solicitudes" data-toggle="tab" aria-selected="true" href="#solicitudes"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Solicitudes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i  class="fa fa-btn glyphicon glyphicon-cog"></i> Parámetros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="solicitudes" class="tab-pane fade show active" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerSolicitudes();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    {!! Form::select('criterios', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'criterios', 'style' => 'border-radius:0;height:40px;'])
                                    !!}
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="filtrarNotificaciones" id="filtrarNotificaciones">
                                    <button type="button" class="btn-buscar" onclick="filtrarRangos();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerSolicitudes();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevaPreasignacion();">
                                            <i class="fas fa-sync"></i> Nueva Pre-asignación
                                        </button>
                                </div>
                            </div>
                            <div id="listadoSolicitudes"></div>
                        </div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="col-md-6" id="motivos_rechazo"></div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="PorAñosYMeses" src="{{ url('admin/reportes/preAsignaciones/PorAñosyMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="ClasesVehiculos" src="{{ url('admin/reportes/preAsignaciones/ClasesVehiculos') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="ServiciosVehiculos" src="{{ url('admin/reportes/preAsignaciones/ServiciosVehiculos') }}" height="100%" width="100%"
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
<script type="text/javascript" src="{{asset('js/tramites/preasignaciones/administrar.js')}}"></script>
<script type="text/ecmascript" src="{{asset('js/tramites/preasignaciones/es_administrar.js')}}"></script>
@endsection