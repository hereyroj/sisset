<form>
    <input type="hidden" name="id_estado" id="id_estado" value="{{$estado->id}}">
    <div class="form-group">
        <label class="control-label" for="name_estado">Nombre</label>
        <input type="text" name="name_estado" id="name_estado" value="{{$estado->name}}" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="estado_carpeta">Disponibilidad de la carpeta</label>
        {!! Form::select('estado_carpeta', ['SI'=>'SI','NO'=>'NO'], $estado->estado_carpeta, ['class' => 'form-control', 'id'=>'estado_carpeta']) !!}
    </div>
</form>
