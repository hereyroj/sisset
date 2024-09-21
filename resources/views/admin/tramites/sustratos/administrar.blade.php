@extends('layouts.dashboard')

@section('meta')
    <title>Administrar sustratos</title>
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
    @if(Session::exists('errorReporte'))
        <div class="alert alert-danger">{{Session::get('errorReporte')}}</div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Administrar sustratos</div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist" id="tabs">
                            <li class="nav-item">
                                <a class="nav-link active" role="tab" aria-controls="sustratos" data-toggle="tab" aria-selected="true" href="#sustratos"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Sustratos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i  class="fa fa-btn glyphicon glyphicon-cog"></i> Parámetros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="sustratos" class="tab-pane fade show active" role="tabpanel">
                                <div class="cabecera-tabla">
                                    <div>
                                        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerSustratos();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                    </div>
                                    <div>
                                        {!! Form::select('criterios', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'criterios', 'style' => 'border-radius:0;height:40px;']) !!}
                                    </div>
                                    <div class="field-search input-group">
                                        <input type="text" name="filtrarSustratos" id="filtrarSustratos">
                                        <button type="button" class="btn-buscar" onclick="filtrarSustratos();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button type="button" class="btn-restaurar" onclick="obtenerSustratos();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevosSustratos()">Nuevos sustratos</button>
                                    </div>
                                </div>
                                <div id="listadoSustratos"></div>
                            </div>
                            <div id="parametros" class="tab-pane fade" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4" id="tiposSustratos"></div>
                                    <div class="col-md-4" id="motivosAnulaciones"></div>
                                    <div class="col-md-4" id="motivosLiberaciones"></div>
                                </div>
                            </div>
                            <div id="reportes" class="tab-pane fade" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">                                        
                                        <form class="form-inline" method="POST" action="{{url('/admin/tramites/sustratos/solicitarReporteSustratos')}}" style="border: 1px solid gray; margin: 5px; padding:8px; border-radius:5px;">
                                            @csrf
                                            <div class="mx-auto">
                                                <h5 style="width: 100%; margin-bottom: 10px; text-align: center;">Generar reporte</h5>
                                                <div class="form-row align-items-center">
                                                    <div class="col-auto my-1">
                                                        <label class="control-label">Tipo sustrato</label>
                                                        {{Form::select('tipo', $tiposSustratos, null, ['class'=>'form-control', 'required'])}}
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <label class="control-label">Estado</label>
                                                        <select class="form-control" name="estado" required>
                                                            <option value="1">Consumido</option>
                                                            <option value="2">Anulado</option>
                                                            <option value="3">Ambos</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <label class="control-label">Fecha incial</label>
                                                        <input type="text" class="form-control datepicker" required name="fecha_inicio" id="fecha_inicio" value="{{date('Y-m-d')}}">
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <label class="control-label">Fecha final</label>
                                                        <input type="text" class="form-control datepicker" required name="fecha_fin" id="fecha_fin" value="{{date('Y-m-d')}}">
                                                    </div>
                                                    <div class="col-auto my-1">
                                                        <input type="submit" class="form-control btn btn-success" value="Generar">
                                                    </div>
                                                </div>    
                                            </div>                                            
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <iframe id="SustratosConsumidosPorAños" src="{{ url('admin/reportes/tramites/SustratosConsumidosPorAños') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                                        <iframe id="SustratosConsumidosPorMeses" src="{{ url('admin/reportes/tramites/SustratosConsumidosPorMeses') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                                        <iframe id="SustratosConsumidosPorDias" src="{{ url('admin/reportes/tramites/SustratosConsumidosPorDias') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                                        <iframe id="SustratosPorTipo" src="{{ url('admin/reportes/tramites/SustratosPorTipo') }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/tramites/sustratos/administrar.js')}}"></script>
@endsection