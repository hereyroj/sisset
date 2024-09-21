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
            @foreach($carrocerias as $carroceria)
            <tr @if($carroceria->trashed()) class="danger" @endif>
                <td>
                    {{$carroceria->name}}
                </td>
                <td>{{$carroceria->created_at}}</td>
                <td>{{$carroceria->updated_at}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-carroceria" onclick="editarCarroceria({{$carroceria->id}});">Editar</button>                    @if($carroceria->trashed())
                        <button type="button" class="btn btn-secondary btn-activar-carroceria" onclick="activarCarroceria({{$carroceria->id}});">Activar</button>                    @else
                        <button type="button" class="btn btn-secondary btn-eliminar-carroceria" onclick="eliminarCarroceria({{$carroceria->id}});">Eliminar</button>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$carrocerias->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>