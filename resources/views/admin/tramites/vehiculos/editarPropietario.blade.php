<form>
    <input type="hidden" name="id" value="{{$propietario->id}}">
    <div class="form-group">
        <label class="control-label" for="tipo_documento">Tipo documento</label>
        {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, $propietario->tipo_documento_id, ['class' => 'form-control', 'id'=>'tipo_documento', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="numero_documento" class="control-label">Numero</label>
        <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="{{$propietario->numero_documento}}">
    </div>
    <div class="form-group">
        <label for="nombre" class="control-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="{{$propietario->nombre}}">
    </div>
    <div class="form-group">
        <label for="telefono" class="control-label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" class="form-control" value="{{$propietario->telefono}}">
    </div>
    <div class="form-group">
        <label for="correo" class="control-label">Correo electrónico</label>
        <input type="email" id="correo" name="correo" class="form-control" value="{{$propietario->correo_electronico}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="departamento">Departamento residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('departamento', $departamentos, $propietario->departamento_id, ['class' => 'form-control', 'id' => 'departamento', 'required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="municipio">Municipio residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <select name="municipio" id="municipio" class="form-control" required></select>
    </div>
    <div class="form-group">
        <label class="control-label" for="direccion">Dirección residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="direccion" id="direccion" class="form-control"  value="{{$propietario->direccion}}" required>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/editarPropietario.js')}}"></script>