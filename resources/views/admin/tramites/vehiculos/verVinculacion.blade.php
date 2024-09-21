<form>
    <input type="hidden" name="vehiculo_id" value="{{$vehiculo->id}}">
    <div class="form-group">
        <label for="razonSocial">Razón social</label>
        {!! Form::select('empresaTransporte', $empresasTransporte, $vehiculo->hasEmpresaActiva()->pivot->empresa_transporte_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="numeroInterno">No. interno</label>
        <input type="text" class="form-control" name="numeroInterno" value="{{$vehiculo->hasEmpresaActiva()->pivot->numero_interno}}">
    </div>
    <div class="form-group">
        <label for="nivelServicio">Nivel del servicio</label>
        {!! Form::select('nivelServicio', $nivelServicio, $vehiculo->hasEmpresaActiva()->pivot->nivel_servicio_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="radioOperacion">Radio de operación</label>
        {!! Form::select('radioOperacion', $radioOperacion, $vehiculo->hasEmpresaActiva()->pivot->radio_operacion_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="fechaVinculacion">Fecha de vinculación</label>
        <input type="date" class="form-control datepicker" id="fechaVinculacion" name="fechaVinculacion" value="{{$vehiculo->hasEmpresaActiva()->pivot->fecha_vinculacion}}">
    </div>
    <div class="form-group">
        <label for="fechaRetiro">Fecha de retiro</label>
        <input type="date" class="form-control datepicker" id="fechaRetiro" name="fechaRetiro">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/verVinculacion.js')}}"></script>