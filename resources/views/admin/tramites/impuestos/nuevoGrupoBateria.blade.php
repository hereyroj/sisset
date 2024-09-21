<form>
    <div class="form-grupo">
        <label class="control-label" for="">Vigencia</label>
        <input type="text" class="form-control" name="vigencia" required>
    </div>
    <div class="form-grupo">
        <label class="control-label" for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" required>
    </div>
    <div class="form-grupo">
        <label class="control-label" for="bateria_tipo">Tipo batería</label>
        {!! Form::select('bateria_tipo', $tiposBateria, null, ['class'=>'form-control', 'required', 'id'=>'bateria_tipo']) !!}
    </div>
    <div class="form-grupo">
        <label class="control-label" for="desde">Desde (capacidad)</label>
        <input type="number" class="form-control" name="desde" required>
    </div>
    <div class="form-grupo">
        <label class="control-label" for="hasta">Hasta (capacidad)</label>
        <input type="number" class="form-control" name="hasta" required>
    </div>
</form>            