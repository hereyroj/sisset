@foreach($mandamientos as $mandamiento)
    <h4>Información del mandamiento</h4>
    <p><strong>Fecha:</strong><br>{{$mandamiento->fecha_mandamiento}}</p>
    <p><strong>Número:</strong><br>{{$mandamiento->consecutivo}}</p>
    <p><strong>Valor:</strong><br>{{$mandamiento->valor}}</p>
    <h4>Información de las notificaciones</h4>
    <table class="table table-striped ">
        <thead>
        <tr>
            <th>Tipo</th>
            <th>Consecutivo</th>
            <th>Fecha</th>
            <th>Fecha max. presentación</th>
            <th>Estado</th>
            <th>Medio</th>
            <th>Empresa</th>
            <th>Número guía</th>
            <th>Notificación</th>
            <th>Pantallazo RUNT</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mandamiento->hasNotificaciones as $notificacion)
            <tr>
                <td>{{$notificacion->hasTipoNotificacion->name}}</td>
                <td>{{$notificacion->consecutivo}}</td>
                <td>{{$notificacion->fecha_notificacion}}</td>
                <td>{{$notificacion->fecha_max_presentacion}}</td>
                <td>
                    @if($notificacion->hasEntrega)
                        <button type="button" class="btn btn-primary btn-sm" onclick="verEntrega({{$notificacion->hasEntrega->id}})">Ver Entrega</button>
                    @elseif($notificacion->hasDevolucion)
                        <button type="button" class="btn btn-warning btn-sm" onclick="verDevolucion({{$notificacion->hasDevolucion->id}})">Ver Devolución</button>
                    @endif
                </td>
                <td>{{$notificacion->hasMedio->hasMedioNotificacion->name}}</td>
                <td>@if($notificacion->hasMedio->hasEmpresa != null) {{$notificacion->hasMedio->hasEmpresa->name}} @endif</td>
                <td>{{$notificacion->hasMedio->numero_guia}}</td>
                <td><a href="{{url('admin/coactivo/mandamientos/obtenerMandamientoNotificacion/'.$notificacion->id)}}" class="btn btn-outline-secondary">Ver</a></td>
                <td><a href="{{url('admin/coactivo/mandamientos/obtenerPantalalzoRuntMandamientoNotificacion/'.$notificacion->id)}}" class="btn btn-outline-secondary">Ver</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h4>Información de finalización</h4>
    @if($mandamiento->hasFinalizacion != null)
    <p><strong>Tipo:</strong><br>{{$mandamiento->hasFinalizacionn->hasTipoFinalizacion->name}}</p>
    <p><strong>Fecha:</strong><br>{{$mandamiento->hasFinalizacion->fecha_finalizacion}}</p>
    <p><strong>Observación:</strong><br>{{$mandamiento->hasFinalizacion->observacion}}</p>
    <p><strong>Documento:</strong>@if($mandamiento->hasFinalizacion->documento != null)<a class="btn btn-secondary" href="{{url('admin/coactivo/mandamientos/obtenerDocumentoFinalizacion/'.$finalizacion->id)}}">Ver</a>@else Sin documento @endif</p>
    @else
    Sin finalización
    @endif
@endforeach