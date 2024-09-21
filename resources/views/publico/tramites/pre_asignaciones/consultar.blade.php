@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consultar Pre-Asignación - {{Setting::get('empresa_sigla')}}</title>
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
                        Consulte el estado de su Pre-Asignación
                    </div>
                </div>
                <div class="body-box">
                    <form role="form" method="POST" action="{{ url('/servicios/tramites/preasignaciones/consultar') }}">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <label class="control-label" for="motor">Número de motor</label>
                            <input type="text" class="form-control" id="motor" name="motor" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_documento">Número de documento propietario</label>
                            <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
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
            <h4>Por favor especifique los datos de la consulta en el recuadro de la izquierda.</h4>
        </div>
    </div>
@endsection