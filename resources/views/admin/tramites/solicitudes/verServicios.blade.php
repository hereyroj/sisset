<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Placa</th>
            <th>Vehículo Servicio</th>
            <th>Vehículo Clase</th>
            <th>Fecha y hora</th>
            <th>Tramites</th>
            <th>Estados</th>
            <th>Carpetas</th>
            <th>Recibos</th>
            <th>Finalización</th>
        </tr>
        </thead>
        <tbody>
        @foreach($solicitud->hasServicios as $servicio)
            <tr>
                <td>{{$servicio->placa}}</td>
                <td>{{$servicio->hasVehiculoServicio->name}}</td>
                <td>{{$servicio->hasVehiculoClase->name}}</td>
                <td>{{$servicio->created_at}}</td>
                <td>
                    @foreach($servicio->hasTramites as $tramite)
                        <span class="badge badge-pill badge-primary">{{$tramite->name}}</span>
                    @endforeach
                </td>
                <td><button type="button" class="btn btn-secondary" onclick="verEstadosServicio({{$servicio->id}})">Ver</button></td>
                <td><button type="button" class="btn btn-secondary" onclick="verCarpetasServicio({{$servicio->id}})">Ver</button></td>
                <td><button type="button" class="btn btn-secondary" onclick="verRecibosServicio({{$servicio->id}})">Ver</button></td>
                <td><button type="button" class="btn btn-secondary" onclick="verFinalizacionServicio({{$servicio->id}})">Ver</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/verServicios.js')}}"></script>