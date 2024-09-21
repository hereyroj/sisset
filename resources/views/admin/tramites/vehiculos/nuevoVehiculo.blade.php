<form>
    <h4>Información del vehículo</h4>
    <div class="form-group">
        <label for="placa">Placa</label>
        <input type="text" class="form-control reset" id="placa" name="placa" minlength="6" maxlength="6" required>
    </div>
    <div class="form-group">
        <label for="tipoVehiculo">Tipo de vehículo</label>
        {!! Form::select('tipoVehiculo', $clases, null, ['class' => 'form-control  reset', 'id'=>'tipoVehiculo', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="tipoCarroceria">Tipo de carroceria</label>
        {!! Form::select('tipoCarroceria', $carrocerias, null, ['class' => 'form-control', 'id'=>'tipoCarroceria', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="marcaVehiculo">Marca</label>
        {!! Form::select('marcaVehiculo', $marcas, null, ['class' => 'form-control', 'id'=>'marcaVehiculo', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="lineaVehiculo">Línea</label>
        <select class="form-control" name="lineaVehiculo" id="lineaVehiculo" required></select>
    </div>
    <div class="form-group">
        <label for="modeloVehiculo">Modelo</label>
        <input type="text" class="form-control" id="modeloVehiculo" name="modeloVehiculo" placeholder="" minlength="4"  maxlength="4" required>
    </div>
    <div class="form-group">
        <label for="colorVehiculo">Color</label>
        <input type="text" class="form-control" id="colorVehiculo" name="colorVehiculo" required>
    </div>
    <div class="form-group">
        <label for="puertasVehiculo">Puertas</label>
        <input type="text" class="form-control" id="puertasVehiculo" name="puertasVehiculo" required>
    </div>
    <div class="form-group">
        <label for="claseCombustible">Clase de combustible</label>
        {!! Form::select('claseCombustible', $combustibles, null, ['class' => 'form-control', 'id'=>'claseCombustible', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="numeroMotor">Número motor</label>
        <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" placeholder="" required>
    </div>
    <div class="form-group">
        <label for="numeroChasis">Número chasis</label>
        <input type="text" class="form-control" id="numeroChasis" name="numeroChasis" placeholder="" required>
    </div>
    <div class="form-group">
        <label for="capacidadPasajeros">Capacidad de pasajeros</label>
        <input type="text" class="form-control" id="capacidadPasajeros" name="capacidadPasajeros" placeholder="" required>
    </div>
    <div class="form-group">
        <label for="capacidadToneladas">Capacidad de toneladas</label>
        <input type="text" class="form-control" id="capacidadToneladas" name="capacidadToneladas" placeholder="" required>
    </div>
    <div class="form-group">
        <label for="tipoBateria">Tipo de batería (Para eléctricos)</label>
        {!! Form::select('tipoBateria', $tiposBaterias, null, ['class' => 'form-control', 'id'=>'tipoBateria']) !!}
    </div>
    <div class="form-group">
        <label for="bateriaCapacidad">Capacidad de la batería (Para eléctricos)</label>
        <input type="text" class="form-control" id="bateriaCapacidad" name="bateriaCapacidad">
    </div>
    <h4>Información del propietario</h4>
    <div class="form-group">
        <label class="control-label" for="tipo_documento">Tipo documento</label>
        {!! Form::select('tipo_documento', $tiposDocumentosIdentidad, old('tipo_documento'), ['class' => 'form-control', 'id'=>'tipo_documento', 'required']) !!}
    </div>
    <div class="form-group">
        <label for="numero_documento" class="control-label">Numero</label>
        <input type="text" id="numero_documento" name="numero_documento" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="nombre" class="control-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="telefono" class="control-label">Teléfono</label>
        <input type="text" id="telefono" name="telefono" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="correo" class="control-label">Correo electrónico</label>
        <input type="email" id="correo" name="correo" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="departamento">Departamento residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('departamento', $departamentos, old('departamento'), ['class' => 'form-control', 'id' => 'departamento', 'required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="municipio">Municipio residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <select name="municipio" id="municipio" class="form-control" required></select>
    </div>
    <div class="form-group">
        <label class="control-label" for="direccion">Dirección residencia<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion')}}" required>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/nuevoVehiculo.js')}}"></script>