<form>
    <input type="hidden" name="vehiculo_id" value="{{$vehiculo->id}}">
    <input type="hidden" name="linea" id="linea" value="{{$vehiculo->vehiculo_linea_id}}">
    <div class="form-group">
        <label for="placa">Placa</label>
        <input type="text" class="form-control reset" id="placa" name="placa" minlength="6" maxlength="6" value="{{$vehiculo->placa}}" required>
    </div>
    <div class="form-group">
        <label for="tipoVehiculo">Tipo de vehículo</label>
        {!! Form::select('tipoVehiculo', $clases, $vehiculo->vehiculo_clase_id, ['class' => 'form-control  reset', 'id'=>'tipoVehiculo', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="tipoCarroceria">Tipo de carroceria</label>
        {!! Form::select('tipoCarroceria', $carrocerias, $vehiculo->vehiculo_carroceria_id, ['class' => 'form-control', 'id'=>'tipoCarroceria', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="marcaVehiculo">Marca</label>
        {!! Form::select('marcaVehiculo', $marcas, $vehiculo->vehiculo_marca_id, ['class' => 'form-control', 'id'=>'marcaVehiculo', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="lineaVehiculo">Línea</label>
        <select class="form-control" name="lineaVehiculo" id="lineaVehiculo" required></select>
    </div>
    <div class="form-group">
        <label for="modeloVehiculo">Modelo</label>
        <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" value="{{$vehiculo->modelo}}" minlength="4" maxlength="4" required>
    </div>
    <div class="form-group">
        <label for="colorVehiculo">Color</label>
        <input type="text" class="form-control" id="colorVehiculo" name="colorVehiculo" required value="{{$vehiculo->color}}">
    </div>
    <div class="form-group">
        <label for="puertasVehiculo">Puertas</label>
        <input type="text" class="form-control" id="puertasVehiculo" name="puertasVehiculo" required value="{{$vehiculo->puertas}}">
    </div>
    <div class="form-group">
        <label for="claseCombustible">Clase de combustible</label>
        {!! Form::select('claseCombustible', $combustibles, $vehiculo->vehiculo_combustible_id, ['class' => 'form-control', 'id'=>'claseCombustible', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="numeroMotor">Número motor</label>
        <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" value="{{$vehiculo->numero_motor}}" required>
    </div>
    <div class="form-group">
        <label for="numeroChasis">Número chasis</label>
        <input type="text" class="form-control" id="numeroChasis" name="numeroChasis" value="{{$vehiculo->numero_chasis}}" required>
    </div>
    <div class="form-group">
        <label for="capacidadPasajeros">Capacidad de pasajeros</label>
        <input type="text" class="form-control" id="capacidadPasajeros" name="capacidadPasajeros" value="{{$vehiculo->capacidad_pasajeros}}" required>
    </div>
    <div class="form-group">
        <label for="capacidadToneladas">Capacidad de toneladas</label>
        <input type="text" class="form-control" id="capacidadToneladas" name="capacidadToneladas" value="{{$vehiculo->capacidad_toneladas}}" required>
    </div>
    <div class="form-group">
        <label for="tipoBateria">Tipo de batería (Para eléctricos)</label>
        {!! Form::select('tipoBateria', $tiposBaterias, $vehiculo->vehiculo_bateria_tipo_id, ['class' => 'form-control', 'id'=>'tipoBateria']) !!}
    </div>
    <div class="form-group">
        <label for="bateriaCapacidad">Capacidad de la batería (Para eléctricos)</label>
        <input type="text" class="form-control" id="bateriaCapacidad" name="bateriaCapacidad" value="{{$vehiculo->bateria_capacidad_watts}}">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/editarVehiculo.js')}}"></script>