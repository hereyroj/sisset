<form>
    <div class="form-group">
        <label for="name" class="control-label">Nombre</label>
        <input id="name" type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
        <label for="requiere_sustrato" class="control-label">Requiere sustrato</label>
        {{Form::select('requiere_sustrato', ['SI'=>'SI','NO'=>'NO'], null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="tipo_sustrato" class="control-label">Tipo sustrato</label>
        {{Form::select('tipo_sustrato', $tiposSustratos, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="requiere_placa" class="control-label">Requiere placa</label>
        {{Form::select('requiere_placa', ['SI'=>'SI','NO'=>'NO'], null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="solicita_carpeta" class="control-label">Solicita carpeta</label>
        {{Form::select('solicita_carpeta', ['SI'=>'SI','NO'=>'NO'], null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="cupl" class="control-label">CUPL</label>
        <input id="cupl" type="text" class="form-control" name="cupl" placeholder="$" required>
    </div>
    <div class="form-group">
        <label for="ministerio" class="control-label">Ministerio</label>
        <input id="ministerio" type="text" class="form-control" name="ministerio" placeholder="$" required>
    </div>
    <div class="form-group">
        <label for="entidad" class="control-label">Entidad</label>
        <input id="entidad" type="text" class="form-control" name="entidad" placeholder="$" required>
    </div>
    <div class="form-group">
        <label for="sustrato" class="control-label">Sustrato</label>
        <input id="sustrato" type="text" class="form-control" name="sustrato" placeholder="$" required>
    </div>
</form>