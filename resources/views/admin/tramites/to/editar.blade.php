{!! Form::open(array('id'=>'frmEditarTO', 'name' => 'frmEditarTO')) !!}
<input type="hidden" name="id" value="{{$to['id']}}">
<div class="row">
    <div class="form-group col-md-4">
        <label for="fechaVencimiento">Fecha de vencimiento</label>
        <input type="date" class="form-control datepicker picker-editar" id="fechaVencimiento" name="fechaVencimiento" placeholder="Clic para establecer fecha" value="{{str_replace('-', ',', $to['fecha_vencimiento'])}}">
    </div>
    <div class="form-group col-md-4">
        <label for="placa">Placa</label>
        <input type="text" class="form-control" id="placa" name="placa" value="{{ $to['placa']}}" disabled>
        @if($to->vehiculo->placa != $to->placa)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->placa}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="tipoVehiculo">Tipo de vehículo</label>
        {!! Form::select('tipoVehiculo', $tipoVehiculo, $to['hasTipoVehiculo']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->vehiculo_clase_id != $to->tipo_vehiculo_id)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->hasTipoVehiculo->name}}</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="tipoCarroceria">Tipo de carroceria</label>
        {!! Form::select('tipoCarroceria', $tipoCarroceria, $to['hasTipoCarroceria']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->vehiculo_carroceria_id != $to->tipo_carroceria_id)
            <span id="helpBlock" class="help-block">Actual {{$to->vehiculo->hasTipoCarroceria->name}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="marcaVehiculo">Marca</label>
        {!! Form::select('marcaVehiculo', $marcaVehiculo, $to['hasMarca']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->vehiculo_marca_id != $to->marca_vehiculo_id)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->hasMarca->name}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="modeloVehiculo">Modelo</label>
        <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" disabled
               value="{{$to['modelo']}}">
        @if($to->vehiculo->modelo != $to->modelo)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->modelo}}</span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="claseCombustible">Clase de combustible</label>
        {!! Form::select('claseCombustible', $claseCombustible, $to['hasClaseCombustible']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->vehiculo_combustible_id != $to->clase_combustible_id)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->hasClaseCombustible->name}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="numeroMotor">Número motor</label>
        <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" disabled value="{{$to['numero_motor']}}">
        @if($to->vehiculo->numero_motor != $to->numero_motor)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->numero_motor}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="nivelServicio">Nivel del servicio</label>
        {!! Form::select('nivelServicio', $nivelServicio, $to['hasNivelServicio']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->hasEmpresaActiva()->pivot->nivel_servicio_id != $to->nivel_servicio_id)
            <span id="helpBlock" class="help-block">Actual: <?php echo \DB::table('vehiculo_nivel_servicio')->where('id', $to->vehiculo->hasEmpresaActiva()->pivot->nivel_servicio_id)->select('name')->first()->name; ?></span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="capacidadPasajeros">Capacidad de pasajeros</label>
        <input type="text" class="form-control" id="capacidadPasajeros" name="capacidadPasajeros" disabled value="{{$to['capacidad_pasajeros']}}">
        @if($to->vehiculo->capacidad_pasajeros != $to->capacidad_pasajeros)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->capacidad_pasajeros}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="capacidadToneladas">Capacidad de toneladas</label>
        <input type="text" class="form-control" id="capacidadToneladas" name="capacidadToneladas" disabled value="{{$to['capacidad_toneladas']}}">
        @if($to->vehiculo->capacidad_toneladas != $to->capacidad_toneladas)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->capacidad_toneladas}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="razonSocial">Razón social</label>
        {!! Form::select('razonSocial', $empresasTransporte, $to['hasEmpresaTransporte']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->hasEmpresaActiva()->pivot->empresa_transporte_id != $to->empresa_transporte_id)
            <span id="helpBlock" class="help-block">Actual: <?php echo \DB::table('empresa_transporte')->where('id', $to->vehiculo->hasEmpresaActiva()->pivot->empresa_transporte_id)->select('name')->first()->name; ?></span>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="numeroInterno">No. interno</label>
        <input type="text" class="form-control" id="numeroInterno" name="numeroInterno" disabled value="{{$to['numero_interno']}}">
        @if($to->vehiculo->hasEmpresaActiva()->pivot->numero_interno != $to->numero_interno)
            <span id="helpBlock" class="help-block">Actual: {{$to->vehiculo->hasEmpresaActiva()->pivot->numero_interno}}</span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="radioOperacion">Radio de operación</label>
        {!! Form::select('radioOperacion', $radioOperacion, $to['hasRadioOperacion']['id'], ['class' => 'form-control', 'disabled']) !!}
        @if($to->vehiculo->hasEmpresaActiva()->pivot->radio_operacion_id != $to->radio_operacion_id)
            <span id="helpBlock" class="help-block">Actual: <?php echo \DB::table('vehiculo_radio_operacion')->where('id', $to->vehiculo->hasEmpresaActiva()->pivot->radio_operacion_id)->select('name')->first()->name; ?></span>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label for="duplicado">Duplicado</label>
        {!! Form::select('duplicado', ['0'=>'NO', '1'=>'SI'], $to['duplicado'], ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label for="actualizaVehiculo">Actualizar la información del vehículo?</label>
        {!! Form::select('actualizaVehiculo', ['NO'=>'NO', 'SI'=>'SI'], $to['duplicado'], ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-4">
        <label for="actualizaVinculacion">Actualizar la información de vinculación?</label>
        {!! Form::select('actualizaVinculacion', ['NO'=>'NO', 'SI'=>'SI'], $to['duplicado'], ['class' => 'form-control']) !!}
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/tramites/to/editar.js')}}"></script>