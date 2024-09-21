@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liquidar Servicio Público - {{Setting::get('empresa_sigla')}}</title>
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
                       Consulte su impuesto de servicio público
                    </div>
                </div>
                <div class="body-box">
                    <form role="form" method="POST" action="{{ url('/servicios/liquidaciones/servicioPublico/consultar') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="control-label" for="tipo_documento">Tipo documento<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, old('tipo_documento'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_documento">Número de documento<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="numero_documento" class="form-control" value="{{old('numero_documento')}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="placa">Placa</label>
                            <input type="text" name="placa" class="form-control" id="placa" value="{{old('placa')}}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </div>
                        {!! app('captcha')->render() !!}
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h2>Observaciones</h2>
            <p>
                Señor usuario, antes de realizar su liquidación de servicio público, le pedimos que tenga encuenta lo siguiente:
            </p>
            <br>
            <ul>
                <li></li>
            </ul>
        </div>
    </div>
@endsection