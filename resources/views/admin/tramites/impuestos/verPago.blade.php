<h4>Número de la consignación</h4>
<p>{{$pago->numero_consignacion}}</p>
<h4>Valor de la consignación</h4>
<p>{{$pago->valor_consignacion}}</p>
<h4>Consignación (PDF)</h4>
<iframe id="viewer" src="/admin/liquidaciones/vehiculos/verConsignacion/{{$pago->id}}" width="100%" height="400px"></iframe>