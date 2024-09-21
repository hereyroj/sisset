<table class="table table-striped">
    <thead>
    <tr>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Número de acuerdo</th>
        <th>Comparendos</th>
        <th>Valor total</th>
        <th>Pago inicial</th>
        <th>Cuotas</th>
        <th>Estado</th>
        <th>Deudor</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($acuerdosPagos as $acuerdoPago)
        <tr>
            <td>{{$acuerdoPago->getEstado()}}</td>
            <td>{{$acuerdoPago->fecha_acuerdo}}</td>
            <td>{{$acuerdoPago->numero_acuerdo}}</td>
            <td>
                @foreach($acuerdoPago->hasComparendos as $comparendo)
                    <span class="badge badge-pill badge-primary">{{$comparendo->numero}}</span>
                @endforeach
            </td>
            <td>{{$acuerdoPago->valor_total}}</td>
            <td>{{$acuerdoPago->pago_inicial}}</td>
            <td><button type="button" class="btn btn-success" onclick="verCuotas({{$acuerdoPago->id}})">{{$acuerdoPago->cuotas}}</button></td>
            <td>{{$acuerdoPago->getEstado()}}</td>
            <td><button type="button" class="btn btn-secondary" onclick="verDeudor({{$acuerdoPago->id}})">Ver</button></td>
            <td>
                <button type="button" class="btn btn-secondary" onclick="editarAcuerdoPago({{$acuerdoPago->id}})">Editar</button>
                <button type="button" class="btn btn-danger" onclick="anular({{$acuerdoPago->id}})">Anular</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>