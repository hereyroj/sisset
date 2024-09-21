@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consultar normativas - {{Setting::get('empresa_sigla')}}</title>
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
                        Consulte todas las normativas relacionadas con esta entidad.
                    </div>
                </div>
                <div class="body-box">
                    <form role="form" method="POST" action="{{ url('/servicios/normativas/consultar') }}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <label for="tipoCriterio" class="form-label">Buscar por <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <select class="form-control" name="tipoCriterio" id="tipoCriterio">
                                <option value="fecha" @if(isset($tipocriterio) && $tipocriterio == 'fecha') selected @endif>
                                    Fecha (Año-Mes-Día)
                                </option>
                                <option value="numero" @if(isset($tipocriterio) && $tipocriterio == 'numero') selected @endif>
                                    Número
                                </option>
                                <option value="objeto" @if(isset($tipocriterio) && $tipocriterio == 'objeto') selected @endif>
                                    Objeto
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="criterio" name="criterio" required @if(isset($parametro)) value="{{$parametro}}" @endif>
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
            @if(isset($normativas) && count($normativas)>0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th>Fecha</th>
                        <th>Objeto</th>
                        <th>Archivo</th>    
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($normativas as $normativa)
                        <tr>
                            <td>{{$normativa->hasTipo->name}}</td>
                            <td>{{$normativa->numero}}</td>
                            <td>{{$normativa->fecha_expedicion}}</td>
                            <td>{{$normativa->objeto}}</td>
                            <td><a href="{{url('servicios/normativas/documento/'.$normativa->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ url('/') }}" class="btn btn-primary">Volver</a>
            @else
                @if(isset($parametro))
                    <h2>No se han encontrado normativas con los criterios especificados.</h2>
                @else
                    <h2>Por favor especifique los datos de la consulta en el recuadro de la izquierda.</h2>
                @endif
            @endif
        </div>
    </div>
@endsection