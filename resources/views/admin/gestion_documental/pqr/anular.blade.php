<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="motivo">Motivo</label>
        {!! Form::select('motivo', $motivos, null, ['class'=>'form-control','id'=>'motivo','required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="observacion">Observación</label>
        <textarea class="form-control" name="observacion" id="observacion" required></textarea>
    </div>
</form>