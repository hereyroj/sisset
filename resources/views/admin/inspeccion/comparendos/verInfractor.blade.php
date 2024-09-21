@if($infractor != null)
<p><strong>Tipo de infractor:</strong><br>{{$infractor->hasTipoInfractor->name}}</p>
<p><strong>Tipo de documento:</strong><br>{{$infractor->hasTipoDocumento->name}}</p>
<p><strong>Número de documento:<br></strong>{{$infractor->numero_documento}}</p>
<p><strong>Nombres y apellidos:<br></strong>{{$infractor->nombre}}</p>
<p><strong>Ciudad:</strong><br>{{$infractor->hasCiudad->name}}</p>
<p><strong>Dirección:</strong><br>{{$infractor->direccion}}</p>
<p><strong>Dirección electrónica:</strong><br>{{$infractor->direccion_electronica}}</p>
<p><strong>Teléfono:</strong><br>{{$infractor->telefono}}</p>
<p><strong>Ciudad RUNT:</strong><br>@if($infractor->hasCiudadRunt != null){{$infractor->hasCiudadRunt->name}}@endif</p>
<p><strong>Dirección RUNT:</strong><br>{{$infractor->direccion_runt}}</p>
<p><strong>Teléfono RUNT:</strong><br>{{$infractor->telefono_runt}}</p>
<p><strong>Categoría licencia de conducción:</strong><br>@if($infractor->hasCategoriaLicenciaConduccion != null){{$infractor->hasCategoriaLicenciaConduccion->name}}@endif</p>
<p><strong>Número licencia de conducción:</strong><br>{{$infractor->licencia_numero}}</p>
<p><strong>Vencimiento licencia de conducción:</strong><br>{{$infractor->licencia_fecha_vencimiento}}</p>
@else 
No hay información del infractor.
@endif