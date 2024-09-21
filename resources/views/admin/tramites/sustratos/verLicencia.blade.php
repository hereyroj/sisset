<p><strong>Categorías:</strong><br>
    @foreach ($licencia->hasCategorias as $categoria)
    <span class="badge badge-pill badge-primary">{{$categoria->name}}</span>
    @endforeach
</p>
<p><strong>Tipo documento:</strong><br>{{$licencia->hasTurno->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name}}</p>
<p><strong>Número documento:</strong><br>{{$licencia->hasTurno->hasUsuarioSolicitante->numero_documento}}</p>
<p><strong>Nombre:</strong><br>{{$licencia->hasTurno->hasUsuarioSolicitante->nombre_usuario}}</p>
<p><strong>Registrada por:</strong><br>{{$licencia->hasFuncionario->name}}</p>