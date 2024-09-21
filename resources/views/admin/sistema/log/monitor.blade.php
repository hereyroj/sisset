@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Monitorear logs</title>
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
                <div class="card-header">Monitorear logs</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="activityLogs" data-toggle="tab" aria-selected="true" href="#activityLogs"><i class="fa fa-btn glyphicon glyphicon-screenshot"></i> Activity Logs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="exceptions" data-toggle="tab" aria-selected="false" href="#exceptions"><i class="fa fa-btn glyphicon glyphicon-search"></i> Exceptions</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="activityLogs" class="tab-pane fade show active" role="tabpanel" style="overflow-x:auto">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerLogsActividades();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    {!! Form::select('criteriosActividades', $filtrosActividades, $sFiltroActividades, ['class'=>'form-control', 'id'=>'criteriosActividades',
                                    'style' => 'border-radius:0;height:40px;']) !!}
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="filtrarTramites" id="filtrarTramites">
                                    <button type="button" class="btn-buscar" onclick="filtrarLogsActividades();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerLogsActividades();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="exceptions" class="tab-pane fade" role="tabpanel" style="overflow-x:auto">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerLogsExcepciones();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    {!! Form::select('criteriosExcepciones', $filtrosExcepciones, $sFiltroExcepciones, ['class'=>'form-control', 'id'=>'criteriosExcepciones',
                                    'style' => 'border-radius:0;height:40px;']) !!}
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="filtrarTramites" id="filtrarTramites">
                                    <button type="button" class="btn-buscar" onclick="filtrarLogsExcepciones();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="obtenerLogsExcepciones();">
                                            <i class="fas fa-times"></i>
                                        </button>
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
<script type="text/javascript" src="{{asset('js/sistema/log/monitor.js')}}"></script>
@endsection