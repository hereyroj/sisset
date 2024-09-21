<form>
    <input type="hidden" name="id" value="{{$motivo->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" class="form-control" name="name" required value="{{$motivo->name}}">
    </div>
</form>