<form>
    <input type="hidden" name="sustratoId" value="{{$sustratoId}}">
    <div class="form-group">
        <label class="control-label">Motivo de anulación</label>
        {{Form::select('motivo_anulacion', $motivos, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label">Observación</label>
        <textarea class="form-control" name="observacion"></textarea>
    </div>
</form>