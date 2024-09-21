<form>
    <input type="hidden" name="sustratoId" value="{{$sustratoId}}">
    <div class="form-group">
        <label class="control-label">Motivo de liberación</label>
        {{Form::select('motivo_liberacion', $motivos, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label">Observación</label>
        <textarea class="form-control" name="observacion"></textarea>
    </div>
</form>