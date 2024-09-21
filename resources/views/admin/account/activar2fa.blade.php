@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Google 2FA - {{Setting::get('empresa_sigla')}}</title>
@endsection
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="margin-top: 3em;">
            <div class="card">
                <div class="card-header">Configurar Google Authenticator</div>

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                <a href="/admin/cuenta/perfil" class="btn btn-success">Finalizar</a> @else
                <div class="panel-body" style="text-align: center;">
                    <p>Configure la autenticación en dos pasos escaneando el código de barras a continuación. Alternativamente,
                        puedes usar el código {{ $secret }}</p>
                    <div>
                        @if(old('QR_Image'))
                        <img src="{{ old('QR_Image') }}" name="QR_Image"> @else
                        <img src="{{ $QR_Image }}" name="QR_Image"> @endif
                    </div>
                    <p>Debe configurar su aplicación Google Authenticator antes de continuar. No podrá iniciar sesión de otra
                        manera.</p>
                    <form action="{{url('/admin/cuenta/registrar2fa')}}" method="POST" style="padding: 20px;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}"> @if(old('secret'))
                        <input type="hidden" name="secret" value="{{old('secret')}}"> @else
                        <input type="hidden" name="secret" value="{{$secret}}"> @endif
                        <div class="form-group">
                            <label class="control-label" for="verify-code">Código de verificación dado por Google Authenticator</label>
                            <input type="text" class="form-control" name="verify-code" required autofocus>
                        </div>
                        <input type="submit" class="btn btn-primary" name="Completar la vinculación">
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection