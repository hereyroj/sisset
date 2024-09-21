@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Radicar PQR - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-6">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="panel-title">Consultar proceso PQR</div>
                    </div>
                <div class="card-body">
                        <form method="POST" action="{{url('/servicios/pqr/consultar')}}" id="frm-pqr">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="control-label" for="tipo_documento">Tipo documento<span
                                            style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, old('tipo_documento'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="numero_documento">Número de documento<span
                                            style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <input type="text" name="numero_documento" class="form-control"
                                    value="{{old('numero_documento')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="radicado">Radicado</label>
                                <input type="text" name="radicado" class="form-control" placeholder="{{ \anlutro\LaravelSettings\Facade::get('empresa-sigla') }}-AÑO-100-CONSECUTIVO"
                                    value="{{old('radicado')}}">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Consultar</button>
                                <button type="reset" class="btn btn-danger">Cancelar</button>
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
        </div>
    </div>
@endsection