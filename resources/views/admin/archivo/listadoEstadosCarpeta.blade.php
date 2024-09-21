<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoClases" onclick="obtenerEstadosCarpetas();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" id="crearClase" onclick="nuevoEstadoCarpeta();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Disponibilidad carpeta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $estado)
            <tr>
                <td>{{$estado->name}}</td>
                <td>{{$estado->estado_carpeta}}</td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarEstadoCarpeta({{$estado->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$estados->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>    