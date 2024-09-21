<form>
    <input type="hidden" name="idMarca" value="{{$marca->id}}">
    <div class="form-group">
        <label for="nombreMarca" class="control-label">Nombre</label>
        <input type="text" id="nombreMarca" name="nombreMarca" class="form-control" value="{{$marca->name}}">
    </div>
</form>