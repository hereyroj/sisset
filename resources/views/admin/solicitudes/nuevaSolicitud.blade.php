<form>
    <div class="form-group">
        <label class="control-label">Motivo</label>
        {{Form::select('motivo', $motivos, null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label">Placa</label>
        <input type="text" class="form-control" name="placa" required>
    </div>
</form>