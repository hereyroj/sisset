<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="placa">Placa</label>
        <input type="text" class="form-control" name="placa" id="placa">
    </div>
    <div class="form-group">
        <label class="control-label" for="documento_propietario">Número documento propietario</label>
        <input type="text" class="form-control" name="documento_propietario" id="documento_propietario">
    </div>
    <div class="form-group">
        <label class="control-label" for="clase_vehiculo">Clase</label>
        {!! Form::select('clase_vehiculo', $clasesVehiculo, null, ['class'=>'form-control', 'id'=>'clase_vehiculo']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="servicio_vehiculo">Servicio</label>
        {!! Form::select('servicio_vehiculo', $serviciosVehiculo, null, ['class'=>'form-control', 'id'=>'servicio_vehiculo']) !!}
    </div>  
    <div class="form-group">
        <label class="control-label" for="tramites">Tramites</label>
        @foreach($tramites as $tramite)
        <div class="checkbox">
            <label>
                <input type="checkbox" name="tramites[]" value="{{$tramite->id}}">
                {{$tramite->name}}
            </label>
        </div>
        @endforeach
    </div>  
</form>