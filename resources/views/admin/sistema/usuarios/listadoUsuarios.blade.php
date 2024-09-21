<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Dependencia</th>
                <th>Celular</th>
                <th>Roles</th>
                <th>Permisos agregados</th>
                @if(Defender::hasRole('Administrador'))
                <th>Acción</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr @if($usuario->trashed()) class="danger" @endif>
                <td style="text-align:center; width: 80px; height: 80px;">
                    <img class="media-object" src="{{asset($usuario->avatar)}}" alt="{{$usuario->name}}" style="width: 60px; height: 60px; border-radius: 100px; vertical-align: middle;">
                </td>
                <td>{{$usuario->name}}</td>
                <td>{{$usuario->email}}</td>
                <td>
                    {{$usuario['hasDependencia']['name']}}
                </td>
                <td>{{$usuario->celphone}}</td>
                <td>
                    @foreach($usuario->hasRoles as $rol)
                    <span class="badge badge-pill badge-primary">{{$rol->name}}</span><br> @endforeach
                </td>
                <td>
                    @foreach($usuario->couldHavePermisosAgregados as $permiso)
                    <span class="badge badge-pill badge-primary">
                            {{$permiso->readable_name}}
                            @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !=null && $usuario->getPermisoAgregado($permiso->id)->pivot->value == false)
                                | Desactivado | Expira el: {{$usuario->getPermisoAgregado($permiso->id)->pivot->expires}}
                            @elseif($usuario->getPermisoAgregado($permiso->id)->pivot->expires !=null)
                                | Expira el: {{$usuario->getPermisoAgregado($permiso->id)->pivot->expires}}
                            @elseif($usuario->getPermisoAgregado($permiso->id)->pivot->value == false)
                                | Desactivado
                            @elseif($usuario->getPermisoAgregado($permiso->id)->pivot->value == true)
                                | Permanente
                            @endif
                        </span><br> @endforeach
                </td>
                @if(Defender::hasRole('Administrador'))
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button onclick="verPerfil({{$usuario->id}});" class="btn btn-secondary">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Perfil
                            </button>&nbsp;&nbsp;
                        <button onclick="editarUsuario({{$usuario->id}});" class="btn btn-secondary">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar
                            </button>&nbsp;&nbsp; @if($usuario->lock_session != 'no')
                        <button class="btn btn-secondary" onclick="habilitarUsuario({{$usuario->id}});">
                                    <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Habilitar
                                </button>&nbsp;&nbsp; @else
                        <button class="btn btn-secondary" onclick="deshabilitarUsuario({{$usuario->id}});">
                                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Deshabilitar
                                </button>&nbsp;&nbsp; @endif @if($usuario->trashed())
                        <button class="btn btn-secondary" onclick="restaurarUsuario({{$usuario->id}});">
                                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Restaurar
                                </button>&nbsp;&nbsp; @else
                        <button class="btn btn-secondary" onclick="eliminarUsuario({{$usuario->id}});">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar
                                </button>&nbsp;&nbsp; @endif
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$usuarios->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>