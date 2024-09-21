<form>
    <input type="hidden" name="id" value="{{$cilindrajeGrupo->id}}">
    <div class="form-grupo">
        <label class="control-label" for="">Vigencia</label>
        <input type="text" class="form-control" name="vigencia" required value="{{$cilindrajeGrupo->vigencia}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$cilindrajeGrupo->name}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="clase_vehiculo">Tipo batería</label> 
        {!! Form::select('clase_vehiculo', $clasesVehiculo, $cilindrajeGrupo->vehiculo_clase_id, ['class'=>'form-control', 'required', 'id'=>'clase_vehiculo']) !!}
    </div>
    <div class="form-grupo">
        <label class="control-label" for="desde">Desde (capacidad)</label>
        <input type="number" class="form-control" name="desde" required value="{{$cilindrajeGrupo->desde}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="hasta">Hasta (capacidad)</label>
        <input type="number" class="form-control" name="hasta" required value="{{$cilindrajeGrupo->hasta}}">
    </div>
</form>