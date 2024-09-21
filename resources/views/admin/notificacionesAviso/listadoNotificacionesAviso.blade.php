<table class="table table-striped">
    <thead>
    <tr>
        <th>Tipo de notificación</th>
        <th>Número proceso</th>
        <th>No. documento notificado</th>
        <th>Nombre notificado</th>
        <th>Documento notificación</th>
        <th>Fecha publicación</th>
        <th>Fecha de desfijación</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($notificacionesAviso as $notificacionAviso)
        <tr>
            <td>
                {{$notificacionAviso->hasTipoNotificacion->name}}
            </td>
            <td>
                {{$notificacionAviso->numero_proceso}}
            </td>
            <td>
                {{$notificacionAviso->numero_documento}}
            </td>
            <td>
                {{$notificacionAviso->nombre_notificado}}
            </td>
            <td>
                <a href="{{url('admin/notificacionesAviso/obtenerDocumento/'.$notificacionAviso->id)}}" class="btn btn-secondary">Ver</a>
            </td>
            <td>
                {{$notificacionAviso->fecha_publicacion}}
            </td>
            <td>
                {{$notificacionAviso->fecha_desfijacion}}
            </td>
            <td>
                <button class="btn btn-secondary" onclick="editarNotificacionAviso({{$notificacionAviso->id}})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarNotificacionAviso({{$notificacionAviso->id}})">Eliminar</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>