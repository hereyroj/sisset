<form>
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Finaliza el servicio?</label>
        {!! Form::select('finaliza_servicio', ['SI'=>'SI','NO'=>'NO'], null, ['class'=>'form-control', 'required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Requiere observación?</label>
        {!! Form::select('requiere_observacion', ['SI'=>'SI','NO'=>'NO'], null, ['class'=>'form-control', 'required']) !!}
    </div>
</form>