<table class="table table-striped">
    <thead>
    <tr>
        <th>Tipo de sustrato</th>
        <th>Número</th>
        <th>Fecha anulación</th>
        <th>Motivo anulación</th>
        <th>Observación anulación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sustratos as $sustrato)
        <tr>
            <td>{{$sustrato->hasTipoSustrato->name}}</td>
            <td>{{$sustrato->numero}}</td>
            <td>{{$sustrato->hasAnulacion->created_at->format('Y-m-d')}}</td>
            <td>{{$sustrato->hasAnulacion->hasMotivo->name}}</td>
            <td>{{$sustrato->hasAnulacion->observacion}}</td>
        </tr>
    @endforeach
    </tbody>
</table>