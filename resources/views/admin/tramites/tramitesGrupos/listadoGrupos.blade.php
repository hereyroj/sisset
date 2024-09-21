<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Tramites</th>
                @if(Defender::hasRole('Administrador'))
                <th>Acción</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($grupos as $grupo)
            <tr>
                <td>{{$grupo->name}}</td>
                <td>{{$grupo->code}}</td>
                <td>
                    @foreach ($grupo->hasTramites as $tramite)
                    <span class="badge badge-pill badge-primary">{{$tramite->name}}</span> @endforeach
                </td>
                @if(Defender::hasRole('Administrador'))
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary" onclick="editarGrupo({{$grupo->id}});">Editar</button>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$grupos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>