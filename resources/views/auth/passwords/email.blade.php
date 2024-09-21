@extends('layouts.app')

<!-- Main Content -->

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Restablecer la contraseña</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Correo electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>                                @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span> @endif
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    Solicitar restablecimiento de la contraseña
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                        {!! app('captcha')->render() !!}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection