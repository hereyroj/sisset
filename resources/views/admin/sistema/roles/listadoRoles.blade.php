<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Permisos</th>
                @if(Defender::hasRole('Administrador'))
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $rol)
            <tr>
                <td>{{$rol->name}}</td>
                <td>
                    @foreach($rol->hasPermisos as $permiso)
                    <span class="badge badge-pill badge-primary">{{$permiso->name}}</span> @endforeach
                </td>
                <td>
                    @if(Defender::hasRole('Administrador'))
                    <button type="button" class="btn btn-secondary" onclick="editarRol({{$rol->id}})">Editar</button> @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$roles->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>