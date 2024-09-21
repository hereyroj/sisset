@if($testigo != null)
<p><strong>Tipo documento:</strong><br>{{$testigo->hasTipoDocumento->name}}</p>
<p><strong>Número documento:</strong><br>{{$testigo->numero_documento}}</p>
<p><strong>Nombre:</strong><br>{{$testigo->nombre}}</p>
<p><strong>Dirección:</strong><br>{{$testigo->direccion}}</p>
<p><strong>Teléfono:</strong><br>{{$testigo->telefono}}</p>
@else 
No hay información del testigo. 
@endif