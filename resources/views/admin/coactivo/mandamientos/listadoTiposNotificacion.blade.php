<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposNotificacion();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoNotificacion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="4">TIPOS NOTIFICACIONES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Cant días</th>
                <th>Tipo día</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>{{$tipo->name}}</td>
                <td>{{$tipo->dia_cantidad}}</td>
                <td>
                    @if($tipo->dia_tipo == 'h')
                    Hábil
                    @else 
                    Calendario 
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarTipoNotificacion({{$tipo->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tipos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>