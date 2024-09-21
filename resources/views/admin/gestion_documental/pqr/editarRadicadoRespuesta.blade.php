<form>
    <input type="hidden" name="pqrId" value="{{$pqrId}}">
    <input type="hidden" name="radicadoAnterior" value="{{$radicado}}">
    <div class="form-group">
        <label class="control-label" for="radicadoNuevo">Radicado</label>
        <input type="text" class="form-control" id="radicadoNuevo" name="radicadoNuevo" value="{{$radicado}}" required>
    </div>
</form>