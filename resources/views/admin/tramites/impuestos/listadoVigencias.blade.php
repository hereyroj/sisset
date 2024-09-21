<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Impuesto público</th>
            <th>Derechos entidad</th>
            <th>Cant. Meses Mora</th>
            <th>Mora Mensual</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($vigencias as $vigencia)
            <tr>
                <td>{{$vigencia->vigencia}}</td>
                <td>{{$vigencia->impuesto_publico}}x1000</td>
                <td>${{number_format($vigencia->derechos_entidad, 0, ',','.')}}</td>
                <td>{{$vigencia->cantidad_meses_intereses}}</td>
                <td>
                    @foreach($vigencia->hasMeses as $mes)
                        <span class="label label-primary" style="letter-spacing: 2px;">{{$mes->nombre}} / {{$mes->pivot->porcentaje_interes}}%</span>
                    @endforeach
                </td>
                <td><button type="button" class="btn btn-secondary" onclick="editarVigencia({{$vigencia->id}})">Editar</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>