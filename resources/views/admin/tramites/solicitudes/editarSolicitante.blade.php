<form style="overflow-x:hidden;">
    <input type="hidden" name="solicitante" value="{{$usuario->id}}">
    <div class="form-group">
        <label class="control-label" for="tipo_documento">Tipo documento</label> 
        {!! Form::select('tipo_documento', $tiposDocumentos, $usuario->tipo_documento_identidad_id, ['class' => 'form-control', 'id'=>'tipo_documento', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="numero_documento" class="control-label">Numero</label>
        <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="{{$usuario->numero_documento}}" required>
    </div>
    <div class="form-group">
        <label for="nombre" class="control-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="{{$usuario->nombre_usuario}}" required>
    </div>
    <div class="form-group">
        <label for="telefono" class="control-label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" class="form-control" value="{{$usuario->numero_telefonico}}">
    </div>
    <div class="form-group">
        <label for="correo" class="control-label">Correo electrónico</label>
        <input type="email" id="correo" name="correo" class="form-control"value="{{$usuario->correo_electronico}}">
    </div>
</form>