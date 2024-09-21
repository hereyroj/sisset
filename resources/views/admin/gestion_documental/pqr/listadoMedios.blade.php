<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoMedios" onclick="obtenerMedios();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" id="crearMedio" onclick="nuevoMedio();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Medio
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">MEDIOS DE TRASLADO</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medios as $medio)
            <tr @if($medio->trashed()) class="danger" @endif >
                <td>{{$medio->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarMedio({{$medio->id}})">Editar</button>                @if($medio->trashed()) @if(auth()->user()->hasRole('Administrador'))
                    <button type="button" class="btn btn-primary  btn-block" onclick="restaurarMedio({{$medio->id}})">Restaurar</button>                @endif @else @if(auth()->user()->hasRole('Administrador'))
                    <button type="button" class="btn btn-danger  btn-block" onclick="eliminarMedio({{$medio->id}})">Eliminar</button>                @endif @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$medios->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>