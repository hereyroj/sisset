
    <table class="table table-striped table-hover table-sm">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Archivo</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notificacionesAviso as $notificacionAviso)
            <tr>
                <td class="text-truncate">{{$notificacionAviso->numero_documento}}</td>
                <td class="text-truncate">{{$notificacionAviso->nombre_notificado}}</td>
                <td class="text-truncate"><a href="{{url('servicios/notificacionesAviso/documento/'.$notificacionAviso->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a></td>
                <td class="text-truncate">{{$notificacionAviso->fecha_publicacion}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$notificacionesAviso->links('vendor.pagination.bootstrap-4')}}
    </div>