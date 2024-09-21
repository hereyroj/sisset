<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <th>Fecha</th>
        <th>Usuario que autorizó</th>
        <th>Número certificado RUNT</th>
        <th>Número Acto Administrativo</th>
        </thead>
        <tbody>
        <tr>
            <td>{{$cancelacion->fecha_cancelacion}}</td>
            <td>{{$cancelacion->nombre_funcionario_autoriza}}</td>
            <td>{{$cancelacion->nro_certificado_runt}}</td>
            <td>{{$cancelacion->nro_acto_administrativo}}</td>
        </tr>
        </tbody>
    </table>
</div>