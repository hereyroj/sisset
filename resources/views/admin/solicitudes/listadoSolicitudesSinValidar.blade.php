<table class="table table-striped" id="solicitudesSinValidar">
    <thead>
    <tr>
        <th>Carpeta</th>
        <th>Hora salida</th>
        <th>Tramites</th>
        <th>Entregado a:</th>
        <th>Entregado el:</th>
        <th>Entregado por:</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicitudes as $solicitud)
        <tr>
            <td>
                {{$solicitud->hasTramiteServicio->placa}}
            </td>
            <td>
                {{$solicitud->created_at}}
            </td>
            <td>
                @foreach($solicitud->hasTramiteServicio->hasTramites as $tramite)
                <span class="badge badge-pill badge-primary">{{$tramite->name}}</span> 
                @endforeach
            </td>
            <td>
                {{$solicitud->hasCarpetaPrestada->hasFuncionarioRecibe->name}}
            </td>
            <td>
                {{$solicitud->hasCarpetaPrestada->created_at}}
            </td>
            <td>
                {{$solicitud->hasCarpetaPrestada->hasFuncionarioEntrega->name}}
            </td>
            <td>
                <button type="button" onclick="validarSolicitud({{$solicitud->id}});" class="btn btn-success btn-validarSolicitud">Validar solicitud</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>