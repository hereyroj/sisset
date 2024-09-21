@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar PQRS</title>
@endsection
 
@section('styles')
<style>
    .tblCoIn th {
        border: 1px solid #fff;
        text-align: center;
        vertical-align: middle !important;
    }

    .botones a {
        margin-right: 5px;
    }

    table a,
    table button {
        display: inline-block;
    }

    .anulada {
        background-color: #ec865e !important;
        color: #ffffff;
    }

    #bar-navigation {
        float: right;
    }

    #bar-navigation ul {
        margin: 0;
    }

    #bar-navigation>div {
        height: 40px;
    }

    #bar-navigation span,
    #bar-navigation a {
        border-radius: 0 !important;
        height: 40px;
        line-height: 25px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar PQRS</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="correspondencia_externa" data-toggle="tab" aria-selected="true" href="#correspondencia_externa">Correspondencia externa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="correspondencia_interna" data-toggle="tab" aria-selected="false" href="#correspondencia_interna">Correspondencia interna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="correspondencia_saliente" data-toggle="tab" aria-selected="false" href="#correspondencia_saliente">Correspondencia saliente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros">Parámetros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes">Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="correspondencia_externa" class="tab-pane fade show active" role="tabpanel"></div>
                        <div id="correspondencia_interna" class="tab-pane fade" role="tabpanel"></div>
                        <div id="correspondencia_saliente" class="tab-pane fade" role="tabpanel"></div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6" id="clases"></div>
                                <div class="col-md-2" id="medios"></div>
                                <div class="col-md-2" id="modalidades"></div>
                                <div class="col-md-2" id="motivos_anulacion"></div>
                            </div>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="GeneralPorAñosYMeses" src="{{ url('admin/reportes/pqr/GeneralPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="respondidasATiempo" src="{{ url('admin/reportes/pqr/respondidasATiempo') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="respondidasFueraTiempo" src="{{ url('admin/reportes/pqr/respondidasFueraTiempo') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="pqrPorClases" src="{{ url('admin/reportes/pqr/pqrPorClases') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="sinResponder" src="{{ url('admin/reportes/pqr/sinResponder') }}" height="150px" width="400px" style="height:150px; min-width:500px; border:none;"></iframe>
                            <iframe id="vencidas" src="{{ url('admin/reportes/pqr/vencidas') }}" height="150px" width="400px" style="height:150px; min-width:500px; border:none;"></iframe>
                            <iframe id="porVencer" src="{{ url('admin/reportes/pqr/porVencer') }}" height="150px" width="400px" style="height:150px; min-width:500px; border:none;"></iframe>
                            <iframe id="CoExPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoExPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CoInPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoInPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CoSaPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoSaPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="pqrPorMedioTraslado" src="{{ url('admin/reportes/pqr/pqrPorMedioTraslado') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="GeneralAnuladasPorAñosYMeses" src="{{ url('admin/reportes/pqr/GeneralAnuladasPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CoExAnuladasPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoExAnuladasPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CoInAnuladasPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoInAnuladasPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                            <iframe id="CoSaAnuladasPorAñosYMeses" src="{{ url('admin/reportes/pqr/CoSaAnuladasPorAñosYMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/administrar.js')}}"></script>
<script type="ecmascript" src="{{asset('js/gestion_documental/pqr/es_administrar.js')}}"></script>
@endsection