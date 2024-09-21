<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Pre-asignable</th>
                <th>Fecha creación</th>
                <th>Última actualización</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clases as $clase)
            <tr @if($clase->trashed()) class="danger" @endif>
                <td>
                    {{$clase->name}}
                </td>
                <th>{{$clase->pre_asignable}}</th>
                <td>{{$clase->created_at}}</td>
                <td>{{$clase->updated_at}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-clase" onclick="editarClase({{$clase->id}})">Editar</button>                    @if($clase->trashed())
                        <a type="button" class="btn btn-secondary btn-activar-clase" onclick="activarClase({{$clase->id}});">Activar</a>                    @else
                        <a type="button" class="btn btn-secondary btn-eliminar-clase" onclick="eliminarClase({{$clase->id}});">Eliminar</a>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$clases->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>