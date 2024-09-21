<p><strong>Entidad:</strong><br>{{$agente->hasEntidad->name}}</p>
<p><strong>Nombre del agente:</strong><br>{{$agente->hasUsuario->name}}</p>
<p><strong>Placa:</strong><br>{{$agente->placa}}</p>
<p><strong>Fecha ingreso:</strong><br>{{$agente->fecha_ingreso}}</p>
@if($agente->estado == 1)
    <p><strong>Estado:</strong><br>Activo</p>
@else
    <p><strong>Estado:</strong><br>Inactivo</p>
    <p><strong>Fecha retiro:</strong><br>{{$agente->fecha_retiro}}</p>
@endif