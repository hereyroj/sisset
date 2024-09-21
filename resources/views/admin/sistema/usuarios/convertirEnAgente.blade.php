<form>
    <input type="hidden" name="usuarioId" value="{{$id}}">
    <div class="form-group">
            <label class="control-label" for="placa">Entidad</label>
            {{Form::select('entidad', $entidades, null, ['class'=>'form-control', 'id'=>'entidad', 'required'])}}
        </div>
    <div class="form-group">
        <label class="control-label" for="placa">Placa</label>
        <input type="text" class="form-control" name="placa" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_vinculacion">Fecha de vinculación</label>
        <input type="text" class="form-control datepicker" name="fecha_vinculacion" required>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/usuarios/convertirEnAgente.js')}}"></script>