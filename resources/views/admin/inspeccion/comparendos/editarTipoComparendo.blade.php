<form>
    <input type="hidden" name="id" value="{{$tipoComparendo->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$tipoComparendo->name}}">
    </div>
</form>