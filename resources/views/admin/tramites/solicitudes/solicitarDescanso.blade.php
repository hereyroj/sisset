<form>
    <div class="form-group">
        <label class="control-label" for="motivo">Motivo</label>
        {{Form::select('motivo', $motivos, null, ['class'=>'form-control', 'required', 'id'=>'motivo'])}}
    </div>
</form>