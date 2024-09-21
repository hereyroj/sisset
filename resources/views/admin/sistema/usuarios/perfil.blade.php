<form>
    <div class="form-group" style="text-align:center;">
        <img src="{{asset($usuario->avatar)}}" style="width: 300px; height: 300px; border-radius: 1000px; text-align:center;"/>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label class="control-label">Nombre:</label><br>
            <label class="control-label" style="font-weight: normal">{{$usuario->name }}</label>
        </div>
        <div class="form-group">
            <label class="control-label">Correo:</label><br>
            <label class="control-label" style="font-weight: normal">{{ $usuario->email }}</label>
        </div>
        @if($usuario->hasAgente()->count() > 0)
            <div class="form-group">
                <label class="control-label">Agente de Tránsito</label>
                <input type="button" class="form-control btn btn-success" value="Ver Agente" onclick="verAgente({{$usuario->id}});">
            </div>
        @endif

    </div>
    <div class="col-md-4">
        <label class="control-label">Roles:</label>
        @foreach($usuario->roles as $rol)
            <div class="col-md-12">
                <label class="control-label" style="font-weight: normal">{{$rol->name}}</label>
            </div>
        @endforeach
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Activo</th>
                <th>Permisos individuales</th>
                <th>Desactivado</th>
                <th>Temporal</th>
                <th>Fecha terminación</th>
                <th>Hora terminación</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permisos as $permiso)
                @if($usuario->roleHasPermission($permiso->name) || $usuario->havePermisoAgregado($permiso->id) > 0)
                    <tr class="success">
                        <td>
                            <input type="checkbox" disabled/>
                        </td>
                        <td>
                            {{$permiso->readable_name}}
                        </td>
                        @if($usuario->havePermisoAgregado($permiso->id) > 0)
                            <td>
                                @if($usuario->getPermisoAgregado($permiso->id)->pivot->value == false)
                                    <input type="checkbox" checked disabled/>
                                @else
                                    <input type="checkbox" disabled/>
                                @endif
                            </td>
                            <td>
                                @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null)
                                    <input type="checkbox" checked disabled/>
                                @else
                                    <input type="checkbox" disabled/>
                                @endif
                            </td>
                            <td>
                                @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null)
                                    {{date_format($usuario->getPermisoAgregado($permiso->id)->pivot->expires, 'Y-m-d')}}
                                @else
                                    <label class="control-label">
                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                    </label>
                                @endif
                            </td>
                            <td>
                                @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null)
                                    {{date_format($usuario->getPermisoAgregado($permiso->id)->pivot->expires, 'H:i')}}
                                @else
                                    <label class="control-label">
                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                    </label>
                                @endif
                            </td>
                        @else
                            <td>
                                <input type="checkbox" disabled/>
                            </td>
                            <td>
                                <input type="checkbox" disabled/>
                            </td>
                            <td>
                                <label class="control-label"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></label>
                            </td>
                            <td>
                                <label class="control-label"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></label>
                            </td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td>
                            <input type="checkbox" disabled/>
                        </td>
                        <td>
                            {{$permiso->name}}
                        </td>
                        <td>
                            <input type="checkbox" disabled/>
                        </td>
                        <td>
                            <input type="checkbox" disabled/>
                        </td>
                        <td>
                            <label class="control-label"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></label>
                        </td>
                        <td>
                            <label class="control-label"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></label>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/usuarios/perfil.js')}}"></script>