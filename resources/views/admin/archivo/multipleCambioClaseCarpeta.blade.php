<form>
    <div class="form-group">
        <label class="form-label" for="claseVehiculo">Clase de vehículo</label>
        {!! Form::select('claseVehiculo', $clasesVehiculos, null, ['class' => 'form-control', 'id' => 'claseVehiculo']) !!}
    </div>
</form>