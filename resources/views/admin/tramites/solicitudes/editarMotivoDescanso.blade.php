<form>
    <input type="hidden" name="id" value="{{$motivo->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$motivo->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="minutos">Minutos</label>
        <input type="number" name="minutos" class="form-control" required min="1" value="{{$motivo->minutes}}">
    </div>
</form>