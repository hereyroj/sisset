@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consultar notificaciones por aviso - {{Setting::get('empresa_sigla')}}</title>
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
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-box">
                <div class="title-box">
                    <div class="title-box-title">
                        Consulte si ha sido Notificado por Aviso
                    </div>
                </div>
                <div class="body-box">
                    <form role="form" method="POST" action="{{ url('/servicios/notificacionesAviso/consultarNotificacionesAviso') }}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <label for="tipoCriterio" class="form-label">Buscar por <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <select class="form-control" name="tipoCriterio" id="tipoCriterio">
                                <option value="cc">Número documento</option>
                                <option value="name">Nombre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="criterio" name="criterio" required>
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
            @if(isset($notificacionesAviso) && count($notificacionesAviso)>0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Tipo de notificación</th>
                        <th>Número de proceso</th>                        
                        <th>Nombres y Apellidos</th>
                        <th>Número documento</th>
                        <th>Archivo</th>
                        <th>Fecha de publicación</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($notificacionesAviso as $notificacionAviso)
                        <tr>
                            <td>{{$notificacionAviso->hasTipoNotificacion->name}}</td>
                            <td>{{$notificacionAviso->numero_proceso}}</td>
                            <td>{{$notificacionAviso->nombre_notificado}}</td>
                            <td>{{$notificacionAviso->numero_documento}}</td>                            
                            <td><a href="{{url('servicios/notificacionesAviso/documento/'.$notificacionAviso->id)}}" class="btn btn-secondary">Ver</a></td>
                            <td>{{$notificacionAviso->fecha_publicacion}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                <a href="{{ url('/') }}" class="btn btn-primary">Volver</a>  <a href="{{ url('/servicios/notificacionesAviso/exportar/'.$parametro) }}" class="btn btn-success">Exportar</a>
            @else
                @if(isset($parametro) && count($errors) <= 0)
                    <h2>No se han realizado notificaciones por aviso al nombre o cédula especificado.</h2>
                @else
                    <h2>Por favor especifique los datos de la consulta en el recuadro de la izquierda.</h2>
                @endif
            @endif
        </div>
    </div>
@endsection