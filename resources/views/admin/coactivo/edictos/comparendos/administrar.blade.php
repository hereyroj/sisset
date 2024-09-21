@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar comparendos</title>
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
                <div class="card-header">Administrar comparendos</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrarComparendos" data-toggle="tab" aria-selected="true" href="#administrarComparendos"><i class="fa fa-btn glyphicon glyphicon-folder-open"></i> Administrar Comparendos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="importarComparendos" data-toggle="tab" aria-selected="false" href="#importarComparendos"><i  class="fa fa-btn glyphicon glyphicon-open-file"></i> Importar Comparendos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrarComparendos" class="tab-pane fade show active" role="tabpanel"></div>
                        <div id="importarComparendos" class="tab-pane fade" role="tabpanel">
                            <form method="POST" enctype='multipart/form-data' action="{{url('admin/coactivo/edictos/comparendos/importar')}}" class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    Por favor adjunte el archivo con los comparendos a importar. Tener en cuenta que el archivo debe ser de formato .csv separado
                                    por comas.
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="comparendos" required id="comparendos">
                                    <label class="custom-file-label" for="comparendos">Seleccionar archivo:</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Importar comparendos">
                                </div>
                            </form>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel">
                            <iframe id="ComparendosPorAñosYMeses" src="{{ url('admin/reportes/cobrocoactivo/ComparendosPorAñosYMeses') }}" height="100%"
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
<script type="text/javascript" src="{{asset('js/coactivo/comparendos/administrar.js')}}"></script>
@endsection