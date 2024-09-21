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
            @foreach($marcas as $marca)
            <tr @if($marca->trashed()) class="danger" @endif>
                <td> {{$marca->name}} </td>
                <td>{{$marca->created_at}}</td>
                <td>{{$marca->updated_at}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-marca" onclick="editarMarca({{$marca->id}})">Editar</button>                    @if($marca->trashed())
                        <button type="button" class="btn btn-secondary btn-activar-marca" onclick="activarMarca({{$marca->id}});">Activar</button>                    @else
                        <a type="button" class="btn btn-secondary btn-eliminar-marca" onclick="eliminarMarca({{$marca->id}});">Eliminar</a>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$marcas->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>