<form>
    <input type="hidden" name="id" value="{{$claseGrupo->id}}">
    <div class="form-grupo">
        <label class="control-label" for="">Vigencia</label>
        <input type="text" class="form-control" name="vigencia" required value="{{$claseGrupo->vigencia}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$claseGrupo->name}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="clase_vehiculo">Tipo batería</label>
        {!! Form::select('clase_vehiculo', $clasesVehiculo, $claseGrupo->vehiculo_clase_id, ['class'=>'form-control', 'required', 'id'=>'clase_vehiculo']) !!}
    </div>
    <div class="form-grupo">
        <label class="control-label" for="clase_vehiculo">Marca vehículo</label>
        {!! Form::select('marca_vehiculo', $marcasVehiculo, $claseGrupo->vehiculo_marca_id, ['class'=>'form-control', 'required', 'id'=>'marca_vehiculo']) !!}
    </div>
</form>  