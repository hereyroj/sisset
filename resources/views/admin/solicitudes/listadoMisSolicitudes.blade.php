<table class="table table-striped">
    <thead>
    <tr>
        <th>Estado</th>
        <th>Placa</th>
        <th>Motivo</th>
        <th>Hora solicitud</th>
        <th>Hora entrega</th>
        <th>Hora devolución</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicitudes as $solicitud)
        <tr>
            <td>
                {{$solicitud->hasSolicitud->getEstado()}}
            </td>
            <td>
                {{$solicitud->placa}}
            </td>
            <td>
                {{$solicitud->hasMotivo->name}}
            </td>
            <td>
                {{$solicitud->created_at}}
            </td>
            @if($solicitud->hasSolicitud->hasCarpetaPrestada)
            <td>
                {{$solicitud->hasSolicitud->hasCarpetaPrestada->fecha_entrega}}
            </td>
            <td>
                {{$solicitud->hasSolicitud->hasCarpetaPrestada->fecha_devolucion}}
            </td>
            @else
            <td></td>
            <td></td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>