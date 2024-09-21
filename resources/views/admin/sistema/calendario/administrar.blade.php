@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<title>Administrar calendario</title>
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

    td {
        text-align: center;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif @if(session('mensaje'))
            <div class="alert alert-success">
                {{session('mensaje')}}
            </div>
            @endif
            <div class="card">
                <div class="card-header">Administrar calendario</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrar" data-toggle="tab" aria-selected="true" href="#administrar"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="importar" data-toggle="tab" aria-selected="false" href="#importar"><i class="fa fa-btn glyphicon glyphicon-plus"></i> Importar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrar" class="tab-pane fade show active" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerRegistros();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div>
                                    <select name="year" id="year" class="form-control" style="border-radius:0;height:40px;">
                                            @foreach($years as $year)
                                                <option value="{{$year}}">{{$year}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div>
                                    {!! Form::select('month', $months, null, ['class'=>'form-control', 'id'=>'month', 'style' => 'border-radius:0;height:40px;'])
                                    !!}
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="obtenerRegistros();">
                                            <i class="fas fa-search"></i> Filtrar
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="crearRegistro();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo registro
                                        </button>
                                </div>
                            </div>
                            <div id="listadoRegistros"></div>
                        </div>
                        <div id="importar" class="tab-pane fade" role="tabpanel">
                            <form method="POST" enctype='multipart/form-data' action="{{url('admin/sistema/calendario/importar')}}" class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    Por favor adjunte el archivo con las fechas a importar. Tener en cuenta que el archivo debe ser de formato .csv separado
                                    por comas.
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="archivo" required id="archivo">
                                    <label class="custom-file-label" for="archivo">Seleccionar archivo:</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Importar fechas">
                                </div>
                            </form>
                        </div>
                        <div id="reportes" class="tab-pane fade" role="tabpanel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/sistema/calendario/administrar.js')}}"></script>
@endsection