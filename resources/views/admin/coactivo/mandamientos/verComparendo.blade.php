<div class="row" style="margin:0;">
    <div class="col-md-4">
        <h4>Información del comparendo</h4>
        <p>Número: {{$comparendo->numero}}</p>
        <p>Tipo: {{$comparendo->hasTipoComparendo->name}}</p>
        <p>Fecha: {{$comparendo->fecha_realizacion}}</p>
        <p>Valor: ${{$comparendo->valor}}</p>
        <p>Infracción: {{$comparendo->hasInfraccion->name}}</p>
        <p>Barrio/Vereda: {{$comparendo->barrio_vereda}}</p>
        <p>Dirección: {{$comparendo->direccion}}</p>
        <h4>Información del agente</h4>
        <p>Entidad: {{$comparendo->hasAgente->hasEntidad->name}}</p>
        <p>Nombre del agente: {{$comparendo->hasAgente->hasUsuario->name}}</p>
        <p>Placa: {{$comparendo->hasAgente->placa}}</p>
        <p>Fecha ingreso: {{$comparendo->hasAgente->fecha_ingreso}}</p>
        <p>Observación del agente: {{$comparendo->observacion_agente}}</p>
        @if($comparendo->hasAgente->estado == 1)
            <p>Estado: Activo</p>
        @else
            <p>Estado: Inactivo</p>
            <p>Fecha retiro: {{$comparendo->hasAgente->fecha_retiro}}</p>
        @endif
        <h4>Información de la inmovilización</h4>
        @if($comparendo->hasInmovilizacion != null)
        <p>Tipo inmovilización: {{$comparendo->hasInmovilizacion->hasTipoInmovilizacion->name}}</p>
        <p>Patio nombre: {{$comparendo->hasInmovilizacion->patio_nombre}}</p>
        <p>Patio dirección: {{$comparendo->hasInmovilizacion->patio_direccion}}</p>
        <p>Grúa número: {{$comparendo->hasInmovilizacion->grua_numero}}</p>
        <p>Grúa placa: {{$comparendo->hasInmovilizacion->grua_placa}}</p>
        <p>Consecutivo: {{$comparendo->hasInmovilizacion->consecutivo}}</p>
        <p>Observación: {{$comparendo->hasInmovilizacion->observacion}}</p>
        @else 
        No hay información de la inmovilización.
        @endif
    </div>
    <div class="col-md-4">
        <h4>Información del vehículo</h4>
        <p>Nombre del propietario: {{$comparendo->hasVehiculo->propietario_nombre}}</p>
        <p>Tipo documento propietario: @if($comparendo->hasVehiculo->hasTipoDocumentoPropietario != null){{$comparendo->hasVehiculo->hasTipoDocumentoPropietario->name}}@endif</p>
        <p>Número documento propietario: {{$comparendo->hasVehiculo->prop_numero_documento}}</p>
        <p>Nombre del propietario: {{$comparendo->hasVehiculo->propietario_nombre}}</p>
        <p>Placa: {{$comparendo->hasVehiculo->placa}}</p>
        <p>Número de licencia de tránsito: {{$comparendo->hasVehiculo->licencia_transito}}</p>
        <p>Organismo licencia de tránsito: {{$comparendo->hasVehiculo->licencia_transito_otto}}</p>
        <p>Servicio: {{$comparendo->hasVehiculo->hasVehiculoServicio->name}}</p>
        <p>Nivel servicio: @if($comparendo->hasVehiculo->hasVehiculoNivelServicio != null){{$comparendo->hasVehiculo->hasVehiculoNivelServicio->name}}@endif</p>
        <p>Clase: {{$comparendo->hasVehiculo->hasVehiculoClase->name}}</p>
        <p>Empresa de transporte: @if($comparendo->hasVehiculo->hasEmpresaTransporte != null){{$comparendo->hasVehiculo->hasEmpresaTransporte->name}}@endif</p>
        <p>Número de tarjeta de operación: {{$comparendo->hasVehiculo->tarjeta_operacion}}</p>
        <p>Radio de operación: @if($comparendo->hasVehiculo->hasVehiculoRadioOperacion != null){{$comparendo->hasVehiculo->hasVehiculoRadioOperacion->name}}@endif</p>
        <h4>Información del testigo</h4>
        @if($comparendo->hasTestigo != null)
        <p>Tipo documento: {{$comparendo->hasTestigo->hasTipoDocumento->name}}</p>
        <p>Número documento: {{$comparendo->hasTestigo->numero_documento}}</p>
        <p>Nombre: {{$comparendo->hasTestigo->nombre}}</p>
        <p>Dirección: {{$comparendo->hasTestigo->direccion}}</p>
        <p>Teléfono: {{$comparendo->hasTestigo->telefono}}</p>
        @else 
        No hay información del testigo.
        @endif   
    </div>
    <div class="col-md-4">
        <h4>Información del infractor</h4>
        @if($comparendo->hasInfractor != null)
        <p>Tipo de infractor: {{$comparendo->hasInfractor->hasTipoInfractor->name}}</p>
        <p>Tipo de documento: {{$comparendo->hasInfractor->hasTipoDocumento->name}}</p>
        <p>Número de documento: {{$comparendo->hasInfractor->numero_documento}}</p>
        <p>Nombres y apellidos: {{$comparendo->hasInfractor->nombre}}</p>
        <p>Ciudad: {{$comparendo->hasInfractor->hasCiudad->name}}</p>
        <p>Dirección: {{$comparendo->hasInfractor->direccion}}</p>
        <p>Dirección electrónica: {{$comparendo->hasInfractor->direccion_electronica}}</p>
        <p>Teléfono: {{$comparendo->hasInfractor->telefono}}</p>
        <p>Ciudad RUNT: @if($comparendo->hasInfractor->hasCiudadRunt != null){{$comparendo->hasInfractor->hasCiudadRunt->name}}@endif</p>
        <p>Dirección RUNT: {{$comparendo->hasInfractor->direccion_runt}}</p>
        <p>Teléfono RUNT: {{$comparendo->hasInfractor->telefono_runt}}</p>
        <p>Categoría licencia de conducción: @if($comparendo->hasInfractor->hasCategoriaLicenciaConduccion != null){{$comparendo->hasInfractor->hasCategoriaLicenciaConduccion->name}}@endif</p>
        <p>Número licencia de conducción: {{$comparendo->hasInfractor->licencia_numero}}</p>
        <p>Vencimiento licencia de conducción: {{$comparendo->hasInfractor->licencia_fecha_vencimiento}}</p>
        @else 
        No hay información del infractor.
        @endif
        <h4>Información del pago</h4>
        @if($comparendo->hasPago != null)
            <p><strong>Fecha pago:</strong><br>{{$comparendo->hasPago->fecha_pago}}</p>
            <p><strong>Valor:</strong><br>{{$comparendo->hasPago->valor}}</p>
            <p><strong>Descuento al valor:</strong><br>{{$comparendo->hasPago->descuento_valor}}</p>
            <p><strong>Intereses:</strong><br>{{$comparendo->hasPago->valor_intereses}}</p>
            <p><strong>Descuento a intereses:</strong><br>{{$comparendo->hasPago->descuento_intereses}}</p>
            <p><strong>Cobro adicional:</strong><br>{{$comparendo->hasPago->cobro_adicional}}</p>
            <p><strong>Número factura:</strong><br>{{$comparendo->hasPago->numero_factura}}</p>
            <p><strong>Número consignación:</strong><br>{{$comparendo->hasPago->numero_consignacion}}</p>
            <p><strong>Consignación:</strong><br><a href="{{url('admin/coactivo/mandamientos/obtenerConsginacionPago/'.$comparendo->hasPago->id)}}" class="btn btn-secondary">Ver</a> </p>
        @else 
        No hay información del pago.
        @endif
        <h4>Información de la sanción</h4>
        <p>Número: {{$comparendo->hasSancion->consecutivo}}</p>
        <p>Fecha: {{$comparendo->hasSancion->fecha_publicacion}}</p>
        <p>Documento: <a href="{{url('admin/coactivo/mandamientos/verSancion/'.$comparendo->hasSancion->id)}}" class="btn btn-secondary">Ver</a> </p>
    </div>
</div>