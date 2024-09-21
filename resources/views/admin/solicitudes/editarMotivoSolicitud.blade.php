<form>
    <input type="hidden" name="id" id="id" value="{{$motivo->id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" name="name" id="name" value="{{$motivo->name}}" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="control-label">Prioritario?</label>
        <select class="form-control" name="priorizar">
            <option value="1" @if($motivo->priorizar) selected @endif>Sí</option>
            <option value="0" @if(!$motivo->priorizar) selected @endif>No</option>
        </select>
    </div>
</form>