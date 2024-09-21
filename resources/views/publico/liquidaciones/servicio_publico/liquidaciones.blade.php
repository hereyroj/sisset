<div class="table-responsive">
    <table class="table table-striped table-hover">
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
        @foreach($vehiculo->hasLiquidaciones as $liquidacion)
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
                    <a href="{{url('servicios/liquidaciones/servicioPublico/imprimirLiquidacion/'.$liquidacion->id)}}" class="btn btn-secondary">Imprimir</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>