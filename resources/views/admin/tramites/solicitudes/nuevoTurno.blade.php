<form id="nueva_solicitud">
    <input type="hidden" name="solicitud_id" value="{{$solicitud_id}}">
    <div class="form-group">
        <label class="control-label" for="">Tipo Documento Identidad</label>
        {!! Form::select('tipo_documento_identidad', $tiposDocumentos, null, ['id'=>'tipo_documento_identidad', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento">Número Documento Identidad</label>
        <input type="text" id="numero_documento" name="numero_documento" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_usuario">Nombre usuario</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_telefonico">Número telefónico de contacto</label>
        <input type="text" id="numero_telefonico" name="numero_telefonico" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="correo_electronico">Correo electrónico de contacto</label>
        <input type="email" id="correo_electronico" name="correo_electronico" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_solicitud_origen">Origen de la solicitud</label>
        {!! Form::select('tramite_solicitud_origen', $tramitesSolicitudOrigenes, null, ['id'=>'tramite_solicitud_origen', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="preferente">Preferente</label>
        {!! Form::select('preferente', ['0'=>'NO','1'=>'SI'], null, ['id'=>'preferente', 'class'=>'form-control']) !!}
    </div>
</form>