<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Servicios (cant)</th>
            <th>Grupo Tramites</th>
            <th>Tramites</th>
            <th>Estado</th>
            <th>Turno activo</th>
            <th>Turnos</th>
            <th>Servicios y licencias</th>
            <th>Asignaciones</th>
            <th>Radicados</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
            
        @foreach($tramites as $tramite)
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
                @if($tramite->hasTurnoActivo() != null)
                    <td>
                        <button class="btn btn-warning btn-block" onclick="verTurno({{$tramite->hasTurnoActivo()->id}});">{{$tramite->hasTurnoActivo()->turno}}</button>
                    </td>
                @else
                    <td></td>
                @endif
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
                <td>
                    <button type="button" class="btn btn-secondary" onclick="nuevoTurno({{$tramite->id}});">Nuevo turno</button>
                    @if($tramite->canEdit())
                        <button type="button" class="btn btn-secondary" onclick="editarSolicitud({{$tramite->id}});">Editar tramite</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="text-center">
    {{$tramites->links('vendor.pagination.bootstrap-4')}}
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/listadoTramites.js')}}"></script>