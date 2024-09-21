<form>
    <div class="form-group">
        <label class="control-label" for="ventanilla">Ventanillas</label>
        {{Form::select('ventanilla', $ventanillas, null, ['id'=>'ventanilla', 'class'=>'form-control'])}}
    </div>
</form>