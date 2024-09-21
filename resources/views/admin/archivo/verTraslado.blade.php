<table class="table table-striped">
    <thead>
    <th>Fecha</th>
    <th>Usuario que autorizó</th>
    <th>Departamento</th>
    <th>Municipio</th>
    </thead>
    <tbody>
    <tr>
        <td>{{$traslado->fecha_traslado}}</td>
        <td>{{$traslado->nombre_funcionario_autoriza}}</td>
        <td>{{$traslado->departamentoTraslado->name}}</td>
        <td>{{$traslado->municipioTraslado->name}}</td>
    </tr>
    </tbody>
</table>