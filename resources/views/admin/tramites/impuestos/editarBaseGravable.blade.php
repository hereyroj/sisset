<form>
    <input type="hidden" name="id" value="{{$baseGravable->id}}">
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        <input type="text" name="vigencia" required class="form-control" value="{{$baseGravable->vigencia}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_marca">Vehículo marca</label>
        {!! Form::select('vehiculo_marca', $vehiculosMarcas, $baseGravable->hasVehiculoLinea->vehiculo_marca_id, ['class'=>'form-control', 'required', 'id'=>'vehiculo_marca']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_linea">Vehículo línea</label>
        <select class="form-control" name="linea" id="linea" required></select>
    </div>
    <div class="form-group">
        <label class="control-label" for="clase">Vehículo clase</label>
        {!! Form::select('marca', $vehiculosClases, $baseGravable->vehiculo_clase_id, ['class'=>'form-control', 'required', 'id'=>'marca']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="carroceria">Vehículo carrocería</label>
        {!! Form::select('carroceria', $vehiculosCarrocerias, $baseGravable->vehiculo_carroceria_id, ['class'=>'form-control', 'required', 'id'=>'carroceria']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="modelo">Modelo</label>
        <input type="text" name="modelo" required class="form-control" value="{{$baseGravable->modelo}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="avaluo">Avalúo</label>
        <input type="text" name="avaluo" required class="form-control" value="{{$baseGravable->avaluo}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="grupo">Grupo</label>
        <input type="text" name="grupo" class="form-control" value="{{$baseGravable->grupo}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="tonelaje">Tonelaje</label>
        <input type="number" name="tonelaje" class="form-control" value="{{$baseGravable->tonelaje}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="pasajeros">Pasajeros</label>
        <input type="number" name="pasajeros" class="form-control" value="{{$baseGravable->pasajeros}}">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/editarBaseGravable.js')}}"></script>