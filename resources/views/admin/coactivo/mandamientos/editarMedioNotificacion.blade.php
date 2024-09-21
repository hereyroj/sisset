<form>
    <input type="hidden" name="id" value="{{$medio->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$medio->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="requiere_guia">Requiere guía?</label>
        <select class="form-control" name="requiere_guia" required>
            <option value="2" @if($medio->requiere_guia == 2) selected @endif>No</option>
            <option value="1" @if($medio->requiere_guia == 1) selected @endif>Sí</option>
        </option>
    </div>
</form>