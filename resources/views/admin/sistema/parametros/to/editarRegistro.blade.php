<form enctype="multipart/form-data">
    <input type="hidden" name="registro_id" value="{{$registro->id}}">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, $registro->vigencia_id, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="from-group">
        <label class="control-label" for="consecutivo_inicial">Consecutivo inicial</label>
        <input type="text" class="form-control" name="consecutivo_inicial" id="consecutivo_inicial" required>
    </div>
    <div class="from-group">
        <label class="control-label" for="marca_agua">Marca de agua</label>
        <input type="text" class="form-control" name="marca_agua" id="marca_agua" required>
    </div>
    <div class="from-group">
        <label class="control-label" for="valor_unitario">Valor unitario</label>
        <input type="text" class="form-control" name="valor_unitario" id="valor_unitario" required>
    </div>
</form>