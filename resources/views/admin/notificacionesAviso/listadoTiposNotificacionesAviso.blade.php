<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposNotificacionesAviso();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoNotificacionAviso();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Tipo Notificación
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">TIPOS NOTIFICACIONES AVISO</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposNotificacionesAviso as $tipoNotificacionAviso)
            <tr>
                <td>{{$tipoNotificacionAviso->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarTipoNotificacionAviso({{$tipoNotificacionAviso->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tiposNotificacionesAviso->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>