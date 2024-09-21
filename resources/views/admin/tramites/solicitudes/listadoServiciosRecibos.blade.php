<div id="recibosServicio">
    <div class="cabecera-tabla">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" onclick="obtenerRecibosServicioModal({{$id}});">
                <i class="fas fa-sync"></i> Actualizar
            </button>
            <button type="button" onclick="subirRecibos({{$id}});" class="btn btn-info">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Subir
            </button>
        </div>
    </div>
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
                    <td>{{$recibo->created_at}}</td>
                    <td>@if($recibo->cupl != null)<a href="{{url('admin/tramites/solicitudes/verCUPL/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                    <td>{{$recibo->numero_cupl}}</td>
                    <td>@if($recibo->webservices != null)<a href="{{url('admin/tramites/solicitudes/verSINTRAT/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                    <td>{{$recibo->numero_sintrat}}</td>
                    <td>@if($recibo->consignacion != null)<a href="{{url('admin/tramites/solicitudes/verCONSIGNACION/'.$recibo->id)}}" class="btn btn-secondary btn-block">Ver</a>@endif</td>
                    <td>{{$recibo->numero_consignacion}}</td>
                    <td>{{$recibo->observacion}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/listadoServiciosRecibos.js')}}"></script>