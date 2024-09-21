@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (old('comparendo_id') != null)
<form enctype="multipart/form-data" style="padding-bottom: 5px;">
    <input type="hidden" name="comparendo_id" value="{{old('comparendo_id')}}">
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
        <label class="control-label" for="comparendo_valor">Valor</label>
        <input type="text" class="form-control" name="comparendo_valor" value="{{old('comparendo_valor')}}">
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
        <input class="form-check-input" type="checkbox" value="" id="comparendo_fuga" name="comparendo_fuga" @if(old('comparendo_fuga')) checked @endif>
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
        <input class="form-check-input" type="checkbox" value="" id="alcoholemia_negacion" name="alcoholemia_negacion" @if(old('alcoholemia_negacion')) checked @endif>
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
        <label class="control-label" for="vehiculo_licenciaTransitoOtto">Licencia Organismo Tránsito</label>
        <input type="text" class="form-control" name="vehiculo_licenciaTransitoOtto" value="{{old('vehiculo_licenciaTransitoOtto')}}">
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
@else
    <form  enctype="multipart/form-data">
        <input type="hidden" name="comparendo_id" value="{{$comparendo->id}}">
        <h4>Información del comparendo</h4>
        <div class="form-group">
            <label class="control-label" for="comparendo_numero">Número</label>
            <input type="text" class="form-control" name="comparendo_numero" value="{{$comparendo->numero}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_fecha">Fecha</label>
            <?php
            $fecha_realizacion = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comparendo->fecha_realizacion)
            ?>
            <input type="text" class="form-control datepicker" name="comparendo_fecha" id="comparendo_fecha" value="{{$fecha_realizacion->toDateString()}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_hora">Hora</label>
            <input type="text" class="form-control timepicker" name="comparendo_hora" id="comparendo_hora" value="{{$fecha_realizacion->format('H:i')}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_tipo">Tipo</label>
            {{Form::select('comparendo_tipo', $tiposComparendos, $comparendo->comparendo_tipo_id, ['class'=>'form-control', 'id'=>'comparendo_tipo'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_infraccion">Infracción</label>
            {{Form::select('comparendo_infraccion', $infracciones, $comparendo->comparendo_infraccion_id, ['class'=>'form-control', 'id'=>'comparendo_infraccion'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="descripcion_infraccion">Descripción Infracción</label>
            <textarea name="descripcion_infraccion" id="descripcion_infraccion" class="form-control" disabled></textarea>
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_valor">Valor</label>
            <input type="text" class="form-control" name="comparendo_valor" value="{{$comparendo->valor}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_barrio">Barrio/Vereda</label>
            <input type="text" class="form-control" name="comparendo_barrio" value="{{$comparendo->barrio_vereda}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_direccion">Dirección</label>
            <input type="text" class="form-control" name="comparendo_direccion" value="{{$comparendo->direccion}}">
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="comparendo_fuga" name="comparendo_fuga" @if($comparendo->fuga) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
                Fuga
            </label>
        </div>
        <hr>
        <h4>Información de alcoholemia</h4>
        <div class="form-group">
            <label class="control-label" for="alcoholemia_grado">Grado</label>
            <input type="text" class="form-control" name="alcoholemia_grado" value="{{ $comparendo->grado_alcoholemia }}">
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="alcoholemia_negacion" name="alcoholemia_negacion" @if($comparendo->niega_alcoholemia) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
                Se negó a realizar
            </label>
        </div>
        <hr>
        <h4>Información del vehículo</h4>
        <div class="form-group">
            <label class="control-label" for="vehiculo_placa">Placa</label>
            <input type="text" class="form-control" name="vehiculo_placa" value="{{$comparendo->hasVehiculo->placa}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_licencia_numero">Licencia tránsito n°</label>
            <input type="text" class="form-control" name="vehiculo_licencia_numero" value="{{$comparendo->hasVehiculo->licencia_transito}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_licenciaTransitoOtto">Licencia Organismo Tránsito</label>
            <input type="text" class="form-control" name="vehiculo_licenciaTransitoOtto" value="{{$comparendo->hasVehiculo->licencia_transito_otto}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_servicio">Servicio</label>
            {{Form::select('vehiculo_servicio', $servicios, $comparendo->hasVehiculo->vehiculo_servicio_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_clase">Clase</label>
            {{Form::select('vehiculo_clase', $clases, $comparendo->hasVehiculo->vehiculo_clase_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_razon_social">Empresa transportadora</label>
            {{Form::select('vehiculo_razon_social', $empresas, $comparendo->hasVehiculo->empresa_transporte_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_tarjeta_operacion">Tarjeta de operación n°</label>
            <input type="text" class="form-control" name="vehiculo_tarjeta_operacion" value="{{$comparendo->hasVehiculo->tarjeta_operacion}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_radio_operacion">Radio de operación</label>
            {{Form::select('vehiculo_radio_operacion', $radiosOperacion, $comparendo->hasVehiculo->vehiculo_radio_operacion_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_nivelServicio">Nivel servicio</label>
            {{Form::select('vehiculo_nivelServicio', $nivelesServicios, $comparendo->hasVehiculo->vehiculo_nivel_servicio_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="propietario_nombre">Nombre del propietario</label>
            <input type="text" class="form-control" name="propietario_nombre" value="{{$comparendo->hasVehiculo->propietario_nombre}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_propTipoDocumento">Propietario tipo documento</label>
            {{Form::select('vehiculo_propTipoDocumento', $tiposDocumentos, $comparendo->hasVehiculo->prop_tipo_documento_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="vehiculo_propNumeroDocumento">Propietario número documento</label>
            <input type="text" class="form-control" name="vehiculo_propNumeroDocumento" value="{{$comparendo->hasVehiculo->prop_numero_documento}}">
        </div> 
        <hr>   
        <h4>Información del infractor</h4>
        @if($comparendo->hasInfractor != null)
        <div class="form-group">
            <label class="control-label" for="conductor_tipo">Tipo</label>
            {{Form::select('conductor_tipo', $infractorTipos, $comparendo->hasInfractor->infractor_tipo_id, ['class'=>'form-control'])}}
        </div> 
        <div class="form-group">
            <label class="control-label" for="conductor_documento">Tipo documento de identidad</label>
            {{Form::select('conductor_documento', $tiposDocumentos, $comparendo->hasInfractor->tipo_documento_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_numero_documento">Número de documento</label>
            <input type="text" class="form-control" name="conductor_numero_documento" value="{{ $comparendo->hasInfractor->numero_documento}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_nombre">Nombre</label>
            <input type="text" class="form-control" name="conductor_nombre" value="{{$comparendo->hasInfractor->nombre}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_dpto">Departamento</label>
            {{Form::select('conductor_dpto', $departamentos, $comparendo->hasInfractor->hasCiudad->departamento_id, ['class'=>'form-control', 'id'=>'conductor_dpto'])}}
        </div>  
        <div class="form-group">
            <label class="control-label" for="conductor_ciudad">Ciudad</label>
            {{Form::select('conductor_ciudad', [], null, ['class'=>'form-control', 'id' => 'conductor_ciudad'])}}
        </div>  
        <div class="form-group">
            <label class="control-label" for="conductor_direccion">Dirección</label>
            <input type="text" class="form-control" name="conductor_direccion" value="{{$comparendo->hasInfractor->direccion}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_direccionElectronica">Dirección electrónica</label>
            <input type="text" class="form-control" name="conductor_direccionElectronica" value="{{$comparendo->hasInfractor->direccion_electronica}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_telefono">Teléfono</label>
            <input type="text" class="form-control" name="conductor_telefono" value="{{$comparendo->hasInfractor->telefono}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_licenciaCategoria">Licencia categoría</label>
            {{Form::select('conductor_licenciaCategoria', $licenciaCategorias, $comparendo->hasInfractor->licencia_categoria_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="conductor_licencia">Licencia conducción n°</label>
            <input type="text" class="form-control" name="conductor_licencia" value="{{$comparendo->hasInfractor->licencia_numero}}">
        </div>        
        <div class="form-group">
            <label class="control-label" for="conductor_licencia_vencimiento">Licencia conducción fecha vencimiento</label>
            <input type="text" class="form-control datepicker" name="conductor_licencia_vencimiento" value="{{$comparendo->hasInfractor->licencia_fecha_vencimiento}}">
        </div>
        @else 
        <div class="form-group">
                <label class="control-label" for="conductor_tipo">Tipo</label>
                {{Form::select('conductor_tipo', $infractorTipos, null, ['class'=>'form-control'])}}
            </div> 
            <div class="form-group">
                <label class="control-label" for="conductor_documento">Tipo documento</label>
                {{Form::select('conductor_documento', $tiposDocumentos, null, ['class'=>'form-control'])}}
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_numero_documento">Número de documento</label>
                <input type="text" class="form-control" name="conductor_numero_documento">
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_nombre">Nombre</label>
                <input type="text" class="form-control" name="conductor_nombre">
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_dpto">Departamento</label>
                {{Form::select('conductor_dpto', $departamentos, null, ['class'=>'form-control', 'id'=>'conductor_dpto'])}}
            </div>  
            <div class="form-group">
                <label class="control-label" for="conductor_ciudad">Ciudad</label>
                {{Form::select('conductor_ciudad', [], null, ['class'=>'form-control', 'id' => 'conductor_ciudad'])}}
            </div>    
            <div class="form-group">
                <label class="control-label" for="conductor_direccion">Dirección</label>
                <input type="text" class="form-control" name="conductor_direccion">
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_direccionElectronica">Dirección electrónica</label>
                <input type="text" class="form-control" name="conductor_direccionElectronica">
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_telefono">Teléfono</label>
                <input type="text" class="form-control" name="conductor_telefono">
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_licenciaCategoria">Licencia categoría</label>
                {{Form::select('conductor_licenciaCategoria', $licenciaCategorias, null, ['class'=>'form-control'])}}
            </div>
            <div class="form-group">
                <label class="control-label" for="conductor_licencia">Licencia número</label>
                <input type="text" class="form-control" name="conductor_licencia">
            </div>    
            <div class="form-group">
                <label class="control-label" for="conductor_licencia_vencimiento">Licencia fecha vencimiento</label>
                <input type="text" class="form-control datepicker" name="conductor_licencia_vencimiento">
            </div> 
        @endif   
        <hr> 
        <h4>Información del agente</h4>
        <div class="form-group">
            <label class="control-label" for="agente">Agente</label>
            {{Form::select('agente', $agentes, $comparendo->hasAgente->hasUsuario->id, ['class'=>'form-control'])}}
        </div>
        <hr>
        <h4>Información de inmovilización</h4>
        @if($comparendo->hasInmovilizacion != null)
        <div class="form-group">
            <label class="control-label" for="comparendo_tipoInmovilizacion">Tipo de inmovilización</label>
            {{Form::select('comparendo_tipoInmovilizacion', $tiposInmovilizaciones, $comparendo->hasInmovilizacion->inmovilizacion_tipo_id, ['class'=>'form-control'])}}
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_observacionInmovilizacion">Observación de inmovilización</label>
            <input type="text" class="form-control" name="comparendo_observacionInmovilizacion" value="{{$comparendo->hasTipoInmovilizacion->first()->pivot->observacion}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_patioNombre">Patio nombre</label>
            <input type="text" class="form-control" name="comparendo_patioNombre"  value="{{$comparendo->hasInmovilizacion->patio_nombre}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_patioDireccion">Patio dirección</label>
            <input type="text" class="form-control" name="comparendo_patioDireccion" value="{{$comparendo->hasInmovilizacion->pation_direccion}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_gruaNumero">Grúa número</label>
            <input type="text" class="form-control" name="comparendo_gruaNumero" value="{{$comparendo->hasInmovilizacion->grua_numero}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_gruaPlaca">Grúa placa</label>
            <input type="text" class="form-control" name="comparendo_gruaPlaca" value="{{$comparendo->hasInmovilizacion->grua_placa}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_inmovilizacionConsecutivo">Consecutivo inmovilización</label>
            <input type="text" class="form-control" name="comparendo_inmovilizacionConsecutivo" value="{{$comparendo->hasInmovilizacion->consecutivo}}">
        </div>
        <div class="form-group">
            <label class="control-label" for="comparendo_observacionInmovilizacionn">Observación</label>
            <textarea class="form-control" name="comparendo_observacionInmovilizacionn">{{$comparendo->hasInmovilizacion->observacion}}</textarea>
        </div>
        @else 
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
        @endif 
        <hr>   
        <h4>Observación del agente</h4>
        <div class="form-group">
            <label class="control-label" for="comparendo_observacion"></label>
            <textarea name="comparendo_observacion" class="form-control">{{$comparendo->observacion_agente}}</textarea>
        </div>
    </form>
@endif
<script type="text/javascript" src="{{asset('js/inspeccion/comparendos/editar.js')}}"></script>