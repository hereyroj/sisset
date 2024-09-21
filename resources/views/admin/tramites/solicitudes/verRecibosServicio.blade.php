<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>CUPL</th>
                <th>Número CUPL</th>
                <th>WEBSERVICES</th>
                <th>Número WEBSERVICES</th>
                <th>CONSIGNACIÓN</th>
                <th>Número CONSIGNACIÓN</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recibos as $recibo)
            <tr>
            <tr>
                <td>{{$recibo->created_at}}</td>
                <td>@if($recibo->cupl != null)<a href="{{url('admin/tramites/solicitudes/verCUPL/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                <td>{{$recibo->numero_cupl}}</td>
                <td>@if($recibo->webservices != null)<a href="{{url('admin/tramites/solicitudes/verSINTRAT/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                <td>{{$recibo->numero_sintrat}}</td>
                <td>@if($recibo->consignacion != null)<a href="{{url('admin/tramites/solicitudes/verCONSIGNACION/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                <td>{{$recibo->numero_consignacion}}</td>
                <td>{{$recibo->observacion}}</td>
            </tr>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>