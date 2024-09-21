<form>
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$placa->id}}">
    <div class="form-group">
        <label class="control-label">Clases de vehículos</label>
        @foreach($clases_vehiculos as $clase_vehiculo)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="clases_vehiculos[]" id="clases_vehiculos[]" value="{{$clase_vehiculo->id}}" @if($placa->hasVehiculoClase($clase_vehiculo->id)) checked @endif> {{$clase_vehiculo->name}}
                </label>
            </div>
        @endforeach
        <hr/>
    </div>
    <div class="form-group">
        <label class="control-label" for="placa">Placa</label>
        <input type="text" name="placa" id="placa" class="form-control" value="{{$placa->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_servicio_id">Servicio</label>
        {!! Form::select('vehiculo_servicio_id', $servicios_vehiculos, $placa->vehiculo_servicio_id, ['class'=>'form-control', 'id'=>'vehiculo_servicio_id']) !!}
    </div>
</form>