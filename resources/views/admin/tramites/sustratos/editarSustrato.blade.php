<form>
    <input type="hidden" name="sustrato" value="{{$sustrato->id}}">
    <div class="form-group">
        <label class="control-label" for="tipo_sustrato">Tipo de sustrato</label>
        {!! Form::select('tipo_sustrato', $tiposSustratos, $sustrato->tipo_sustrato_id ,['class'=>'form-control', 'id'=>'tipo_sustrato']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero">Número</label>
        <input type="text" name="numero" id="numero" class="form-control" value="{{$sustrato->numero}}">
    </div>
</form>