<p><strong>Tipo:</strong><br>{{$finalizacion->hasTipoFinalizacion->name}}</p>
<p><strong>Fecha:</strong><br>{{$finalizacion->fecha_finalizacion}}</p>
<p><strong>Observación:</strong><br>{{$finalizacion->observacion}}</p>
<p><strong>Documento:</strong>@if($finalizacion->documento != null)<a class="btn btn-secondary" href="{{url('admin/coactivo/mandamientos/obtenerDocumentoFinalizacion/'.$finalizacion->id)}}">Ver</a>@else Sin documento @endif</p>