<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoClases" onclick="obtenerMotivosSolicitud();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" id="crearClase" onclick="nuevoMotivoSolicitud();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="3">MOTIVOS SOLICITUDES</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Prioritario?</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($motivos as $motivo)
            <tr>
                <td>{{$motivo->name}}</td>
                <td>
                    @if($motivo->priorizar)
                    SI
                    @else
                    NO
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarMotivoSolicitud({{$motivo->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$motivos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>