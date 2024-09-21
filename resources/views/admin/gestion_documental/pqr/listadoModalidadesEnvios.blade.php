<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoModalidadess" onclick="obtenerModalidades();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" id="crearModalidades" onclick="nuevaModalidad();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva modalidad
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">MODALIDADES DE ENVIO</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modalidades as $modalidad)
            <tr @if($modalidad->trashed()) class="danger" @endif >
                <td>{{$modalidad->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarModalidad({{$modalidad->id}})">Editar</button>                @if($modalidad->trashed()) @if(auth()->user()->hasRole('Administrador'))
                    <button type="button" class="btn btn-primary  btn-block" onclick="restaurarModalidad({{$modalidad->id}})">Restaurar</button>                @endif @else @if(auth()->user()->hasRole('Administrador'))
                    <button type="button" class="btn btn-danger  btn-block" onclick="eliminarModalidad({{$modalidad->id}})">Eliminar</button>                @endif @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$modalidades->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>