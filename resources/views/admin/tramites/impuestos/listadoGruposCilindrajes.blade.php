<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vigencia</th>
                <th>Nombre</th>
                <th>Clase vehículo</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cilindrajesGrupos as $cilindrajeGrupo)
            <tr>
                <td>{{$cilindrajeGrupo->vigencia}}</td>
                <td>{{$cilindrajeGrupo->name}}</td>
                <td>{{$cilindrajeGrupo->hasVehiculoClase->name}}</td>
                <td>{{$cilindrajeGrupo->desde}}</td>
                <td>{{$cilindrajeGrupo->hasta}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="editarGrupoCilindraje({{$cilindrajeGrupo->id}});">Editar</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>