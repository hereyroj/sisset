<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Nombre</th>
            <th>Clase vehículo</th>
            <th>Marca vehículo</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clasesGrupos as $claseGrupo)
            <tr>
                <td>{{$claseGrupo->vigencia}}</td>
                <td>{{$claseGrupo->name}}</td>
                <td>{{$claseGrupo->hasVehiculoClase->name}}</td>
                <td>{{$claseGrupo->hasVehiculoMarca->name}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="editarGrupoClase({{$claseGrupo->id}});">Editar</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>