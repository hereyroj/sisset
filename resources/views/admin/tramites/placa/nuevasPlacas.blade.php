<form>
    {{ csrf_field() }}
    <div class="form-group">
        <label class="control-label">Clases de vehículos</label>
        @foreach($clases_vehiculos as $clase_vehiculo)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="clases_vehiculos[]" id="clases_vehiculos[]" value="{{$clase_vehiculo->id}}"> {{$clase_vehiculo->name}}
                </label>
            </div>
        @endforeach
        <hr/>
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_servicio_id">Servicio</label>
        {{Form::select('vehiculo_servicio_id', $servicios_vehiculos, null,['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="letras_rango_inicial">Letras rango inicial</label>
        <input type="text" name="letras_rango_inicial" id="letras_rango_inicial" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="letras_rango_final">Letras rango final</label>
        <input type="text" name="letras_rango_final" id="letras_rango_final" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="numeros_rango_inicial">Números rango inicial</label>
        <input type="text" name="numeros_rango_inicial" id="numeros_rango_inicial" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="numeros_rango_final">Números rango final</label>
        <input type="text" name="numeros_rango_final" id="numeros_rango_final" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="requiere_letra_final">Requiere letra final?</label>
        <select name="requiere_letra_final" class="form-control">
            <option value="SI">SI</option>
            <option value="NO">NO</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label" for="letra_termiacion">Letra terminación</label>
        {!! Form::select('letra_terminacion', $letrasTerminacion, null, ['class'=>'form-control', 'id'=>'letra_terminacion']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="orden">Orden de elementos</label>
        <select name="orden" id="orden" class="form-control">
            <option value="L">Letras primero</option>
            <option value="N">Números primero</option>
        </select>
    </div>
</form>