<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Fecha asignación</th>
            <th>Nombre funcionario</th>
            <th>Turno</th>
            <th>Ventanilla</th>
        </tr>
        </thead>
        <tbody>
        @foreach($asignaciones as $asignacion)
            <tr>
                <td>{{$asignacion->created_at}}</td>
                <td>{{\App\User::find($asignacion->funcionario_id)->first()->name}}</td>
                <td>{{\App\tramite_solicitud_turno::find($asignacion->tramite_solicitud_turno_id)->first()->turno}}</td>
                <td>{{\App\ventanilla::find($asignacion->ventanilla_id)->first()->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>