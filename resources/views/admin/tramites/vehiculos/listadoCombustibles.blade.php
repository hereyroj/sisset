<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha creación</th>
                <th>Última actualización</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($combustibles as $combustible)
            <tr @if($combustible->trashed()) class="danger" @endif>
                <td>
                    {{$combustible->name}}
                </td>
                <td>{{$combustible->created_at}}</td>
                <td>{{$combustible->updated_at}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-combustible" onclick="editarCombustible({{$combustible->id}});">Editar</button>                    @if($combustible->trashed())
                        <button type="button" class="btn btn-secondary btn-activar-combustible" onclick="activarCombustible({{$combustible->id}});">Activar</button>                    @else
                        <button type="button" class="btn btn-secondary btn-eliminar-combustible" onclick="eliminarCombustible({{$combustible->id}});">Eliminar</button>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center listadoCombustibles">
        {{$combustibles->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>