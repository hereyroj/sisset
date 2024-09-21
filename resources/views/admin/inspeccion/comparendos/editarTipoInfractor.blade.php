<form>
    <input type="hidden" name="id" value="{{$tipoInfractor->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required value="{{$tipoInfractor->name}}">
    </div>
</form>