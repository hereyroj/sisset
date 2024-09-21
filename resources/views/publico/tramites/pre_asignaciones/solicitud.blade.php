@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Solicitar Pre-asignación - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger col-md-12">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('mensaje'))
        <div class="alert alert-success col-md-12">
            {{session('mensaje')}}
        </div>
    @endif
    <div class="row justify-content-md-center">        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="panel-title">Solicitar Pre-asignación</div>
                </div>
               <div class="card-body">
                    <form method="post" action="{{url('/servicios/tramites/preasignaciones/crearSolicitudPreAsignacion')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h4>Información del solicitante</h4>
                        <div class="form-group">
                            <label class="control-label" for="tipo_documento">Tipo documento identidad <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            {!! Form::select('tipo_documento', $tipos_documentos, old('tipo_documento') ,['class'=>'form-control', 'id'=>'tipo_documento', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_documento">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="numero_documento" class="form-control" value="{{old('numero_documento')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="nombre_solicitante">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="nombre_solicitante" class="form-control" value="{{old('nombre_solicitante')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="telefono_solicitante">Número telefónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="telefono_solicitante" class="form-control" value="{{old('telefono_solicitante')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="correo_solicitante">Correo electrónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="email" name="correo_solicitante" class="form-control" value="{{old('correo_solicitante')}}" required>
                        </div>
                        <h4>Información del vehículo</h4>
                        <div class="form-group">
                            <label class="control-label" for="clase_vehiculo">Clase del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            {!! Form::select('clase_vehiculo', $clases_vehiculos, old('clase_vehiculo'), ['id'=>'clase_vehiculo', 'class'=>'form-control', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="servicio_vehiculo">Servicio del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <select class="form-control" name="servicio_vehiculo" id="servicio_vehiculo" required></select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_motor">Número del motor <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="numero_motor" class="form-control" value="{{old('numero_motor')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_chasis">Número del chasis <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" class="form-control" name="numero_chasis" value="{{old('numero_chasis')}}" required>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="manifiesto_importacion" name="manifiesto_importacion" required>
                            <label class="custom-file-label" for="manifiesto_importacion">Manifiesto de importación (jpeg,jpg,png)</label>
                            <span style="color: #990000; width: 3px;height: 3px;">*</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="factura_compra" name="factura_compra" required>
                            <label class="custom-file-label" for="factura_compra">Factura de compra (jpeg,jpg,png)</label>
                            <span style="color: #990000; width: 3px;height: 3px;">*</span>
                        </div>
                        <h4>Información del propietario</h4>
                        <div class="form-group">
                            <label class="control-label" for="tipo_documento_propietario">Tipo documento identidad <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            {!! Form::select('tipo_documento_propietario', $tipos_documentos, old('tipo_documento_propietario'),['class'=>'form-control', 'id'=>'tipo_documento_propietario', 'required']) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="numero_documento_propietario">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="numero_documento_propietario" class="form-control" value="{{old('numero_documento_propietario')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="nombre_propietario">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                            <input type="text" name="nombre_propietario" class="form-control" value="{{old('nombre_propietario')}}" required>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="cedula_propietario" name="cedula_propietario" required>
                            <label class="custom-file-label" for="cedula_propietario">Cedula propietario (jpeg,jpg,png)</label>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="observaciones">Observaciones</label>
                            <textarea name="observaciones" class="form-control"></textarea>
                        </div>                        
                        <div class="form-group" style="margin-top:20px">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! app('captcha')->render() !!}
                                    @if (array_has($errors, 'g-recaptcha-response'))
                                        <span class="help-block">
                                            <strong>{{ array_get($errors, 'g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" class="btn btn-primary" value="Enviar solicitud">
                                    <input type="reset" class="btn btn-danger" value="Cancelar">
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/publico/tramites/pre_asignaciones/solicitud.js')}}"></script>
@endsection