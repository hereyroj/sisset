<form>
    <input type="hidden" name="modalidad_id" value="{{$modalidad->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{$modalidad->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="require_empresa">Require empresa de mensajería?</label>
        {{Form::select('requiere_empresa', ['NO'=>'NO','SI'=>'SI',], $modalidad->requiere_empresa, ['class'=>'form-control'])}}
    </div>
</form>