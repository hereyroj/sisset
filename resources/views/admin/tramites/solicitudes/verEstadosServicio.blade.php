<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Observación</th>
                <th>Realizado por</th>
                <th>Fecha y hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $estado)
            <tr>
                <td>{{$estado->name}}</td>
                <td>{{$estado->pivot->observacion}}</td>
                <td>{{\App\User::find($estado->pivot->funcionario_id)->first()->name}}</td>
                <td>{{$estado->pivot->created_at}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>