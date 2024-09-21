<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vigencia</th>
                <th>Nombre</th>
                <th>Tipo batería</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bateriasGrupos as $bateriaGrupo)
            <tr>
                <td>{{$bateriaGrupo->vigencia}}</td>
                <td>{{$bateriaGrupo->name}}</td>
                <td>{{$bateriaGrupo->hasTipoBateria->name}}</td>
                <td>{{$bateriaGrupo->desde}}</td>
                <td>{{$bateriaGrupo->hasta}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="editarGrupoBateria({{$bateriaGrupo->id}});">Editar</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>