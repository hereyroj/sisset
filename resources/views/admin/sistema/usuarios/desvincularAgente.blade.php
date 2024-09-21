<form>
    <input type="hidden" name="usuarioId" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="fecha_desvinculacion">Fecha de desvinculación</label>
        <input type="text" class="form-control datepicker" name="fecha_desvinculacion">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/usuarios/desvincularAgente.js')}}"></script>