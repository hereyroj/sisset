@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form>
    @csrf
    <input type="hidden" name="solicitud" value="{{$solicitud_id}}">
    <input type="hidden" name="turno" value="{{$turno_id}}">
    <input type="hidden" name="ventanilla" value="{{$ventanilla_id}}">
    <div class="form-group">
        <label for="usuario_asistencia" class="control-label">Asistencia: ¿El usuario se presentó en la
            ventanilla?</label>
        {{Form::select('usuario_asistencia', ['SI'=>'SI', 'NO'=>'NO', 'NOP' => 'NO PRESENTE'], old('usuario_asistencia'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="estado_tramite" class="control-label">Estado del trámite: ¿Cuál es el estado en el que termina el
            trámite?</label>
        {{Form::select('estado_tramite', ['1'=>'Finaliza', '2'=>'Pendiente carpeta', '3'=>'Pendiente pago', '4'=>'Pendiente documentación', '5'=>'Anulado'], old('estado_tramite'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label for="observacion_tramite" class="control-label">Observación:</label>
        <textarea class="form-control" name="observacion_tramite">{{old('observacion_tramite')}}</textarea>
    </div>
</form>