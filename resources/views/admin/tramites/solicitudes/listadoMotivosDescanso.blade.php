<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerMotivosDescanso();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoMotivoDescanso();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Motivo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="3">MOTIVOS DESCANSO</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Tiempo (minutos)</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($motivos as $motivo)
            <tr>
                <td>{{$motivo->name}}</td>
                <td>{{$motivo->minutes}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMotivoDescanso({{$motivo->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$motivos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>