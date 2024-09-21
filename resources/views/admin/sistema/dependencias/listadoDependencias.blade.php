<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Creado el</th>
                <th>Actualizado el</th>
                @if(Defender::hasRole('Administrador'))
                <th>Acción</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($dependencias as $dependencia)
            <tr @if($dependencia->trashed()) class="danger" @endif>
                <td>{{$dependencia->name}}</td>
                <td>{{$dependencia->created_at}}</td>
                <td>{{$dependencia->updated_at}}</td>
                @if(Defender::hasRole('Administrador'))
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-dependencia" onclick="editarDependencia({{$dependencia->id}});">Editar</button>                    @if($dependencia->trashed())
                        <button type="button" class="btn btn-secondary btn-activar-dependencia" onclick="activarDependencia({{$dependencia->id}});">Activar</button>                    @else
                        <button type="button" class="btn btn-secondary btn-eliminar-dependencia" onclick="eliminarDependencia({{$dependencia->id}});">Eliminar</button>                    @endif
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$dependencias->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>