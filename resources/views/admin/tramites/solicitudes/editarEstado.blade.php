<form>
    <input type="hidden" name="id" value="{{$estado->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$estado->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Finaliza el servicio?</label>
        {!! Form::select('finaliza_servicio', ['SI'=>'SI','NO'=>'NO'], $estado->finaliza_servicio, ['class'=>'form-control', 'required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Requiere observación?</label>
        {!! Form::select('requiere_observacion', ['SI'=>'SI','NO'=>'NO'], $estado->requiere_observacion, ['class'=>'form-control', 'required']) !!}
    </div>
</form>