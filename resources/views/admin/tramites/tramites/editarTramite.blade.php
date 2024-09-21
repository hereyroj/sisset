<form>
    <input type="hidden" name="idTramite" id="idTramite" value="{{$tramite->id}}">
    <div class="form-group">
        <label for="nameTramite" class="control-label">Nombre</label>
        <input type="text" class="form-control" name="nameTramite" id="nameTramite" value="{{$tramite->name}}" required autofocus>
    </div>
    <div class="form-group">
        <label for="requiere_sustrato" class="control-label">Requiere sustrato</label>
        {{Form::select('requiere_sustrato', ['SI'=>'SI','NO'=>'NO'], $tramite->require_sustrato, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="tipo_sustrato" class="control-label">Tipo sustrato</label>
        {{Form::select('tipo_sustrato', $tiposSustratos, $tramite->tipo_sustrato_id, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="requiere_placa" class="control-label">Requiere placa</label>
        {{Form::select('requiere_placa', ['SI'=>'SI','NO'=>'NO'], $tramite->requiere_placa, ['class'=>'form-control'])}}
    </div
    <div class="form-group">
        <label for="solicita_carpeta" class="control-label">Solicita carpeta</label>
        {{Form::select('solicita_carpeta', ['SI'=>'SI','NO'=>'NO'], $tramite->solicita_carpeta, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="cupl" class="control-label">CUPL</label>
        <input id="cupl" type="text" class="form-control" name="cupl" required value="{{$tramite->cupl}}">
    </div>
    <div class="form-group">
        <label for="ministerio" class="control-label">Ministerio</label>
        <input id="ministerio" type="text" class="form-control" name="ministerio" required value="{{$tramite->ministerio}}">
    </div>
    <div class="form-group">
        <label for="entidad" class="control-label">Entidad</label>
        <input id="entidad" type="text" class="form-control" name="entidad" required value="{{$tramite->entidad}}">
    </div>
    <div class="form-group">
        <label for="sustrato" class="control-label">Sustrato</label>
        <input id="sustrato" type="text" class="form-control" name="sustrato" required value="{{$tramite->sustrato}}">
    </div>
</form>