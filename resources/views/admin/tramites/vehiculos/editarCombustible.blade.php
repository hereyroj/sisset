<form>
    <input type="hidden" name="idCombustible" value="{{$combustible->id}}">
    <div class="form-group">
        <label for="nombreCombustible" class="control-label">Nombre</label>
        <input type="text" id="nombreCombustible" name="nombreCombustible" class="form-control" value="{{$combustible->name}}">
    </div>
</form>