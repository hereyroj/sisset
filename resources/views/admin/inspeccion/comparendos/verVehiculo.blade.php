<p><strong>Nombre del propietario:</strong><br>{{$vehiculo->propietario_nombre}}</p>
<p><strong>Tipo documento propietario:</strong><br>@if($vehiculo->hasTipoDocumentoPropietario != null){{$vehiculo->hasTipoDocumentoPropietario->name}}@endif</p>
<p><strong>Número documento propietario:</strong><br>{{$vehiculo->prop_numero_documento}}</p>
<p><strong>Nombre del propietario:</strong><br>{{$vehiculo->propietario_nombre}}</p>
<p><strong>Placa:</strong><br>{{$vehiculo->placa}}</p>
<p><strong>Número de licencia de tránsito:</strong><br>{{$vehiculo->licencia_transito}}</p>
<p><strong>Organismo licencia de tránsito:</strong><br>{{$vehiculo->licencia_transito_otto}}</p>
<p><strong>Servicio:</strong><br>{{$vehiculo->hasVehiculoServicio->name}}</p>
<p><strong>Nivel servicio:</strong><br>@if($vehiculo->hasVehiculoNivelServicio != null){{$vehiculo->hasVehiculoNivelServicio->name}}@endif</p>
<p><strong>Clase:</strong><br>{{$vehiculo->hasVehiculoClase->name}}</p>
<p><strong>Empresa de transporte:</strong><br>@if($vehiculo->hasEmpresaTransporte != null){{$vehiculo->hasEmpresaTransporte->name}}@endif</p>
<p><strong>Número de tarjeta de operación:</strong><br>{{$vehiculo->tarjeta_operacion}}</p>
<p><strong>Radio de operación:</strong><br>@if($vehiculo->hasVehiculoRadioOperacion != null){{$vehiculo->hasVehiculoRadioOperacion->name}}@endif</p>