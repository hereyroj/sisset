<table id="historialCarpeta" class="table table-striped">
    <thead>
        <tr>
            <th>Fecha solicitud</th>
            <th>Fecha aprobación</th>
            <th>Autorizada por:</th>
            <th>Entregada por:</th>
            <th>Entregada a:</th>
            <th>Fecha de entrega:</th>
            <th>Fecha de retorno:</th>
        </tr>
    </thead>
    <tbody>
    @foreach($historiales as $historial)
        <tr>
            <td>{{$historial->created_at}}</td>
            <td>{{$historial->hasCarpetaPrestada->created_at}}</td>
            <td>{{$historial->hasCarpetaPrestada->hasFuncionarioAutoriza->name}}</td>
            @if($historial->hasCarpetaPrestada->hasFuncionarioEntrega != null)
                <td>{{$historial->hasCarpetaPrestada->hasFuncionarioEntrega->name}}</td>
            @else
                <td></td>
            @endif
            @if($historial->hasCarpetaPrestada->hasFuncionarioRecibe != null)
                <td>{{$historial->hasCarpetaPrestada->hasFuncionarioRecibe->name}}</td>
            @else
                <td></td>
            @endif
            <td>{{$historial->hasCarpetaPrestada->fecha_entrega}}</td>
            <td>{{$historial->hasCarpetaPrestada->fecha_devolucion}}</td>
        </tr>
    @endforeach
    </tbody>
</table>