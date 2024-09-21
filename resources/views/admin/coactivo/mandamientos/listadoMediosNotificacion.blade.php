<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMediosNotificacion();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoMedioNotificacion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="3">MEDIOS NOTIFICACIONES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Requiere guía?</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medios as $medio)
            <tr>
                <td>{{$medio->name}}</td>
                <td>@if($medio->requiere_guia == 1) Sí @else No @endif </td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMedioNotificacion({{$medio->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$medios->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>