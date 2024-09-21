<h4>Placa</h4>
<p>{{$agente->placa}}</p>
<h4>Fecha de vinculación</h4>
<p>{{$agente->fecha_ingreso}}</p>
<h4>Estado</h4>
@if($agente->estado == 1)
<p>Activo</p>
@else
<p>Inactivo</p>
<h4>Fecha de desvinculación</h4>
<p>{{$agente->fecha_retiro}}</p>
@endif