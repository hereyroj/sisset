@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar Tarjetas de Operación</title>
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

    .iconos {
        text-align: center;
    }

    .modal-body-editarTO {
        padding: 15px;
        width: auto;
        height: auto;
    }

    .modal-body-verTO {
        padding: 15px;
        width: auto;
        height: auto;
    }

    .tarjeta-vencida {
        background-color: #d9534f !important;
        color: #fff;
    }

    .tarjeta-sobre-vencimiento {
        background-color: #f0ad4e !important;
        color: #fff;
    }

    td {
        vertical-align: middle;
    }

    td a {
        margin-bottom: 4px !important;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Tarjetas de Operación</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrarTSO" data-toggle="tab" aria-selected="true" href="#administrarTSO"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrarTSO" class="tab-pane fade show active" role="tabpanel">
                            <div class="listadoTSO"></div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="TOSProximasAVencer" src="{{ url('admin/reportes/inspeccion/TOSProximasAVencer') }}" height="400px" width="45%"
                                style="height:400px; width:45%; border:none; margin-bottom: 40px; margin-right: 5%"></iframe>
                            <iframe id="TOSVencidas" src="{{ url('admin/reportes/inspeccion/TOSVencidas') }}" height="400px" width="45%" style="height:400px; width:45%; border:none; margin-bottom: 40px;"></iframe>
                            <iframe id="TOPorNivelDeServicio" src="{{ url('admin/reportes/inspeccion/TOPorNivelDeServicio') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="TOActivasPorEmpresa" src="{{ url('admin/reportes/inspeccion/TOActivasPorEmpresa') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="TOExpedidasActualVigencia" src="{{ url('admin/reportes/inspeccion/TOExpedidasActualVigencia') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/tramites/to/administrar.js')}}"></script>
@endsection