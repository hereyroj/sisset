<form>
    <input type="hidden" name="id" value="{{$entidad->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required value="{{$entidad->name}}">
    </div>
</form>