<form>
    <div class="form-group">
        <label class="form-label" for="claseVehiculo">Clase de vehículo</label>
        {!! Form::select('claseVehiculo', $clasesVehiculos, null, ['class' => 'form-control', 'id' => 'claseVehiculo']) !!}
    </div>
    <div class="form-group">
        <label class="form-label" for="servicioVehiculo">Servicio del vehículo</label>
        {!! Form::select('servicioVehiculo', $serviciosVehiculos, null, ['class' => 'form-control', 'id' => 'servicioVehiculo']) !!}
    </div>
    <div class="form-group">
        <label class="form-label" for="txtLetras">Letras</label>
        <input type="text" name="txtLetras" class="form-control" id="txtLetras">
    </div>
    <div class="form-group">
        <label class="form-label" for="rangoInicial">Rango inicial</label>
        <input type="number" name="rangoInicial" class="form-control" id="rangoInicial">
    </div>
    <div class="form-group">
        <label class="form-label" for="rangoFinal">Rango final</label>
        <input type="number" name="rangoFinal" class="form-control" id="rangoFinal">
    </div>
    <div class="form-group">
        <label class="form-label" for="letraTerminacion">Letra terminación (opcional)</label>
        <select id="letraTerminacion" name="letraTerminacion" class="form-control"> </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="estado">Estado</label>
        {!! Form::select('estadoCarpeta', $estadosCarpetas, null, ['class' => 'form-control', 'id'=>'estadoCarpeta']) !!}
    </div>
</form>
<script type="text/javascript" src="{{asset('js/archivo/creaMultiplesCarpetas.js')}}">