<table class="table table-striped">
    <thead>
    <tr>
        <th>Estado</th>
        <th>Valor</th>
        <th>Fecha vencimiento</th>
        <th>Consignación</th>
        <th>Factura WEBSERVICES</th>
        <th>Fecha pago</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cuotas as $cuota)
        <tr>
            <td>{{$cuota->getEstado()}}</td>
            <td>{{$cuota->valor}}</td>
            <td>{{$cuota->fecha_vencimiento}}</td>
            <td>
                @if($cuota->consignacion_factura != null)
                    <a href="{{url('admin/inspeccion/AcuerdosPagos/obtenerConsignacionCuota/'.$cuota->id)}}" class="btn btn-secondary">Ver</a>
                @endif
            </td>
            <td>
                @if($cuota->factura_sintrat != null)
                    <a href="{{url('admin/inspeccion/AcuerdosPagos/obtenerFacturaCuota/'.$cuota->id)}}" class="btn btn-secondary">Ver</a>
                @endif
            </td>
            <td>{{$cuota->fecha_pago}}</td>
            <td>
                @if($cuota->fecha_pago == null)
                    <button type="button" class="btn btn-success" onclick="pagarCuota({{$cuota->id}})">Pagar</button>
                @else
                    <button type="button" class="btn btn-primary" onclick="editarPagoCuota({{$cuota->id}})">Editar pago</button>
                @endif
                <button type="button" class="btn btn-secondary" onclick="editarCuota({{$cuota->id}})">Editar</button>
                <button type="button" class="btn btn-danger" onclick="anularCuota({{$cuota->id}})">Anular</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript" src="{{asset('js/inspeccion/acuerdos_pagos/cuotas.js')}}"></script>