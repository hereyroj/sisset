<form>
    <input type="hidden" name="ventanilla" value="{{$ventanilla}}">
    <div class="form-group">
        <label class="control-label">Llamar por</label>
        {{Form::select('criterio', $criterios, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="valor" required>
    </div>
</form>