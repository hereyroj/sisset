<form>
    <input type="hidden" name="id" value="{{$tipo->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" required value="{{$tipo->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre">Cantidad días</label>
        <input type="numer" min="1" class="form-control" name="cant_dias" required value="{{$tipo->dia_cantidad}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre">Tipo día</label>
        <select class="form-control" name="tipo_dia">
            <option value="h" @if($tipo->dia_tipo == 'h') selected @endif>Hábil</option>
            <option value="c" @if($tipo->dia_tipo == 'c') selected @endif>Calendario</option>
        </select>
    </div>
</form>