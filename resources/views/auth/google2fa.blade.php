@extends('layouts.app') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Google 2FA</title>
@endsection
 
@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Authenticación en dos pasos</div>
                <div class="card-body">
                    <p>La autenticación de dos factores (2FA) fortalece la seguridad de acceso al requerir dos métodos (también
                        conocidos como factores) para verificar su identidad. La autenticación de dos factores protege contra
                        ataques de phishing, ingeniería social y fuerza bruta de contraseñas y protege sus inicios de sesión
                        de los atacantes que explotan credenciales débiles o robadas.</p>
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <strong>Ingrese el PIN de Google Authenticator</strong><br/><br/>
                    <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
                            <label for="one_time_password" class="col-md-4 control-label">PIN</label>
                            <div class="col-md-6">
                                <input name="one_time_password" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-primary" type="submit">Autenticar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection