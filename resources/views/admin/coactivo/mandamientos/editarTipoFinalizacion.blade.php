<form>
    <input type="hidden" name="id" value="{{$tipo->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$tipo->name}}">
    </div>
</form>