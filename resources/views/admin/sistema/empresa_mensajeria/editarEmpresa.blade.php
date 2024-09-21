<form>
    <input type="hidden" name="id" value="{{$empresa->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$empresa->name}}">
    </div>
</form>