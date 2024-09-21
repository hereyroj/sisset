<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="nuevaFecha">Nueva fecha</label>
        <input type="text" class="form-control datepicker" name="nuevaFecha">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/cambiarFechaLimite.js')}}"></script>