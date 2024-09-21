@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mis procesos PQR</title>
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

    .anulada {
        background-color: #ec865e !important;
        color: #ffffff;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mis Procesos PQR</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="correspondencia_externa" data-toggle="tab" aria-selected="true" href="#correspondencia_externa"><i class="fa fa-btn glyphicon glyphicon-globe"></i> Correspondencia externa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="correspondencia_interna" data-toggle="tab" aria-selected="false" href="#correspondencia_interna"><i class="fa fa-btn glyphicon glyphicon-retweet"></i> Correspondencia interna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="correspondencia_saliente" data-toggle="tab" aria-selected="false" href="#correspondencia_saliente"><i class="fa fa-btn glyphicon glyphicon-share-alt"></i> Correspondencia saliente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="correspondencia_externa" class="tab-pane fade show active" role="tabpanel" style="overflow: auto;"></div>
                        <div id="correspondencia_interna" class="tab-pane fade" role="tabpanel" style="overflow: auto;"></div>
                        <div id="correspondencia_saliente" class="tab-pane fade" role="tabpanel" style="overflow: auto;"></div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel" style="overflow: auto;">
                            <iframe id="misPQR_asignadasGeneralCoEx" src="{{ url('admin/reportes/pqr/misPQR_asignadasGeneralCoEx') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="misPQR_asignadasGeneralCoIn" src="{{ url('admin/reportes/pqr/misPQR_asignadasGeneralCoIn') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="misPQRRadicadasGeneralPorTipo" src="{{ url('admin/reportes/pqr/misPQRRadicadasGeneralPorTipo') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="misPQRRespondidasGeneralCoEx" src="{{ url('admin/reportes/pqr/misPQRRespondidasGeneralCoEx') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="misPQRRespondidasGeneralCoIn" src="{{ url('admin/reportes/pqr/misPQRRespondidasGeneralCoIn') }}" height="100%"
                                width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="misPQR_asignadasClases" src="{{ url('admin/reportes/pqr/misPQR_asignadasClases') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none; margin-bottom: 50px;"></iframe>
                            <iframe id="misPQR_respondidasClases" src="{{ url('admin/reportes/pqr/misPQR_respondidasClases') }}" height="100%" width="100%"
                                style="height:500px; width:100%; border:none; margin-bottom: 50px;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/misProcesos.js')}}"></script>
<script type="text/ecmascript" src="{{asset('js/gestion_documental/pqr/es_misProcesos.js')}}"></script>
@endsection