<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMotivosDevolucion();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoMotivoDevolucion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">MOTIVOS DEVOLUCIONES</th>
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
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMotivoDevolucion({{$motivo->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$motivos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>