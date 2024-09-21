<form>
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre">Cantidad días</label>
        <input type="numer" min="1" class="form-control" name="cant_dias" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre">Tipo día</label>
        <select class="form-control" name="tipo_dia">
            <option value="h">Hábil</option>
            <option value="c">Calendario</option>
        </select>
    </div>
</form>