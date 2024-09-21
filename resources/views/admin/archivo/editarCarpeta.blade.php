<form>
    <input type="hidden" name="idCarpeta" id="idCarpeta" value="{{$carpeta->id}}">
    <div class="form-group">
        <label class="form-label" for="placa">Placa</label>
        <input class="form-control" name="name" id="name" value="{{$carpeta->name}}" maxlength="6">
    </div>
    <div class="form-group">
        <label class="form-label" for="claseVehiculo">Clases de vehículos</label>
        {{Form::select('claseVehiculo', $clasesVehiculos, $carpeta->vehiculo_clase_id, ['id'=>'claseVehiculo', 'class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="form-label" for="servicioVehiculo">Servicios de vehículos</label>
        {{Form::select('servicioVehiculo', $serviciosVehiculos, $carpeta->vehiculo_servicio_id, ['id'=>'servicioVehiculo', 'class'=>'form-control'])}}
    </div>
</form>