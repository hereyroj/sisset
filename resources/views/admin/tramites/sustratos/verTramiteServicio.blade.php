<p><strong>Placa:</strong><br>{{$servicio->placa}}</p>
<p><strong>Servicio Vehículo:</strong><br>{{$servicio->hasVehiculoServicio->name}}</p>
<p><strong>Clase vehículo:</strong><br>{{$servicio->hasVehiculoClase->name}}</p>   
<p><strong>Fecha y Hora del Servicio:</strong><br>{{$servicio->created_at}}</p>  
<p><strong>Tramites realizados:</strong><br>@foreach($servicio->hasTramites as $tramite)<span class="badge badge-pill badge-primary">{{$tramite->name}}</span>@endforeach</p>              