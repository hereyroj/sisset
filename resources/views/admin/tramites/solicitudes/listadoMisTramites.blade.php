<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Servicios (cant)</th>
            <th>Tramite Grupo</th>
            <th>Tramite</th>
            <th>Estado</th>
            <th>Turnos</th>
            <th>Serviciosy licencias</th>
            <th>Asignaciones</th>
            <th>Radicados</th>
        </tr>
        </thead>
        <tbody>
        @foreach($misTramites as $tramite)
            <tr>
                <td style="text-align: center">{{$tramite->servicios}}</td>
                <td>{{$tramite->hasTramiteGrupo->name}}</td>
                <td>
                    @foreach ($tramite->hasTramites as $tramite2)
                        <span class="badge badge-pill badge-primary">{{$tramite2->name}}</span>
                        @if(!$loop->last)
                        <br>
                        @endif
                    @endforeach
                </td>
                <td>{{strtoupper($tramite->getEstadoSolicitud())}}</td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="verTurnos({{$tramite->id}});" style="display: block;margin: auto;">Ver</button>
                </td>
                <td>
                    @if($tramite->hasTramiteGrupo->name != 'LICENCIAS')
                        <button type="button" class="btn btn-secondary" onclick="verServicios({{$tramite->id}});" style="display: block;margin: auto;">Ver</button>
                    @else
                        <button type="button" class="btn btn-secondary" onclick="verLicencias({{$tramite->id}});" style="display: block;margin: auto;">Ver</button>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="verAsignaciones({{$tramite->id}});" style="display: block;margin: auto;">Ver</button>
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="verRadicados({{$tramite->id}});" style="display: block;margin: auto;">Ver</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="text-center">
    {{$misTramites->links('vendor.pagination.bootstrap-4')}}
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/listadoMisTramites.js')}}"></script>