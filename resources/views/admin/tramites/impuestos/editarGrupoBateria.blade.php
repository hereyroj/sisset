<form>
    <input type="hidden" name="id" value="{{$bateriaGrupo->id}}">
    <div class="form-grupo">
        <label class="control-label" for="">Vigencia</label>
        <input type="text" class="form-control" name="vigencia" required value="{{$bateriaGrupo->vigencia}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$bateriaGrupo->name}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="bateria_tipo">Tipo batería</label> 
        {!! Form::select('bateria_tipo', $tiposBateria, $bateriaGrupo->vehiculo_bateria_tipo_id, ['class'=>'form-control', 'required', 'id'=>'bateria_tipo']) !!}
    </div>
    <div class="form-grupo">
        <label class="control-label" for="desde">Desde (capacidad)</label>
        <input type="number" class="form-control" name="desde" required value="{{$bateriaGrupo->desde}}">
    </div>
    <div class="form-grupo">
        <label class="control-label" for="hasta">Hasta (capacidad)</label>
        <input type="number" class="form-control" name="hasta" required value="{{$bateriaGrupo->hasta}}">
    </div>
</form>