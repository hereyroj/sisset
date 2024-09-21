<form>
    <input type="hidden" name="vehiculo_id" value="{{$vehiculo_id}}">
    <div class="form-group">
        <label for="razonSocial">Razón social</label>
        {!! Form::select('empresaTransporte', $empresasTransporte, null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="numeroInterno">No. interno</label>
        <input type="text" class="form-control" id="numeroInterno" name="numeroInterno" placeholder="">
    </div>
    <div class="form-group">
        <label for="nivelServicio">Nivel del servicio</label>
        {!! Form::select('nivelServicio', $nivelServicio, null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="radioOperacion">Radio de operación</label>
        {!! Form::select('radioOperacion', $radioOperacion, null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="fechaVinculacion">Fecha de vinculación</label>
        <input type="date" class="form-control datepicker" id="fechaVinculacion" name="fechaVinculacion" placeholder="Clic para establecer fecha">
    </div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="desvincularActual" name="desvincularActual">
        <label class="custom-control-label" for="desvincularActual">Desvincular vehículo actual</label>
    </div>
    <div class="form-group">
        <label for="fechaVinculacion">Fecha de desvinculación</label>
        <input type="date" class="form-control datepicker" id="fechaDesvinculacion" name="fechaDesvinculacion" placeholder="Clic para establecer fecha" disabled>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/vehiculos/vincularEmpresa.js')}}"></script>