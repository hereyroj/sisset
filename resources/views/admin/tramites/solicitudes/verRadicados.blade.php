<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Consecutivo</th>
            <th>Archivos</th>
            <th>Radicado por</th>
            <th>Fecha radicación</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tramite->hasRadicados as $radicado)
            <tr>
                <td>{{$radicado->vigencia}}</td>
                <td>{{$radicado->consecutivo}}</td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="obtenerArchivos({{$radicado->id}})">Obtener</button>
                </td>
                <td>{{$radicado->hasFuncionario->name}}</td>
                <td>{{$radicado->created_at}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>