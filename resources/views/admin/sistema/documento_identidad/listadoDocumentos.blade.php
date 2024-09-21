<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Requiere número</th>
            <th>Creado el</th>
            <th>Actualizado el</th>
            @if(Defender::hasRole('Administrador'))
                <th>Acción</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($documentos as $documento)
            <tr @if($documento->trashed()) class="danger" @endif>
                <td>{{$documento->name}}</td>
                <th>{{$documento->requiere_numero}}</th>
                <td>{{$documento->created_at}}</td>
                <td>{{$documento->updated_at}}</td>
                @if(Defender::hasRole('Administrador'))
                    <td>
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-secondary btn-editar-documento" onclick="editarDocumento({{$documento->id}})">Editar</button>
                            @if($documento->trashed())
                                <button type="button" class="btn btn-secondary btn-activar-documento" onclick="activarDocumento({{$documento->id}})">Activar</button>
                            @else
                                <button type="button" class="btn btn-secondary btn-eliminar-documento" onclick="eliminarDocumento({{$documento->id}})">Eliminar</button>
                            @endif
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>