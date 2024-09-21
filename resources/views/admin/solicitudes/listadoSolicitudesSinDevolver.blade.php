<table class="table table-striped" id="solicitudesSinDevolver">
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
                {{$solicitud->hasCarpetaPrestada->hasCarpeta->name}}
            </td>
            <td>
                {{$solicitud->created_at}}
            </td>
            <td>
                @if($solicitud->origen_type == 'App\tramite_servicio')
                    @foreach($solicitud->hasOrigen->hasTramites as $tramite)
                        <span class="badge badge-pill badge-primary">{{$tramite->name}}</span>
                    @endforeach
                @else
                    {{$solicitud->hasOrigen->hasMotivo->name}}
                @endif
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
                <button type="button" onclick="ingresarCarpeta({{$solicitud->id}})" class="btn btn-success">Ingresar carpeta</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>