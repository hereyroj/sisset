<form>
    <input type="hidden" name="id" value="{{$tipoNormativa->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$tipoNormativa->name}}">
    </div>
</form>