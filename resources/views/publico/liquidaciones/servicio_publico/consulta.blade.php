@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liquidar Servicio Público - {{Setting::get('empresa_sigla')}}</title>
    <link rel="stylesheet" href="{{ asset('js/vendor/jquery-confirm/jquery-confirm.min.css')}}">
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

    <style>
        .center-th {
            text-align: center;
            vertical-align: middle !important;
        }

        .iconos {
            text-align: center;
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
    </style>
@endsection

@section('content')
    <div id="infoVehiculo" class="col-md-2" style="border-right: 1px solid gray">
        <div class="form-group">
            <label class="control-label">Placa</label>
            <input type="text" class="form-control" value="{{$vehiculo->placa}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Modelo</label>
            <input type="text" class="form-control" value="{{$vehiculo->modelo}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Número motor</label>
            <input type="text" class="form-control" value="{{$vehiculo->numero_motor}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Número chasis</label>
            <input type="text" class="form-control" value="{{$vehiculo->numero_chasis}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Capacidad de pasajeros</label>
            <input type="text" class="form-control" value="{{$vehiculo->capacidad_pasajeros}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Capacidad de toneladas</label>
            <input type="text" class="form-control" value="{{$vehiculo->capacidad_toneladas}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Clase</label>
            <input type="text" class="form-control" value="{{$vehiculo->hasTipoVehiculo->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Marca</label>
            <input type="text" class="form-control" value="{{$vehiculo->hasMarca->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Linea</label>
            @if($vehiculo->hasLinea != null)
                <input type="text" class="form-control" value="{{$vehiculo->hasLinea->name}}" disabled>
            @else
                <input type="text" class="form-control" value="" disabled>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label">Tipo de carroceria</label>
            <input type="text" class="form-control" value="{{$vehiculo->hasTipoCarroceria->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Clase de combustible</label>
            <input type="text" class="form-control" value="{{$vehiculo->hasClaseCombustible->name}}" disabled>
        </div>
    </div>
    <div id="liquidacionesVehiculo" class="col-md-10">
        <div class="cabecera-tabla">
            <div>
                <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="actualizarLiquidaciones({{$vehiculo->id}});">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Actualizar
                </button>
            </div>
            <div>
                <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaLiquidacion({{$vehiculo->id}});">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                </button>
            </div>
            <div  style="float: right;">
                <a href="{{url('/')}}" class="btn btn-danger btn-actualizar btn-md"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Salir</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Vigencia</th>
                    <th>Fecha expedición</th>
                    <th>Fecha vencimiento</th>
                    <th>Avaluo</th>
                    <th>Impuesto</th>
                    <th>Descuentos</th>
                    <th>Mora</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vehiculo->hasLiquidaciones as $liquidacion)
                    <tr>
                        <td>
                            {{$liquidacion->codigo}}
                        </td>
                        <td>
                            {{$liquidacion->hasVigencia->vigencia}}
                        </td>
                        <td>
                            {{$liquidacion->created_at}}
                        </td>
                        <td>
                            {{$liquidacion->fecha_vencimiento}}
                        </td>
                        <td>
                            ${{number_format($liquidacion->valor_avaluo, 0, ',','.')}}
                        </td>
                        <td>
                            ${{number_format($liquidacion->valor_impuesto, 0, ',','.')}}
                        </td>
                        <td>
                            ${{number_format($liquidacion->valor_descuento_total, 0, ',','.')}}
                        </td>
                        <td>
                            ${{number_format($liquidacion->valor_mora_total, 0, ',','.')}}
                        </td>
                        <td>
                            ${{number_format($liquidacion->valor_total, 0, ',','.')}}
                        </td>
                        <td>
                            <a href="{{url('servicios/liquidaciones/servicioPublico/imprimirLiquidacion/'.$liquidacion->id)}}" class="btn btn-secondary">Imprimir</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{asset('js/vendor/jquery-confirm/jquery-confirm.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/publico/liquidaciones/servicio_publico/consulta.js')}}"></script>
@endsection
