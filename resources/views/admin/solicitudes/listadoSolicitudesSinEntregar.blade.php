<table class="table table-striped" id="solicitudesSinEntregar">
    <thead>
    <tr>
        <th>Carpeta</th>
        <th>Hora salida</th>
        <th>Entregar a</th>
        <th>Turno</th>
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
                {{$solicitud->hasCarpetaPrestada->created_at}}
            </td>
            <td>
                {{$solicitud->hasOrigen->hasFuncionario->name}}
            </td>
            <td>
                @if($solicitud->origen_type == 'App\tramite_servicio')
                    {{$solicitud->hasOrigen->hasSolicitud->hasTurnoActivo()->turno}}
                @endif
            </td>
            <td>
                <button type="button" onclick="entregarCarpeta({{$solicitud->id}});" class="btn btn-success btn-entregar-carpeta">Entregar carpeta</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>