<form>
    <input type="hidden" name="id" value="{{$origen->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$origen->name}}">
    </div>
</form>