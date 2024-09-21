<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Grupos asignados</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ventanillas as $ventanilla)
            <tr>
                <td>
                    {{$ventanilla->name}}
                </td>
                <td>
                    {{$ventanilla->codigo}}
                </td>
                <td>
                    @foreach($ventanilla->hasTramitesGruposAsignados as $tramiteGrupo)
                        <span class="badge badge-pill badge-primary">{{$tramiteGrupo->name}} - {{$tramiteGrupo->pivot->prioridad}}</span>
                    @endforeach
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarVentanilla({{$ventanilla->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>