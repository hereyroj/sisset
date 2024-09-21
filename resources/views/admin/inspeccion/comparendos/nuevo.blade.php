@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form enctype="multipart/form-data" style="padding-bottom: 5px;">
    <h4>Información del comparendo</h4>
    <div class="form-group">
        <label class="control-label" for="comparendo_numero">Número</label>
        <input type="text" class="form-control" name="comparendo_numero" value="{{old('comparendo_numero')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_fecha">Fecha</label>
        <input type="text" class="form-control datepicker" name="comparendo_fecha" value="{{old('comparendo_fecha')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_hora">Hora</label>
        <input type="text" class="form-control timepicker" name="comparendo_hora" value="{{old('comparendo_hora')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_tipo">Tipo</label>
        {{Form::select('comparendo_tipo', $tiposComparendos, old('comparendo_tipo'), ['class'=>'form-control', 'id'=>'comparendo_tipo'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_infraccion">Infracción</label>
        {{Form::select('comparendo_infraccion', [], old('comparendo_infraccion'), ['class'=>'form-control', 'id'=>'comparendo_infraccion'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="descripcion_infraccion">Descripción Infracción</label>
        <textarea name="descripcion_infraccion" id="descripcion_infraccion" class="form-control" disabled>{{old('descripcion_infraccion')}}</textarea>
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_barrio">Barrio/Vereda</label>
        <input type="text" class="form-control" name="comparendo_barrio" value="{{old('comparendo_barrio')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_direccion">Dirección</label>
        <input type="text" class="form-control" name="comparendo_direccion" value="{{old('comparendo_direccion')}}">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="comparendo_fuga" name="comparendo_fuga">
        <label class="form-check-label" for="defaultCheck1">
            Fuga
        </label>
    </div>
    <hr>
    <h4>Información de alcoholemia</h4>
    <div class="form-group">
        <label class="control-label" for="alcoholemia_grado">Grado</label>
        <input type="text" class="form-control" name="alcoholemia_grado" value="{{old('alcoholemia_grado')}}">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="alcoholemia_negacion" name="alcoholemia_negacion">
        <label class="form-check-label" for="defaultCheck1">
            Se negó a realizar
        </label>
    </div>
    <hr>
    <h4>Información del vehículo</h4>
    <div class="form-group">
        <label class="control-label" for="vehiculo_placa">Placa</label>
        <input type="text" class="form-control" name="vehiculo_placa" value="{{old('vehiculo_placa')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_licencia_numero">Licencia tránsito n°</label>
        <input type="text" class="form-control" name="vehiculo_licencia_numero"  value="{{old('vehiculo_licencia_numero')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_licenciaTransitoOtto">Licencia Organismo Tránsito</label>
        <input type="text" class="form-control" name="vehiculo_licenciaTransitoOtto" value="{{old('vehiculo_licenciaTransitoOtto')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_servicio">Servicio</label>
        {{Form::select('vehiculo_servicio', $servicios, old('vehiculo_servicio'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_clase">Clase</label>
        {{Form::select('vehiculo_clase', $clases, old('vehiculo_clase'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_razon_social">Empresa transportadora</label>
        {{Form::select('vehiculo_razon_social', $empresas, old('vehiculo_razon_social'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_tarjeta_operacion">Tarjeta de operación n°</label>
        <input type="text" class="form-control" name="vehiculo_tarjeta_operacion" value="{{old('vehiculo_tarjeta_operacion')}}">
    </div>    
    <div class="form-group">
        <label class="control-label" for="vehiculo_radio_operacion">Radio de operación</label>
        {{Form::select('vehiculo_radio_operacion', $radiosOperacion, old('vehiculo_radio_operacion'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_nivelServicio">Nivel servicio</label>
        {{Form::select('vehiculo_nivelServicio', $nivelesServicios, old('vehiculo_nivelServicio'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="propietario_nombre">Propietario nombre</label>
        <input type="text" class="form-control" name="propietario_nombre" value="{{old('propietario_nombre')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_propTipoDocumento">Propietario tipo documento</label>
        {{Form::select('vehiculo_propTipoDocumento', $tiposDocumentos, old('vehiculo_propTipoDocumento'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="vehiculo_propNumeroDocumento">Propietario número documento</label>
        <input type="text" class="form-control" name="vehiculo_propNumeroDocumento" value="{{old('vehiculo_propNumeroDocumento')}}">
    </div>
    <hr>
    <h4>Información del infractor</h4>
    <div class="form-group">
        <label class="control-label" for="conductor_tipo">Tipo</label>
        {{Form::select('conductor_tipo', $infractorTipos, old('conductor_tipo'), ['class'=>'form-control'])}}
    </div> 
    <div class="form-group">
        <label class="control-label" for="conductor_documento">Tipo documento</label>
        {{Form::select('conductor_documento', $tiposDocumentos, old('conductor_documento'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_numero_documento">Número de documento</label>
        <input type="text" class="form-control" name="conductor_numero_documento" value="{{old('conductor_numero_documento')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_nombre">Nombre</label>
        <input type="text" class="form-control" name="conductor_nombre" value="{{old('conductor_nombre')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_dpto">Departamento</label>
        {{Form::select('conductor_dpto', $departamentos, old('conductor_dpto'), ['class'=>'form-control', 'id'=>'conductor_dpto'])}}
    </div>  
    <div class="form-group">
        <label class="control-label" for="conductor_ciudad">Ciudad</label>
        {{Form::select('conductor_ciudad', [], null, ['class'=>'form-control', 'id' => 'conductor_ciudad'])}}
    </div>    
    <div class="form-group">
        <label class="control-label" for="conductor_direccion">Dirección</label>
        <input type="text" class="form-control" name="conductor_direccion" value="{{old('conductor_direccion')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_direccionElectronica">Dirección electrónica</label>
        <input type="text" class="form-control" name="conductor_direccionElectronica" value="{{old('conductor_direccionElectronica')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_telefono">Teléfono</label>
        <input type="text" class="form-control" name="conductor_telefono" value="{{old('conductor_telefono')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_licenciaCategoria">Licencia categoría</label>
        {{Form::select('conductor_licenciaCategoria', $licenciaCategorias, old('conductor_licenciaCategoria'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="conductor_licencia">Licencia número</label>
        <input type="text" class="form-control" name="conductor_licencia" value="{{old('conductor_licencia')}}">
    </div>    
    <div class="form-group">
        <label class="control-label" for="conductor_licencia_vencimiento">Licencia fecha vencimiento</label>
        <input type="text" class="form-control datepicker" name="conductor_licencia_vencimiento" value="{{old('conductor_licencia_vencimiento')}}">
    </div>    
    <hr>
    <h4>Información de inmovilización</h4>
    <div class="form-group">
        <label class="control-label" for="comparendo_tipoInmovilizacion">Tipo de inmovilización</label>
        {{Form::select('comparendo_tipoInmovilizacion', $tiposInmovilizaciones, old('comparendo_tipoInmovilizacion'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_patioNombre">Patio nombre</label>
        <input type="text" class="form-control" name="comparendo_patioNombre"  value="{{old('comparendo_patioNombre')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_patioDireccion">Patio dirección</label>
        <input type="text" class="form-control" name="comparendo_patioDireccion" value="{{old('comparendo_patioDireccion')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_gruaNumero">Grúa número</label>
        <input type="text" class="form-control" name="comparendo_gruaNumero" value="{{old('comparendo_gruaNumero')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_gruaPlaca">Grúa placa</label>
        <input type="text" class="form-control" name="comparendo_gruaPlaca" value="{{old('comparendo_gruaPlaca')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_inmovilizacionConsecutivo">Consecutivo inmovilización</label>
        <input type="text" class="form-control" name="comparendo_inmovilizacionConsecutivo" value="{{old('comparendo_inmovilizacionConsecutivo')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="comparendo_observacionInmovilizacionn">Observación</label>
        <textarea class="form-control" name="comparendo_observacionInmovilizacionn">{{old('comparendo_observacionInmovilizacionn')}}</textarea>
    </div>
    <hr>
    <h4>Información del agente</h4>
    <div class="form-group">
        <label class="control-label" for="agente">Agente</label>
        {{Form::select('agente', $agentes, old('agente'), ['class'=>'form-control'])}}
    </div>
    <hr>
    <h4>Observación del agente</h4>
    <div class="form-group">
        <label class="control-label" for="comparendo_observacion">Observación:</label>
        <textarea name="comparendo_observacion" class="form-control">{{old('comparendo_observacion')}}</textarea>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/comparendos/nuevo.js')}}"></script>