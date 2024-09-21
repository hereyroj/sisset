<h4>Modalidad de envío</h4>
<p>{{$pqr->hasEnvio->hasModalidadEnvio->name}}</p>
<h4>Empresa de envío</h4>
<p>@if($pqr->hasEnvio->hasEmpresaMensajeria != null) {{$pqr->hasEnvio->hasEmpresaMensajeria->name}} @else N/A @endif</p>
<h4>Número de guía</h4>
<p>{{$pqr->hasEnvio->numero_guia}}</p>
<h4>Fecha de envío</h4>
<p>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pqr->hasEnvio->fecha_hora_envio)->toDateString()}}</p>
<h4>Hora de envío</h4>
<p>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pqr->hasEnvio->fecha_hora_envio)->toTimeString()}}</p>