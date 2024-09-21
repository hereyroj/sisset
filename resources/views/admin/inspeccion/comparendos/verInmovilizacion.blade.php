@if($inmovilizacion != null)
<p><strong>Tipo inmovilización:</strong><br>{{$inmovilizacion->hasTipoInmovilizacion->name}}</p>
<p><strong>Patio nombre:</strong><br>{{$inmovilizacion->patio_nombre}}</p>
<p><strong>Patio dirección:</strong><br>{{$inmovilizacion->patio_direccion}}</p>
<p><strong>Grúa número:</strong><br>{{$inmovilizacion->grua_numero}}</p>
<p><strong>Grúa placa:</strong><br>{{$inmovilizacion->grua_placa}}</p>
<p><strong>Consecutivo:</strong><br>{{$inmovilizacion->consecutivo}}</p>
<p><strong>Observación:</strong><br>{{$inmovilizacion->observacion}}</p>
@else 
No hay información de la inmovilización.
@endif