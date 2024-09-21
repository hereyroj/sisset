{!! Form::open(array('name' => 'frmNuevaTO', 'id' => 'frmNuevaTO')) !!}
<div class="row">
    <div class="form-group col-md-4">
        <label for="fechaVencimiento">Fecha de vencimiento</label>
        <input type="date" class="form-control datepicker" id="fechaVencimiento" name="fechaVencimiento" placeholder="Clic para establecer fecha">
    </div>
    <div class="form-group col-md-4">
        <label for="placa">Placa</label>
        <input type="text" class="form-control reset" id="placa" name="placa" placeholder="" value="{{ old('placa') }}" minlength="6" maxlength="6">
    </div>
    <div class="form-group col-md-4">
        <label for="tipoVehiculo">Tipo de vehículo</label>
        {!! Form::select('tipoVehiculo', $tipoVehiculo, null, ['class' => 'form-control  reset', 'disabled', 'id'=>'tipoVehiculo']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="tipoCarroceria">Tipo de carroceria</label>
        {!! Form::select('tipoCarroceria', $tipoCarroceria, null, ['class' => 'form-control', 'disabled', 'id'=>'tipoCarroceria']) !!}
    </div>
    <div class="form-group col-md-4">
        <label for="marcaVehiculo">Marca</label>
        {!! Form::select('marcaVehiculo', $marcaVehiculo, null, ['class' => 'form-control', 'disabled', 'id'=>'marcaVehiculo']) !!}
    </div>
    <div class="form-group col-md-4">
        <label for="modeloVehiculo">Modelo</label>
        <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" placeholder="" minlength="4" maxlength="4" disabled>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="claseCombustible">Clase de combustible</label>
        {!! Form::select('claseCombustible', $claseCombustible, null, ['class' => 'form-control', 'disabled', 'id'=>'claseCombustible']) !!}
    </div>
    <div class="form-group col-md-4">
        <label for="numeroMotor">Número motor</label>
        <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" disabled>
    </div>
    <div class="form-group col-md-4">
        <label for="nivelServicio">Nivel del servicio</label>
        {!! Form::select('nivelServicio', $nivelServicio, null, ['class' => 'form-control', 'id'=>'nivelServicio', 'disabled']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="capacidadPasajeros">Capacidad de pasajeros</label>
        <input type="text" class="form-control" id="capacidadPasajeros" name="capacidadPasajeros" disabled>
    </div>
    <div class="form-group col-md-4">
        <label for="capacidadToneladas">Capacidad de toneladas</label>
        <input type="text" class="form-control" id="capacidadToneladas" name="capacidadToneladas" disabled>
    </div>
    <div class="form-group col-md-4">
        <label for="razonSocial">Razón social</label>
        {!! Form::select('razonSocial', $empresasTransporte, null, ['class' => 'form-control', 'id'=>'razonSocial', 'disabled']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="numeroInterno">No. interno</label>
        <input type="text" class="form-control" id="numeroInterno" name="numeroInterno" disabled>
    </div>
    <div class="form-group col-md-4">
        <label for="radioOperacion">Radio de operación</label>
        {!! Form::select('radioOperacion', $radioOperacion, null, ['class' => 'form-control', 'id'=>'radioOperacion', 'disabled']) !!}
    </div>
    <div class="form-group col-md-4">
        <label for="duplicado">Duplicado</label>
        {!! Form::select('duplicado', ['0'=>'NO', '1'=>'SI'], null, ['class' => 'form-control']) !!}
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/tramites/to/nuevaTo.js')}}"></script>