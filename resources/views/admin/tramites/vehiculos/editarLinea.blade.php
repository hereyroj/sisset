<form>
    <input type="hidden" name="id" value="{{$linea->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" value="{{$linea->nombre}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Marca</label>
        {!! Form::select('marca', $marcas, $linea->vehiculo_marca_id, ['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="cilindraje">Cilindraje</label>
        <input type="text" class="form-control" name="cilindraje" value="{{$linea->cilindraje}}">
    </div>
</form>