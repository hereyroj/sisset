<form>
    <input type="hidden" name="id" value="{{$motivo->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required value="{{$motivo->name}}">
    </div>
</form>