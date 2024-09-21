@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form enctype="multipart/form-data">
    <h4>Información del solicitante</h4>
    <div class="form-group">
        <label class="control-label" for="tipo_documento">Tipo documento identidad <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('tipo_documento', $tipos_documentos, old('tipo_documento') ,['class'=>'form-control', 'id'=>'tipo_documento']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_documento" class="form-control" required value="{{old('numero_documento')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_solicitante">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="nombre_solicitante" class="form-control" required value="{{old('nombre_solicitante')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="telefono_solicitante">Número telefónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="telefono_solicitante" class="form-control" required value="{{old('telefono_solicitante')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="correo_solicitante">Correo electrónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="email" name="correo_solicitante" class="form-control" required value="{{old('correo_solicitante')}}">
    </div>
    <h4>Información del vehículo</h4>
    <div class="form-group">
        <label class="control-label" for="clase_vehiculo">Clase del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('clase_vehiculo', $clases_vehiculos, old('clase_vehiculo'), ['id'=>'clase_vehiculo', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="servicio_vehiculo">Servicio del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <select class="form-control" name="servicio_vehiculo" id="servicio_vehiculo" required></select>
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_motor">Número del motor <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_motor" class="form-control" required value="{{old('numero_motor')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_chasis">Número del chasis <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" class="form-control" name="numero_chasis" required value="{{old('numero_chasis')}}">
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
        {!! Form::select('tipo_documento_propietario', $tipos_documentos, old('tipo_documento_propietario') ,['class'=>'form-control', 'id'=>'tipo_documento_propietario']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento_propietario">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_documento_propietario" class="form-control" required value="{{old('numero_documento_propietario')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_propietario">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="nombre_propietario" class="form-control" required value="{{old('nombre_propietario')}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="cedula_propietario" name="cedula_propietario" required>
        <label class="custom-file-label" for="cedula_propietario">Cedula propietario (jpeg,jpg,png)</label>
    </div>
    <div class="form-group">
        <label class="control-label" for="observaciones">Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/preasignaciones/nuevaPreasignacion.js')}}"></script>