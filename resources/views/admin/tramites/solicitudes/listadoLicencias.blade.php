@foreach($licencias as $licencia)
    <tr>
        <td><button class="btn btn-secondary" onclick="editarLicencia({{$licencia->id}});"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
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