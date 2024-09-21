<table class="table table-striped">
    <thead>
    <tr>
        <th colspan="2">Información</th>
        <th colspan="3">Información del solicitante</th>
        <th colspan="6">Información del vehículo</th>
        <th colspan="4">Información del propietario</th>
        <th rowspan="2" colspan="1">Acción</th>
    </tr>
    <tr>
        <th colspan="1">Fecha</th>
        <th colspan="1">Estado</th>
        <th colspan="1">Tipo documento</th>
        <th colspan="1">Número</th>
        <th rowspan="1">Nombre</th>
        <th colspan="1">Motor</th>
        <th colspan="1">Chasis</th>
        <th colspan="1">Clase</th>
        <th colspan="1">Servicio</th>
        <th colspan="1">Manifiesto</th>
        <th colspan="1">Factura</th>
        <th colspan="1">Tipo documento</th>
        <th colspan="1">Número</th>
        <th colspan="1">Nombres</th>
        <th colspan="1">Cedula</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicitudes as $solicitud)
        <tr>
            <td>
                {{$solicitud->created_at}}
            </td>
            <td>
                {{$solicitud->getEstado()}}
            </td>
            <td>
                {{$solicitud->hasSolicitanteTipoDocumento->name}}
            </td>
            <td>
                {{$solicitud->numero_documento_solicitante}}
            </td>
            <td>
                {{$solicitud->nombre_solicitante}}
            </td>
            <td>
                {{$solicitud->numero_motor}}
            </td>
            <td>
                {{$solicitud->numero_chasis}}
            </td>
            <td>
                {{$solicitud->hasVehiculoClase->name}}
            </td>
            <td>
                {{$solicitud->hasvehiculoServicio->name}}
            </td>
            <td>
                @if($solicitud->manifiesto_importacion != null)
                    <a class="btn btn-secondary" href="{{url('/admin/tramites/preAsignaciones/verManifiesto/'.$solicitud->id)}}">Ver</a>
                @else
                    <button type="button" class="btn btn-primary" onclick="subirManifiesto({{$solicitud->id}});">Subir</button>
                @endif
            </td>
            <td>
                @if($solicitud->factura_compra != null)
                    <a class="btn btn-secondary" href="{{url('/admin/tramites/preAsignaciones/verFactura/'.$solicitud->id)}}">Ver</a>
                @else
                    <button type="button" class="btn btn-primary" onclick="subirFactura({{$solicitud->id}});">Subir</button>
                @endif
            </td>
            <td>
                {{$solicitud->hasPropietarioTipoDocumento->name}}
            </td>
            <td>
                {{$solicitud->numero_documento_propietario}}
            </td>
            <td>
                {{$solicitud->nombre_propietario}}
            </td>
            <td>
                <a class="btn btn-secondary" href="{{url('/admin/tramites/preAsignaciones/verCedulaPropietario/'.$solicitud->id)}}">Ver</a>
            </td>
            <td>
                @if($solicitud->hasRechazo()->count() == 0)
                    @if($solicitud->hasPlacaActiva() == null)
                        <button type="button" class="btn btn-secondary btn-block" onclick="preAsignar({{$solicitud->id}});"> Pre-asignar</button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="rechazarSolicitud({{$solicitud->id}});">Rechazar</button>
                    @else
                        @if($solicitud->hasPlacaActiva()->pivot->fecha_matricula == null && $solicitud->hasPlacaActiva()->pivot->fecha_liberacion == null)
                            <button type="button" class="btn btn-secondary btn-block" onclick="matricularSolicitud({{$solicitud->id}});">Matricular</button>
                            <button type="button" class="btn btn-secondary btn-block" onclick="liberarSolicitud({{$solicitud->id}});">Liberar</button>
                        @endif
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>