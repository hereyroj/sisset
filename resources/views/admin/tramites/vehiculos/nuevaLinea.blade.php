<form>
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Marca</label>
        {!! Form::select('marca', $marcas, null, ['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="cilindraje">Cilindraje</label>
        <input type="text" class="form-control" name="cilindraje">
    </div>
</form>