@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consultar tarjeta de operación - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

    <style>
        .center-th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-box">
                <div class="title-box">
                    <div class="title-box-title">
                        Consultar tarjetas de operación
                    </div>
                </div>
                <div class="body-box">
                    <form role="form" method="POST" action="{{ url('servicios/to/consultar') }}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <h4>Criterio de búsqueda</h4>
                        <div class="form-group">
                            <label for="tipo">Buscar por <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            {!! Form::select('tipo', ['Placa' => 'Placa', 'Codigo'=>'Código'], $tipocriterio, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="numero">Número <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" class="form-control" id="numero" name="numero" required @if(isset($parametro)) value="{{$parametro}}" @endif>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </div>
                        {!! app('captcha')->render() !!}

                        @if (array_has($errors, 'g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ array_get($errors, 'g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @if(isset($tos) && count($tos) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2" class="center-th">Vigencia</th>
                        <th colspan="3" class="center-th">Información del vehículo</th>
                        <th colspan="4" class="center-th">Información de la empresa</th>
                        <th rowspan="2" class="center-th">Código Tarjeta</th>
                    </tr>
                    <tr>
                        <th>Vencimiento</th>
                        <th>Expedición</th>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Razón social</th>
                        <th>Nivel del servicio</th>
                        <th>Número interno</th>
                        <th>Radio de operación</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tos as $to)
                        @if($to->fecha_vencimiento <= date('Y:m:d'))
                            <tr class="alert-warning">
                        @else
                            <tr>
                                @endif
                                <td>{{$to->fecha_vencimiento}}</td>
                                <td>{{$to->created_at}}</td>
                                <td>{{$to->placa}}</td>
                                <td>{{$to->hasMarca->name}}</td>
                                <td>{{$to->modelo}}</td>
                                <td>{{$to->hasEmpresaTransporte->name}}</td>
                                <td>{{$to->hasNivelServicio->name}}</td>
                                <td>{{$to->numero_interno}}</td>
                                <td>{{$to->hasRadioOperacion->name}}</td>
                                <td>{{$to->id}}</td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            @if(isset($parametro))
                <h2>No hay Tarjetas de Operación asociada a la información suministrada.</h2>
            @else
                <h2>Por favor especifique los datos de la consulta en el recuadro de la izquierda.</h2>
            @endif
        @endif
    </div>
@endsection