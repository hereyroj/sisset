@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Gestionar de solicitudes</title>
@endsection
 
@section('styles')
<style>
    .botones a {
        margin-right: 5px;
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
                <div class="card-header">Gestionar solicitudes</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="sinAprobar" data-toggle="tab" aria-selected="true" href="#sinAprobar"><i class="fa fa-btn glyphicon glyphicon-time"></i> Pendientes de aprobación</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="sinDevolver" data-toggle="tab" aria-selected="false" href="#sinDevolver"><i class="fa fa-btn glyphicon glyphicon-time"></i> Pendientes de ingreso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Parámetros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="sinAprobar" class="tab-pane fade show active" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="solicitudesSinAprobar();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    {!! Form::select('criterioSinAprobar', $criterios, null, ['class'=>'form-control', 'id'=>'criteriosSinAprobar', 'style' =>
                                    'border-radius:0;height:40px;']) !!}
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="filtrarSinAprobar" id="filtrarSinAprobar">
                                    <button type="button" class="btn-buscar" onclick="filtrarSinAprobar();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="solicitudesSinAprobar();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="sinDevolver" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="solicitudesSinDevolver();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    {!! Form::select('criterioSinDevolver', $criterios, null, ['class'=>'form-control', 'id'=>'criteriosSinDevolver', 'style'
                                    => 'border-radius:0;height:40px;']) !!}
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="filtrarSinDevolver" id="filtrarSinDevolver">
                                    <button type="button" class="btn-buscar" onclick="filtrarSinDevolver();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="solicitudesSinDevolver();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4" id="motivosSolicitud"></div>
                                <div class="col-md-4" id="motivosDenegacion"></div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="SolicitudesAprobadasPorDias" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesAprobadasPorDias') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesAprobadasPorMeses" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesAprobadasPorMeses') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesAprobadasUltimaSemana" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesAprobadasUltimaSemana') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesDenegadasPorDias" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesDenegadasPorDias') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesDenegadasPorMeses" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesDenegadasPorMeses') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesDenegadasUltimaSemana" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesDenegadasUltimaSemana') }}"
                                height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesSinEntregar" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesSinEntregar') }}" height="auto"
                                width="30%" style="height:auto; width:30%; border:none;"></iframe>
                            <iframe id="CarpetasSinDevolver" src="{{ url('admin/reportes/gestionSolicitudes/CarpetasSinDevolver') }}" height="auto" width="30%"
                                style="height:auto; width:30%; border:none;"></iframe>
                            <iframe id="CarpetasSinValidar" src="{{ url('admin/reportes/gestionSolicitudes/CarpetasSinValidar') }}" height="auto" width="30%"
                                style="height:auto; width:30%; border:none;"></iframe>
                            <iframe id="CarpetasValidadasPorEstado" src="{{ url('admin/reportes/gestionSolicitudes/CarpetasValidadasPorEstado') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="SolicitudesDenegadasPorMotivo" src="{{ url('admin/reportes/gestionSolicitudes/SolicitudesDenegadasPorMotivo') }}"
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
<script type="text/javascript" src="{{asset('js/solicitudes/procesarSolicitudes.js')}}"></script>
<script type="text/ecmascript" src="{{asset('js/solicitudes/es_procesarSolicitudes.js.js')}}"></script>
@endsection