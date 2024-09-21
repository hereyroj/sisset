@foreach($servicios as $servicio)
<tr>
    <td>{{$servicio->placa}}</td>
    <td>{{$servicio->hasVehiculoServicio->name}}</td>
    <td>{{$servicio->hasVehiculoClase->name}}</td>
    <td>{{$servicio->created_at}}</td>
    <td>
        @foreach($servicio->hasTramites as $tramite)
            <span class="badge badge-pill badge-primary">{{$tramite->name}}</span> 
        @endforeach
    </td>
    <td><button type="button" class="btn btn-secondary" onclick="obtenerEstadosServicio({{$servicio->id}})">Ver</button></td>
    <td><button type="button" class="btn btn-secondary" onclick="obtenerCarpetasServicio({{$servicio->id}})">Ver</button></td>
    <td><button type="button" class="btn btn-secondary" onclick="obtenerRecibosServicio({{$servicio->id}})">Ver</button></td>
    <td><button type="button" class="btn btn-secondary" onclick="obtenerFinalizacionServicio({{$servicio->id}})">Ver</button></td>
</tr>
@endforeach