<table class="table table-striped">
    <thead>
    <tr>
        <th>Acciones</th>
        <th>Sustrato</th>
        <th>Categorías</th>
        <th>Registrada por</th>
        <th>Registrada el</th>
        <th>Tipo documento conductor</th>
        <th>Número documento conductor</th>
        <th>Nombre conductor</th>
    </tr>
    </thead>
    <tbody id="licenciasSolicitud">
    @foreach($licencias as $licencia)
        <tr>
            <td><button class="btn btn-secondary" onclick="editarLicencia({{$licencia->id}});"><i class="fas fa-pencil-alt"></i></button></td>
            <td>{{$licencia->hasSustrato->numero}}</td>
            <td>
                @foreach ($licencia->hasCategorias as $categoria)
                <span class="badge badge-pill badge-primary">{{$categoria->name}}</span>
                @endforeach
            </td>
            <td>{{$licencia->hasFuncionario->name}}</td>
            <td>{{$licencia->created_at}}</td>
            <td>{{$licencia->hasTurno->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name}}</td>
            <td>{{$licencia->hasTurno->hasUsuarioSolicitante->numero_documento}}</td>
            <td>{{$licencia->hasTurno->hasUsuarioSolicitante->nombre_usuario}}</td>
        </tr>
    @endforeach
</table>