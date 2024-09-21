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
        <label class="control-label" for="clase_vehiculo">Clase vehículo</label>
        {!! Form::select('clase_vehiculo', $clasesVehiculo, null, ['class'=>'form-control', 'required', 'id'=>'clase_vehiculo']) !!}
    </div>
    <div class="form-grupo">
        <label class="control-label" for="clase_vehiculo">Marca vehículo</label>
        {!! Form::select('marca_vehiculo', $marcasVehiculo, null, ['class'=>'form-control', 'required', 'id'=>'marca_vehiculo']) !!}
    </div>
</form>  