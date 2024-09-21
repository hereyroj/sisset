<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="actualizarLiquidaciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaLiquidacion({{$id}});">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Código</th>
            <th>Vigencia</th>
            <th>Fecha expedición</th>
            <th>Fecha vencimiento</th>
            <th>Avaluo</th>
            <th>Impuesto</th>
            <th>Descuentos</th>
            <th>Mora</th>
            <th>Total</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($liquidaciones as $liquidacion)
            <tr>
                <td>
                    {{$liquidacion->codigo}}
                </td>
                <td>
                    {{$liquidacion->hasVigencia->vigencia}}
                </td>
                <td>
                    {{$liquidacion->created_at}}
                </td>
                <td>
                    {{$liquidacion->fecha_vencimiento}}
                </td>
                <td>
                    ${{number_format($liquidacion->valor_avaluo, 0, ',','.')}}
                </td>
                <td>
                    ${{number_format($liquidacion->valor_impuesto, 0, ',','.')}}
                </td>
                <td>
                    ${{number_format($liquidacion->valor_descuento_total, 0, ',','.')}}
                </td>
                <td>
                    ${{number_format($liquidacion->valor_mora_total, 0, ',','.')}}
                </td>
                <td>
                    ${{number_format($liquidacion->valor_total, 0, ',','.')}}
                </td>
                <td>
                    <a href="{{url('admin/liquidaciones/vehiculos/imprimirLiquidacion/'.$liquidacion->id)}}" class="btn btn-secondary" title="Imprimir"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>
                    @if($liquidacion->hasPago == null)
                    <button type="button" class="btn btn-success" onclick="registrarPago({{$liquidacion->id}})" title="Pagar"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></button>
                    <button type="button" class="btn btn-primary" onclick="reCalcularLiquidacion({{$liquidacion->id}})" title="Re-calcular valores"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span></button>
                    <button type="button" class="btn btn-danger" onclick="anularLiquidacion({{$liquidacion->id}});" title="Anular"><i class="fas fa-times"></i></button>
                    @elseif($liquidacion->hasPago != null)
                    <button type="button" class="btn btn-secondary" onclick="verPago({{$liquidacion->hasPago->id}})" title="Ver pago"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>