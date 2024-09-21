@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="background-color: #337ab7">Ingresar</div>
                <div class="card-body">
                    @if($errors->count()>0)
                    <p class="text-danger">Los datos proporcionados no son correctos.</p>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <div class="form-group @if($errors->count()>0) has-error @endif">
                            <label for="email" class="control-label">Correo electrónico</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="form-group @if($errors->count()>0) has-error @endif">
                            <label for="password" class="control-label">Contraseña</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Recordarme
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! app('captcha')->render('es') !!} 
                            @if (array_has($errors, 'g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ array_get($errors, 'g-recaptcha-response') }}</strong>
                            </span> 
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Ingresar
                            </button>
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                Olvidó su contraseña?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection