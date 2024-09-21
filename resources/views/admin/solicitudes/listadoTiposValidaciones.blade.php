<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoClases" onclick="obtenerTiposValidaciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" id="crearClase" onclick="nuevoTipoValidacion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">TIPOS DE VALIDACIONES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposValidaciones as $tipo)
            <tr>
                <td>{{$tipo->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarTipoValidacion({{$tipo->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tiposValidaciones->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>