<style>
    .tablaPermisos {
        padding: 0;
    }
</style>
<form enctype='multipart/form-data'>
    <input type="hidden" name="id" value="{{$usuario->id}}">
    <div class="col-md-8">
        <div class="form-group" style="text-align:center;">
            <img src="{{asset($usuario->avatar)}}" style="width: 300px; height: 300px; border-radius: 1000px; text-align:center;"/>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="avatar" id="avatar">
            <label class="custom-file-label" for="avatar">Cambiar/Seleccionar Avatar</label>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nombre</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ $usuario->name }}">
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Correo</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ $usuario->email }}">
        </div>
        <div class="form-group">
            @if($usuario->hasDependencia != '')
                <label for="dependencia" class="control-label">Dependencia</label>
                {!! Form::select('dependencia', $dependencias, $usuario->hasDependencia->id, ['class' => 'form-control']) !!}
            @else
                <label for="dependencia" class="control-label alert-danger">Dependencia</label>
                {!! Form::select('dependencia', $dependencias, null, ['class' => 'form-control']) !!}
            @endif
        </div>
        <div class="form-group">
            <label class="control-label">Agente de Tránsito</label>
            @if($usuario->hasAgente()->count() == 0)
            <input type="button" class="form-control btn btn-primary" value="Convertir en Agente" onclick="convertirEnAgente({{$usuario->id}});">
            @else
                <input type="button" class="btn btn-success btn-block" value="Ver Agente" onclick="verAgente({{$usuario->id}});">
                <input type="button" class="btn btn-danger btn-block" value="Desvincular Agente" onclick="desvincularAgente({{$usuario->id}});">
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label for="roles" class="control-label">Roles</label>
        @foreach($roles as $rol)
            <div class="col-md-12  checkbox">
                @if(Auth::user()->hasRole('Administrador'))
                    <label for="roles">
                        <input type="checkbox" name="roles[]" id="{{$rol->name}}" value="{{$rol->id}}" @if($usuario->hasRole($rol->name) == true) checked @endif>
                        {{$rol->name}}
                    </label>
                @else
                    <label for="roles">
                        @if(auth()->user()->hasRole($rol->name))
                            <i class="fas fa-search"></i>
                        @endif
                        {{$rol->name}}
                    </label>
                @endif
            </div>
        @endforeach
    </div>

    <div class="table-responsive">
        <table class="table table-bordered ">
            <thead>
            <tr>
                <th rowspan="2"><input type="checkbox" id="asignados"> Asignado</th>
                <th rowspan="2">Permisos</th>
                <th rowspan="2"><input type="checkbox" id="inactivos"> Inactivo</th>
                <th rowspan="1" colspan="3">Establecer por tiempo definido</th>
            </tr>
            <tr>
                <th rowspan="1"><input type="checkbox" id="temporales"> Establecer</th>
                <th rowspan="1">Fecha terminación</th>
                <th rowspan="1">Hora terminación</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permisos as $permiso)
                @if($usuario->roleHasPermission($permiso->name) || $usuario->havePermisoAgregado($permiso->id) > 0)
                    <tr @if($usuario->puedeHacerlo($permiso->name)) class="success" @endif>
                        <td class="asignados">
                            <input type="checkbox" name="permisos[]" id="{{$permiso->id}}" value="{{$permiso->name}}"/>
                        </td>
                        <td>
                            {{$permiso->readable_name}}
                        </td>
                        @if($usuario->havePermisoAgregado($permiso->id) > 0)
                            <td class="inactivos">
                                <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}" @if($usuario->getPermisoAgregado($permiso->id)->pivot->value == false) checked @endif/>
                            </td>
                            <td class="temporales">
                                <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}" @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null) checked @endif/>
                            </td>
                            <td>
                                <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}" @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null) value="{{date_format($usuario->getPermisoAgregado($permiso->id)->pivot->expires, 'Y-m-d')}}" @endif/>
                            </td>
                            <td>
                                <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}" @if($usuario->getPermisoAgregado($permiso->id)->pivot->expires !== null) value="{{date_format($usuario->getPermisoAgregado($permiso->id)->pivot->expires, 'H:i')}}" @endif/>
                            </td>
                        @else
                            <td class="inactivos">
                                <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                            </td>
                            <td class="temporales">
                                <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                            </td>
                            <td>
                                <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}"/>
                            </td>
                            <td>
                                <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}"/>
                            </td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td class="asignados">
                            <input type="checkbox" name="permisos[]" id="{{$permiso->id}}" value="{{$permiso->name}}"/>
                        </td>
                        <td>
                            {{$permiso->readable_name}}
                        </td>
                        <td>
                            <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                        </td>
                        <td>
                            <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                        </td>
                        <td>
                            <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}"/>
                        </td>
                        <td>
                            <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}"/>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/usuarios/editarUsuario.js')}}"></script>