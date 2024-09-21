<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Grupo</th>
                <th>Requiere placa</th>
                <th>Requiere sustrato</th>
                <th>Tipo sustrato</th>
                <th>Solicita carpeta</th>
                <th>Requerimientos</th>
                <th>CUPL</th>
                <th>Ministerio</th>
                <th>Entidad</th>
                @if(Defender::hasRole('Administrador'))
                <th>Acción</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($tramites as $tramite)
            <tr @if($tramite->trashed()) class="danger" @endif>
                <td>{{$tramite->name}}</td>
                <td>
                    @foreach ($tramite->hasGrupos as $grupo)
                    <span class="badge badge-pill badge-primary">{{$grupo->name}}</span> 
                    @endforeach
                </td>
                <td>{{$tramite->requiere_placa}}</td>
                <td>{{$tramite->requiere_sustrato}}</td>
                @if($tramite->hasTipoSustrato != null)
                <td>{{$tramite->hasTipoSustrato->name}}</td>
                @else
                <td></td>
                @endif
                <td>{{$tramite->solicita_carpeta}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="verRequerimientos({{$tramite->id}})">Ver</td>
                <td>{{$tramite->cupl}}</td>
                <td>{{$tramite->ministerio}}</td>
                <td>{{$tramite->entidad}}</td>
                @if(Defender::hasRole('Administrador'))
                    <td>
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-secondary btn-editar-tramite" onclick="editarTramite({{$tramite->id}});">Editar</button>                @if($tramite->trashed())
                    <button type="button" class="btn btn-secondary btn-activar-tramite" onclick="activarTramite({{$tramite->id}});">Activar</button>                @else
                    <button type="button" class="btn btn-secondary btn-eliminar-tramite" onclick="eliminarTramite({{$tramite->id}});">Eliminar</button>                @endif
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tramites->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>