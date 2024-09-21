<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMotivosRechazo();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoMotivoRechazo();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo motivo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">MOTIVOS RECHAZO</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($motivos as $motivo)
            <tr @if($motivo->trashed()) class="danger" @endif >
                <td>{{$motivo->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMotivoRechazo({{$motivo->id}})">Editar</button>                @if($motivo->trashed())
                    <button type="button" class="btn btn-primary  btn-block" onclick="restaurarMotivoRechazo({{$motivo->id}})">Restaurar</button>                @else
                    <button type="button" class="btn btn-danger  btn-block" onclick="eliminarMotivoRechazo({{$motivo->id}})">Eliminar</button>                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$motivos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>