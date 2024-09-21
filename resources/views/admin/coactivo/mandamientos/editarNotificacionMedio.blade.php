<form>
    <div class="form-group">
        <label class="control-label" for="">Medio</label>
        {{Form::select('notificacion_medio', $medios, $notificacion->hasMedio->mandamiento_medio_id, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Empresa transporte</label>
        {{Form::select('tipo_notificacion', $tipos, $notificacion->hasMedio->empresa_transporte_id, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Número de guía</label>
        <input type="text" class="form-control" name="numero_guia" value="{{$notificacion->hasMedio->numero_guia}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación empresa transporte</label>
        <textarea class="form-control" name="observacion_empresa">{{$notificacion->hasMedio->observacion_empresa_transporte}}</textarea>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observacion entidad/label>
        <textarea class="form-control" name="observacion_entidad">{{$notificacion->hasMedio->observacion_entidad}}</textarea>
    </div>
</form>