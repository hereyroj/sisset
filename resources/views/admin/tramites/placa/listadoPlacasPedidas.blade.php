<table class="table table-striped">
    <thead>
    <tr>
        <th>Sustrato</th>
        <th>Placa</th>
        <th>Trámites</th>
        <th>Identificación propietario</th>
        <th>Fecha pago</th>
        <th>Liquidación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($servicios as $servicio)
        <tr>
            <td>{{$servicio->hasFinalizacion->hasSustrato->numero}}</td>
            <td>{{$servicio->placa}}</td>
            <td>
                @foreach($servicio->hasTramites as $tramite)
                {{$tramite->name}}
                @if (!$loop->last) , @endif
                @endforeach
            </td>
            <td>{{$servicio->documento_propietario}}</td>
            <td>{{$servicio->hasRecibos()->latest()->created_at}}</td>
            <td>{{$servicio->hasRecibos()->latest()->numero_sintrat}}</td>            
        </tr>
    @endforeach
    </tbody>
</table>