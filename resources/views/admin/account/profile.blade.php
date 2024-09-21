@extends('layouts.dashboard') 
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Perfil - {{ auth()->user()->name }}  - {{Setting::get('empresa_sigla')}}</title>
@endsection
 
@section('styles')
<style>
    .tablaPermisos {
        padding: 0;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Perfil</div>
                <div class="card-body">
                    @if (Session::has('errors'))
                    <div class="alert alert-danger">
                        <h4>No se ha podido realizar la(s) modificacion(es) debido a los siguientes inconvenientes:</h4>
                        <ul>
                            @foreach (Session::get('errors') as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif @if(Session::has('terminado'))
                    <div class="alert alert-success">{{Session::get('terminado')}}</div>
                    @endif
                    <form id="formulario" class="form-horizontal" role="form" method="POST" action="{{ url('/admin/cuenta/actualizarPerfil') }}"
                        enctype='multipart/form-data'>
                        {{ csrf_field() }}
                        <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group" style="text-align:center;">
                                                <img src="{{asset(auth()->user()->avatar)}}" style="width: 300px; height: 300px; border-radius: 1000px; text-align:center;"
                                                />
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="avatar" id="avatar">
                                                <label class="custom-file-label" for="avatar"">Cambiar/Seleccionar Avatar</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="control-label">Nombre</label>
                                                <input id="name" type="text" class="form-control" name="name" value="{{ auth()->user()->name }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="control-label">Correo</label>
                                                <input id="email" type="email" class="form-control" name="email" value="{{ auth()->user()->email }}">
                                            </div>
                                            <div class="form-group">
                                                @if(auth()->user()->hasRole('Administrador'))
                                                <label for="dependencia" class="control-label">Dependencia</label> {!! Form::select('dependencia',
                                                $dependencias, auth()->user()->hasDependencia->id, ['class' => 'form-control']) !!} @else
                                                <label for="dependencia" class="control-label alert-danger">Dependencia</label>
                                                <input id="dependencia" type="text" class="form-control" name="dependencia" readonly value="{{ auth()->user()->hasDependencia->name }}">                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="cambiar-contraseña" class="control-label">Contraseña</label>
                                                <input type="button" class="btn btn-secondary" name="cambiar-contraseña" value="Cambiar" data-toggle="modal" data-target="#modal-change-password">
                                            </div>
                                            <div class="form-group">
                                                @if(Auth::user()->pin_code != null)
                                                <label for="cambiar-pin" class="control-label">PIN</label>
                                                <input type="button" class="btn btn-secondary" name="cambiar-pin" value="Cambiar" data-toggle="modal" data-target="#modal-change-pin">                                @else
                                                <label for="establecer-pin" class="control-label">PIN</label>
                                                <input type="button" class="btn btn-secondary" name="establecer-pin" value="Establecer" data-toggle="modal" data-target="#modal-set-pin">                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="activar2fa" class="control-label">Autencicación en dos pasos</label> @if(auth()->user()->google2fa_secret
                                                == null)
                                                <a href="/admin/cuenta/activar2fa" class="btn btn-secondary">Activar</a> @else
                                                <a href="#" onclick="event.preventDefault(); desactivar2fa();" class="btn btn-danger">Desactivar</a>                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="activarUf2" class="control-label">Autencicación con llave USB (U2F)</label> 
                                                @if(auth()->user()->hasU2f()->count() == 0)
                                                <a href="/webauthn/register" class="btn btn-secondary">Activar</a>
                                                @else
                                                <a href="/webauthn/register" class="btn btn-success">Registrar otro medio</a>
                                                <a href="#" onclick="event.preventDefault(); desactivarU2f();" class="btn btn-danger">Desactivar</a>   
                                                @endif
                                            </div>
                                        </div>        
            
                                    <div class="col-md-6">
                                        <label for="roles" class="control-label">Roles</label> @foreach($roles as $rol)
                                        <div class="col-md-12  checkbox">
                                            @if(Auth::user()->hasRole('Administrador'))
                                            <label for="roles">
                                                    <input type="checkbox" name="roles[]" id="{{$rol->name}}" value="{{$rol->id}}" @if(auth()->user()->hasRole($rol->name)) checked @endif> {{$rol->name}}
                                                </label> @else
                                            <label for="roles">
                                                @if(auth()->user()->hasRole($rol->name))
                                                    <i class="fas fa-search"></i>
                                                @endif
                                                    {{$rol->name}}
                                            </label> @endif
                                        </div>
                                        @endforeach
                                    </div>
                        </div>
                        

                        @is('Administrador')
                        <div class="col-md-12 tablaPermisos">
                            <div class="table-responsive">
                                <table class="table table-bordered">
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
                                        @foreach($permisos as $permiso) @if(Auth::user()->roleHasPermission($permiso->name) || Auth::user()->havePermisoAgregado($permiso->id)
                                        > 0)
                                        <tr @if(Auth::user()->puedeHacerlo($permiso->name)) class="success" @endif>
                                            <td class="asignados">
                                                <input type="checkbox" name="permisos[]" id="{{$permiso->id}}" value="{{$permiso->name}}" />
                                            </td>
                                            <td>
                                                {{$permiso->name}}
                                            </td>
                                            @if(Auth::user()->havePermisoAgregado($permiso->id) > 0)
                                            <td class="inactivos">
                                                <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}" @if(Auth::user()->getPermisoAgregado($permiso->id)->pivot->value
                                                == false) checked @endif/>
                                            </td>
                                            <td class="temporales">
                                                <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}" @if(Auth::user()->getPermisoAgregado($permiso->id)->pivot->expires
                                                !== null) checked @endif/>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}" @if(Auth::user()->getPermisoAgregado($permiso->id)->pivot->expires
                                                !== null) value="{{date_format(Auth::user()->getPermisoAgregado($permiso->id)->pivot->expires,
                                                'Y-m-d')}}" @endif/>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}" @if(Auth::user()->getPermisoAgregado($permiso->id)->pivot->expires
                                                !== null) value="{{date_format(Auth::user()->getPermisoAgregado($permiso->id)->pivot->expires,
                                                'H:i')}}" @endif/>
                                            </td>
                                            @else
                                            <td class="inactivos">
                                                <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}" />
                                            </td>
                                            <td class="temporales">
                                                <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}" />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}" />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}" />
                                            </td>
                                            @endif
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="asignados">
                                                <input type="checkbox" name="permisos[]" id="{{$permiso->id}}" value="{{$permiso->name}}" />
                                            </td>
                                            <td>
                                                {{$permiso->name}}
                                            </td>
                                            <td class="inactivos">
                                                <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}" />
                                            </td>
                                            <td class="temporales">
                                                <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}" />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}" />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}" />
                                            </td>
                                        </tr>
                                        @endif @endforeach
                                    </tbody>
                                </table>
                            </div>    
                        </div>
                        @endis

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                        Guardar cambios
                                    </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-change-password" tabindex="-1" role="dialog" id="modal-change-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                <h4 class="modal-title">Cambiar contraseña</h4>
            </div>
            <div class="modal-body modal-body-change-password">
                <form id="form-change-password" role="form" method="POST" action="{{ url('/admin/cuenta/cambiarPassword') }}" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="current-password" class="control-label">Contraseña actual</label>
                        <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Contraseña actual">
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="control-label">Confirmar nueva contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar nueva contraseña">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">Cambiar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">
                                Cerrar
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-set-pin" tabindex="-1" role="dialog" id="modal-set-pin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                <h4 class="modal-title">Establecer PIN</h4>
            </div>
            <div class="modal-body modal-body-set-pin">
                <form id="form-set-pin" role="form" method="POST" action="{{ url('/admin/cuenta/establecerPIN') }}" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="current-password" class="control-label">Contraseña actual</label>
                        <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Contraseña actual">
                    </div>
                    <div class="form-group">
                        <label for="pin" class="control-label">Nuevo PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" placeholder="Nuevo PIN">
                    </div>
                    <div class="form-group">
                        <label for="pin_confirmation" class="control-label">Confirmar PIN</label>
                        <input type="password" class="form-control" id="pin_confirmation" name="pin_confirmation" placeholder="Confirmar PIN">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">Establecer</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">
                                Cerrar
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-change-pin" tabindex="-1" role="dialog" id="modal-change-pin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cambiar PIN</h4>
            </div>
            <div class="modal-body modal-body-change-pin">
                <form id="form-change-pin" role="form" method="POST" action="{{ url('/admin/cuenta/cambiarPIN') }}" novalidate>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="current-pin" class="control-label">PIN actual</label>
                        <input type="password" class="form-control" id="current-pin" name="current-pin" placeholder="PIN actual">
                    </div>
                    <div class="form-group">
                        <label for="pin" class="control-label">Nuevo PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" placeholder="Nuevo PIN">
                    </div>
                    <div class="form-group">
                        <label for="pin_confirmation" class="control-label">Confirmar PIN</label>
                        <input type="password" class="form-control" id="pin_confirmation" name="pin_confirmation" placeholder="Confirmar PIN">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">Cambiar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">
                                Cerrar
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/account/profile.js')}}"></script>
@endsection