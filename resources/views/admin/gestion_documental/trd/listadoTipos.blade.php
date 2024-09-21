<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sub serie</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>
                    {{$tipo->hasSubSerie->name}}
                </td>
                <td>
                    {{$tipo->name}}
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarTipo({{$tipo->id}});">Editar</button>
                    <button type="button" class="btn btn-secondary" onclick="eliminarTipo({{$tipo->id}});">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tipos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>