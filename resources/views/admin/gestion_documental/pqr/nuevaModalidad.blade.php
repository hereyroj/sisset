<form>
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="require_empresa">Require empresa de mensajería?</label>
        {{Form::select('requiere_empresa', ['NO'=>'NO','SI'=>'SI',], null, ['class'=>'form-control'])}}
    </div>
</form>