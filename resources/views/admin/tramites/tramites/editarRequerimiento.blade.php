<form id="frmCrearRequerimiento">
    <input type="hidden" name="id" id="tramite" value="{{$requerimiento->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input class="form-control" type="text" name="nombre" value="{{$requerimiento->name}}" required>
    </div>
    <div class="form-group">
        <label class="control-label">Descripción</label>
        <textarea class="form-control" name="descripcion" required>{{$requerimiento->description}}</textarea>
    </div>
</form>