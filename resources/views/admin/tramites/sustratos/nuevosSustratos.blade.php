<form>
    <div class="form-group">
        <label class="control-label" for="tipo_sustrato_id">Tipo sustrato</label>
        {!! Form::select('tipo_sustrato_id', $tiposSustrato, null ,['class'=>'form-control', 'id'=>'tipo_sustrato_id']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numeros_rango_inicial">Números rango inicial</label>
        <input type="text" name="numeros_rango_inicial" id="numeros_rango_inicial" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="numeros_rango_final">Números rango final</label>
        <input type="text" name="numeros_rango_final" id="numeros_rango_final" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="cantidad_digitos">Cantidad dígitos rangos numéricos</label>
        <input type="number" name="cantidad_digitos" id="cantidad_digitos" class="form-control">
    </div>
</form>