<form>
    <input type="hidden" value="{{$preasignacion->id}}">
    <h4>Información del solicitante</h4>
    <div class="form-group">
        <label class="control-label" for="tipo_documento">Tipo documento identidad <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('tipo_documento', $tipos_documentos, $preasignacion->tipo_documento_identidad_id ,['class'=>'form-control', 'id'=>'tipo_documento']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_documento" class="form-control" value="{{$preasignacion->numero_documento_identidad}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_solicitante">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="nombre_solicitante" class="form-control" value="{{$preasignacion->nombre_solicitante}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="telefono_solicitante">Número telefónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="telefono_solicitante" class="form-control"  value="{{$preasignacion->numero_telefono_solicitante}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="correo_solicitante">Correo electrónico <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="email" name="correo_solicitante" class="form-control" value="{{$preasignacion->correo_electronico_solicitante}}">
    </div>
    <h4>Información del vehículo</h4>
    <div class="form-group">
        <label class="control-label" for="clase_vehiculo">Clase del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('clase_vehiculo', $clases_vehiculos, $preasignacion->vehiculo_clase_id, ['id'=>'clase_vehiculo', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="servicio_vehiculo">Servicio del vehículo <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('servicio_vehiculo', $servicios_vehiculos, $preasignacion->vehiculo_servicio_id, ['id'=>'servicio_vehiculo', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_motor">Número del motor <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_motor" class="form-control" value="{{$preasignacion->numero_motor}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_chasis">Número del chasis <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" class="form-control" name="numero_chasis"  value="{{$preasignacion->numero_chasis}}">
    </div>
    <h4>Información del propietario</h4>
    <div class="form-group">
        <label class="control-label" for="tipo_documento_propietario">Tipo documento identidad <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('tipo_documento_propietario', $tipos_documentos, null$preasignacion->tipo_documento_propietario_id ,['class'=>'form-control', 'id'=>'tipo_documento_propietario']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento_propietario">Número de documento <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="numero_documento_propietario" class="form-control" value="{{$preasignacion->numero_documento_propietario}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_propietario">Nombres y Apellidos <span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="nombre_propietario" class="form-control" value="{{$preasignacion->nombre_propietario}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="observaciones">Observaciones</label>
        <textarea name="observaciones" class="form-control">{{$preasignacion->observacion}}</textarea>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/preasignaciones/editarPreasignacion.js')}}"></script>