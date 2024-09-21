<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Motivo</th>
            <th>Observación</th>
            <th>Fecha y hora</th>
            <th>Funcionario</th>
        </tr>
        </thead>
        <tbody>
        @foreach($liberaciones as $liberacion)
            <tr>
                <td>{{$liberacion->hasMotivo->name}}</td>
                <td>{{$liberacion->observacion}}</td>
                <td>{{$liberacion->created_at}}</td>
                <td>{{$liberacion->hasFuncionario->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>