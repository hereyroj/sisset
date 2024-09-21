<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMotivosLiberaciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoMotivoLiberacion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Motivo Liberación
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="4">MOTIVOS LIBERACIONES</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($motivos as $motivo)
            <tr>
                <td>{{$motivo->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMotivoLiberacion({{$motivo->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$motivos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>