<form>
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        <input type="text" name="vigencia" required class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_marca">Vehículo marca</label>
        {!! Form::select('vehiculo_marca', $vehiculosMarcas, null, ['class'=>'form-control', 'required', 'id'=>'vehiculo_marca']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="linea">Vehículo línea</label>
        <select class="form-control" name="linea" id="linea" required></select>
    </div>
    <div class="form-group">
        <label class="control-label" for="clase">Vehículo clase</label>
        {!! Form::select('marca', $vehiculosClases, null, ['class'=>'form-control', 'required', 'id'=>'marca']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="carroceria">Vehículo carrocería</label>
        {!! Form::select('carroceria', $vehiculosCarrocerias, null, ['class'=>'form-control', 'required', 'id'=>'carroceria']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="modelo">Modelo</label>
        <input type="text" name="modelo" required class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="avaluo">Avalúo</label>
        <input type="text" name="avaluo" required class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="grupo">Grupo</label>
        <input type="text" name="grupo" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="tonelaje">Tonelaje</label>
        <input type="number" name="tonelaje" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="pasajeros">Pasajeros</label>
        <input type="number" name="pasajeros" class="form-control">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/nuevaBaseGravable.js')}}"></script>