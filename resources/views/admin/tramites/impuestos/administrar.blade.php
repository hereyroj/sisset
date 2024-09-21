@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Administrar Liquidaciones</title>
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

    #infoVehiculo,
    #liquidacionesVehiculo {
        max-height: 650px;
        overflow: auto;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administrar Impuestos</div>
               <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" aria-controls="administrar" data-toggle="tab" aria-selected="true" href="#administrar"><i class="fa fa-btn glyphicon glyphicon-inbox"></i> Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="vigencias" data-toggle="tab" aria-selected="false" href="#vigencias"><i  class="fa fa-btn glyphicon glyphicon-book"></i> Vigencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="descuentos" data-toggle="tab" aria-selected="false" href="#descuentos"><i  class="fa fa-btn glyphicon glyphicon-tag"></i> Descuentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="avaluos" data-toggle="tab" aria-selected="false" href="#avaluos"><i  class="fa fa-btn glyphicon glyphicon-usd"></i> Avalúos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="gruposBaterias" data-toggle="tab" aria-selected="false" href="#gruposBaterias"><i  class="fa fa-btn glyphicon glyphicon-usd"></i> Grupos Baterías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="gruposClases" data-toggle="tab" aria-selected="false" href="#gruposClases"><i  class="fa fa-btn glyphicon glyphicon-usd"></i> Grupos Clases</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="gruposCilindrajes" data-toggle="tab" aria-selected="false" href="#gruposCilindrajes"><i  class="fa fa-btn glyphicon glyphicon-usd"></i> Grupos Cilindrajes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" aria-controls="reportes" data-toggle="tab" aria-selected="false" href="#reportes"><i class="fa fa-btn glyphicon glyphicon-stats"></i> Reportes</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="administrar" class="tab-pane fade show active" role="tabpanel">
                            <div class="cabecera-tabla" style="margin-bottom: 10px;">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerInfoVehiculo();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="PLACA">
                                    <button type="button" class="btn-buscar" onclick="obtenerInfoVehiculo();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiar();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="importarRegistros();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Importar
                                        </button>
                                </div>
                            </div>
                            <div class="row">
                                <div id="infoVehiculo" class="col-md-2" style="border-right: 1px solid gray"></div>
                                <div id="liquidacionesVehiculo" class="col-md-10"></div>
                            </div>                            
                        </div>
                        <div id="vigencias" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerVigencias();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="PLACA">
                                    <button type="button" class="btn-buscar" onclick="obtenerVigencias();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarVigencias();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevaVigencia();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="descuentos" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerDescuentos();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="NOMBRE">
                                    <button type="button" class="btn-buscar" onclick="obtenerDescuentos();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarDescuentos();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevoDescuento();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="avaluos" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerBasesGravables();">
                                            <i class="fas fa-sync"></i> Actualizar
                                        </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="LINEA">
                                    <button type="button" class="btn-buscar" onclick="obtenerBasesGravables();">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarBasesGravables();">
                                            <i class="fas fa-times"></i>
                                        </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevaBaseGravable();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                                        </button>
                                </div>
                            </div>
                        </div>
                        <div id="gruposBaterias" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerGruposBaterias();">
                                                                    <i class="fas fa-sync"></i> Actualizar
                                                                </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="LINEA">
                                    <button type="button" class="btn-buscar" onclick="obtenerGruposBaterias();">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarGruposBaterias();">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevoGrupoBateria();">
                                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                                                                </button>
                                </div>
                            </div>
                        </div>
                        <div id="gruposClases" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerGruposClases();">
                                                                    <i class="fas fa-sync"></i> Actualizar
                                                                </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="LINEA">
                                    <button type="button" class="btn-buscar" onclick="obtenerGruposClases();">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarGruposClases();">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevoGrupoClase();">
                                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                                                                </button>
                                </div>
                            </div>
                        </div>
                        <div id="gruposCilindrajes" class="tab-pane fade" role="tabpanel">
                            <div class="cabecera-tabla">
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerGruposCilindrajes();">
                                                                    <i class="fas fa-sync"></i> Actualizar
                                                                </button>
                                </div>
                                <div class="field-search input-group">
                                    <input type="text" name="placaVehiculo" id="placaVehiculo" placeholder="LINEA">
                                    <button type="button" class="btn-buscar" onclick="obtenerGruposCilindrajes();">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                    <button type="button" class="btn-restaurar" onclick="limpiarGruposCilindrajes();">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevoGrupoCilindraje();">
                                                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                                                                </button>
                                </div>
                            </div>
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
<script type="text/javascript" src="{{asset('js/tramites/impuestos/administrar.js')}}"></script>
@endsection