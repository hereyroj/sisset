<form>
    <input type="hidden" name="id" id="id" value="{{$motivo->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" name="name" id="name" value="{{$motivo->name}}" class="form-control" required>
    </div>
</form>