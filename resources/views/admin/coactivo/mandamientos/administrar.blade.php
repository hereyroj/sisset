@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar Mandamientos</title>
@endsection
 
@section('styles')
<style>
    .pickadate-root {
        position: relative;
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
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Mandamientos</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrarMandamientos" data-toggle="tab" aria-selected="true" href="#administrarMandamientos"><i class="fa fa-btn glyphicon glyphicon-folder-open"></i> Administrar Mandamientos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="parametros" data-toggle="tab" aria-selected="false" href="#parametros"><i  class="fa fa-btn glyphicon glyphicon-open-file"></i> Parámetros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="importarMandamientos" data-toggle="tab" aria-selected="false" href="#importarMandamientos"><i  class="fa fa-btn glyphicon glyphicon-open-file"></i> Importar Mandamientos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrarMandamientos" class="tab-pane fade show active" role="tabpanel"></div>
                        <div id="parametros" class="tab-pane fade" role="tabpanel">
                            <div class="row">
                                <div class="col-md-3" id="mediosNotificacion"></div>
                                <div class="col-md-3" id="motivosDevolucion"></div>
                                <div class="col-md-3" id="tiposFinalizacion"></div>
                                <div class="col-md-3" id="tiposNotificacion"></div>
                            </div>
                        </div>
                        <div id="importarMandamientos" class="tab-pane fade" role="tabpanel">
                            <form method="POST" enctype='multipart/form-data' action="{{url('admin/coactivo/edictos/Mandamientos/importar')}}" class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    Por favor adjunte el archivo con los Mandamientos a importar. Tener en cuenta que el archivo debe ser de formato .csv separado
                                    por comas.
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="Mandamientos" required id="Mandamientos">
                                    <label class="custom-file-label" for="Mandamientos">Seleccionar archivo:</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Importar Mandamientos">
                                </div>
                            </form>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/administrar.js')}}"></script>
@endsection