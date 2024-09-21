<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Concepto</th>
            <th>Porcentaje</th>
            <th>Vigente desde</th>
            <th>Vigente hasta</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($descuentos as $descuento)
            <tr>
                <td>{{$descuento->hasVigencia->vigencia}}</td>
                <td>{{$descuento->concepto}}</td>
                <td>{{$descuento->porcentaje}}%</td>
                <td>{{$descuento->vigente_desde}}</td>
                <td>{{$descuento->vigente_hasta}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="editarDescuento({{$descuento->id}});">Editar</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>