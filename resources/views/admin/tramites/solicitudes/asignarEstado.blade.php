<form>
    <input type="hidden" name="tramite_servicio" value="{{$id}}">
    <div class="form-group">
        <label for="tramite_servicio_estado" class="control-label">Estado</label>
        {{Form::select('tramite_servicio_estado', $estados, null, ['id'=>'tramite_servicio_estado', 'class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="observacion" class="control-label">Observación</label>
        <textarea name="observacion" id="observacion" class="form-control"></textarea>
    </div>
</form>