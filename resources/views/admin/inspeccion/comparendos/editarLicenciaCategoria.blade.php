<form>
    <input type="hidden" name="id" value="{{$categoria->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required value="{{$categoria->name}}">
    </div>
</form>