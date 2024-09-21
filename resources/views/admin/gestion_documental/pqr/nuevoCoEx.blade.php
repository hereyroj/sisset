@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! Form::open(['id'=>'frm-nuevo-coex', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'style'=>'padding:15px;']) !!}
<div class="form-group">
    <label class="control-label" for="nombre">Nombre<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}" required>
</div>
<div class="form-group">
    <label class="control-label" for="tipo_documento">Tipo documento<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, old('tipo_documento'), ['class' => 'form-control', 'id'=>'tipo_documento', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="numero_documento">Número de documento</label>
    <input type="text" name="numero_documento" class="form-control" value="{{old('numero_documento')}}" required>
</div>
<div class="form-group">
    <label class="control-label" for="numero_oficio">Número de oficio</label>
    <input type="text" name="numero_oficio" class="form-control" value="{{old('numero_oficio')}}">
</div>
<div class="form-group">
    <label class="control-label" for="responde_oficio">Oficio al que responde (Institucional)</label>
    <input type="text" name="responde_oficio" class="form-control" value="{{old('responde_oficio')}}">
</div>
<div class="form-group">
    <label class="control-label" for="numero_telefono">Número telefónico (celular o fijo)</label>
    <input type="text" name="numero_telefono" class="form-control" value="{{old('numero_telefono')}}">
</div>
<div class="form-group">
    <label class="control-label" for="correo_electronico">Correo electrónico</label>
    <input type="email" name="correo_electronico" class="form-control" value="{{old('correo_electronico')}}">
</div>
<div class="form-group">
    <label class="control-label" for="correo_electronico_notificacion">Correo electrónico de notificación</label>
    <input type="email" name="correo_electronico_notificacion" class="form-control" value="{{old('correo_electronico_notificacion')}}">
</div>
<div class="form-group">
    <label class="control-label" for="direccion">Dirección residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion')}}" required>
</div>
<div class="form-group">
    <label class="control-label" for="departamento">Departamento residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('departamento', $departamentos, old('departamento'), ['class' => 'form-control', 'id' => 'departamento', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="municipio">Municipio residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <select name="municipio" id="municipio" class="form-control" required></select>
</div>
<div class="form-group">
    <label class="control-label" for="pqr_clase">Clase PQR<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('pqr_clase', $clasesPQR, old('pqr_clase'), ['class' => 'form-control', 'id'=>'pqr_clase', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="medio">Medio traslado<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('medio', $mediosTraslado, old('medio'), ['class' => 'form-control', 'id' => 'medio', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="asunto">Asunto<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <input type="text" name="asunto" class="form-control" value="{{old('asunto')}}" required>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/nuevoCoEx.js')}}"></script>