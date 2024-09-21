<p><strong>Fecha:</strong><br>{{$entrega->fecha_entrega}}</p>
<p><strong>Observación:</strong><br>{{$entrega->observacion}}</p>
<p><strong>Documento entrega:</strong><br>@if($entrega->documento_entrega != null) <a href="{{url('admin/coactivo/mandamientos/obtenerDocumentoEntrega/'.$entrega->id)}}" class="btn btn-secondary">Ver</a>@else Sin documento @endif</p>