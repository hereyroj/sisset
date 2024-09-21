<form>
    <input type="hidden" name="id" value="{{$estado->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$estado->name}}">
    </div>
    <div class="form-group">
        <label class="control-label">Publicaciones visibles?</label>
        <select class="form-control" name="visibilidad" required>
            <option value="1" @if($estado->show_post == true) selected @endif>Sí</option>
            <option value="0" @if($estado->show_post == false) selected @endif>No</option>
        </select>
    </div>
</form>