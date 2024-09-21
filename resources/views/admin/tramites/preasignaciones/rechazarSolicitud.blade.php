<form>
    <input type="hidden" name="solicitud_id" value="{{$solicitud_id}}">
    <div class="form-group">
        <label for="motivo_rechazo" class="control-label">Motivo</label>
        {!! Form::select('motivo_rechazo', $motivosRechazo, null, ['id'=>'motivo_rechazo', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="observacion" class="control-label">Observación</label>
        <textarea name="observacion" class="form-control"></textarea>
    </div>
</form>