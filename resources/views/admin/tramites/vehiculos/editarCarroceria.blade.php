<form>
    <input type="hidden" name="idCarroceria" value="{{$carroceria->id}}">
    <div class="form-group">
        <label for="nombreCarroceria" class="control-label">Nombre</label>
        <input type="text" id="nombreCarroceria" name="nombreCarroceria" class="form-control" value="{{$carroceria->name}}">
    </div>
</form>