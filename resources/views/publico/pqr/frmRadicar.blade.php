@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Radicar PQR - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('content')
    <div class="container">
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
                <div class="panel-title">Radicar PQR</div>
            </div>
           <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="{{url('/servicios/pqr/procesar')}}" id="frm-pqr"
                            enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="radicado" value="E">
                            <input type="hidden" name="pdf" value="1">
                            <input type="hidden" name="medio" value="1">
                            <div class="form-group">
                                <label class="control-label" for="nombre">Nombre<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="tipo_documento">Tipo documento<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, old('tipo_documento'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="numero_documento">Número de documento</label>
                                <input type="text" name="numero_documento" class="form-control" value="{{old('numero_documento')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="numero_telefono">Número telefónico (celular o fijo)</label>
                                <input type="text" name="numero_telefono" class="form-control" value="{{old('numero_telefono')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="correo_electronico">Correo electrónico</label>
                                <input type="email" name="correo_electronico" class="form-control"  value="{{old('correo_electronico')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="correo_electronico_notificacion">Correo electrónico de notificación</label>
                                <input type="email" name="correo_electronico_notificacion" class="form-control"  value="{{old('correo_electronico_notificacion')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="direccion">Dirección residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <input type="text" name="direccion" id="direccion" class="form-control"  value="{{old('direccion')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="departamento">Departamento residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                {!! Form::select('departamento', $departamentos, null, ['class' => 'form-control', 'id' => 'departamento']) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="municipio">Municipio residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <select name="municipio" id="municipio" class="form-control"></select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="numero_oficio">Número de oficio</label>
                                <input type="text" name="numero_oficio" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="responde_oficio">Oficio al que responde</label>
                                <input type="text" name="responde_oficio" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="tipo_clase">Tipo PQR<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                {!! Form::select('pqr_clase', $clasesPQR, old('pqr_clase'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="asunto">Asunto<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <input type="text" name="asunto" class="form-control"  value="{{old('asunto')}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="descripcion">Mensaje<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
                                <textarea name="descripcion" class="form-control" placeholder="Máximo 1500 caracteres">{{old('descripcion')}}</textarea>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="anexos" name="anexos">
                                <label class="custom-file-label" for="anexos">Cargar anexos (comprimido en ZIP. máximo 1 archivo)</label>
                            </div>
                            <div class="form-group" style="margin-top: 20px;">
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
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Radicar PQR</button>
                                        <button type="reset" class="btn btn-danger btn-lg btn-block">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 panel">
                        <h4><strong>Instructivo para diligenciar el formulario</strong></h4>
                        <p style="text-align: justify">
                            Su petición debe contener: <br>
                            1.Los nombres y apellidos completos del solicitante con indicación del documento de identidad y
                            dirección. <br>
                            2.El objeto de la petición. <br>
                            3.Las razones en que se apoya. <br>
                            4.La relación de documentos que se acompañan.<br>
                        </p>
                        <p style="text-align: justify">
                            <strong>REQUISITOS OBLIGATORIOS:</strong> debe responder a los campos marcados con el asterisco ( <span style="color: #990000; width: 3px;height: 3px;">*</span> ).
                        </p>
                        <p style="border:#ccc 1px solid; border-radius: 5px;padding: 5px;">
                            Políticas TIC
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/publico/pqr/frmRadicar.js')}}"></script>
@endsection