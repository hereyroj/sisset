<form>
    <div class="form-group">
        <label class="form-label" for="placa">Placa</label>
        <input type="text" name="placa" class="form-control" id="placa">
    </div>
    <div class="form-group">
        <label class="form-label" for="claseVehiculo">Clase de vehículo</label>
        {!! Form::select('claseVehiculo', $clasesVehiculos, null, ['class' => 'form-control', 'id' => 'claseVehiculo']) !!}
    </div>
    <div class="form-group">
        <label class="form-label" for="servicioVehiculo">Servicio del vehículo</label>
        {!! Form::select('servicioCarpeta', $serviciosVehiculos, null, ['class' => 'form-control', 'id' => 'servicioCarpeta']) !!}
    </div>
    <div class="form-group">
        <label class="form-label" for="estadoCarpeta">Estado</label>
        {!! Form::select('estadoCarpeta', $estadosCarpetas, null, ['class' => 'form-control', 'id'=>'estadoCarpeta']) !!}
    </div>
</form>