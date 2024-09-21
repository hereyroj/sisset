@if($pago != null)
<p><strong>Fecha pago:</strong><br>{{$pago->fecha_pago}}</p>
<p><strong>Valor:</strong><br>{{$pago->valor}}</p>
<p><strong>Descuento al valor:</strong><br>{{$pago->descuento_valor}}</p>
<p><strong>Intereses:</strong><br>{{$pago->valor_intereses}}</p>
<p><strong>Descuento a intereses:</strong><br>{{$pago->descuento_intereses}}</p>
<p><strong>Cobro adicional:</strong><br>{{$pago->cobro_adicional}}</p>
<p><strong>Número factura:</strong><br>{{$pago->numero_factura}}</p>
<p><strong>Número consignación:</strong><br>{{$pago->numero_consignacion}}</p>
<p><strong>Consignación:</strong><br><a href="{{url('admin/coactivo/mandamientos/obtenerConsginacionPago/'.$pago->id)}}" class="btn btn-secondary">Ver</a> </p>
@else 
No hay información del pago.
@endif