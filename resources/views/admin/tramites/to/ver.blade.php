{!! Form::open() !!}
<div class="row">
    <div class="form-group col-md-4">
        <label for="fechaVencimiento">Fecha de vencimiento</label>
        <input type="date" class="form-control datepicker picker-editar" id="fechaVencimiento" name="fechaVencimiento" placeholder="Clic para establecer fecha" value="{{$to['fecha_vencimiento']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="placa">Placa</label>
        <input type="text" class="form-control" id="placa" name="placa" placeholder="" value="{{ $to['placa']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="tipoVehiculo">Tipo de vehículo</label>
        <input type="text" class="form-control" id="tipoVehiculo" name="tipoVehiculo" placeholder="" value="{{ $to['hasTipoVehiculo']['name']}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="tipoCarroceria">Tipo de carroceria</label>
        <input type="text" class="form-control" id="tipoCarroceria" name="tipoCarroceria" placeholder="" value="{{ $to['hasTipoCarroceria']['name']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="marcaVehiculo">Marca</label>
        <input type="text" class="form-control" id="marcaVehiculo" name="marcaVehiculo" placeholder="" value="{{ $to['hasMarca']['name']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="modeloVehiculo">Modelo</label>
        <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" placeholder="" value="{{$to['modelo']}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="claseCombustible">Clase de combustible</label>
        <input type="text" class="form-control" id="claseCombustible" name="claseCombustible" placeholder="" value="{{ $to['hasClaseCombustible']['name']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="numeroMotor">Número motor</label>
        <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" placeholder="" value="{{$to['numero_motor']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="nivelServicio">Nivel del servicio</label>
        <input type="text" class="form-control" id="nivelServicio" name="nivelServicio" placeholder="" value="{{ $to['hasNivelServicio']['name']}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="capacidadPasajeros">Capacidad de pasajeros</label>
        <input type="text" class="form-control" id="capacidadPasajeros" name="capacidadPasajeros" placeholder="" value="{{$to['capacidad_pasajeros']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="capacidadToneladas">Capacidad de toneladas</label>
        <input type="text" class="form-control" id="capacidadToneladas" name="capacidadToneladas" placeholder="" value="{{$to['capacidad_toneladas']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="razonSocial">Razón social</label>
        <input type="text" class="form-control" id="razonSocial" name="razonSocial" placeholder="" value="{{ $to['hasEmpresaTransporte']['name']}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="numeroInterno">No. interno</label>
        <input type="text" class="form-control" id="numeroInterno" name="numeroInterno" placeholder="" value="{{$to['numero_interno']}}">
    </div>
    <div class="form-group col-md-4">
        <label for="radioOperacion">Radio de operación</label>
        <input type="text" class="form-control" id="radioOperacion" name="radioOperacion" placeholder="" value="{{ $to['hasRadioOperacion']['name']}}">
    </div>
</div>
{!! Form::close() !!}