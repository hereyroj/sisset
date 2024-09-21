<!doctype html>
<html class="no-js" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('js/vendor/jquery-confirm/jquery-confirm.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/vendor/pickadate/themes/default.css')}}">
    <link rel="stylesheet" href="{{asset('js/vendor/pickadate/themes/default.date.css')}}">
    <link rel="stylesheet" href="{{asset('js/vendor/pickadate/themes/default.time.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="{{asset('css/layouts/dashboard.css')}}">
    <meta name="theme-color" content="#fafafa">    
    @yield('styles')
</head>
<body>
<!--[if lte IE 9]>
<p class="browserupgrade">Estás usando un navegador web <strong>obsoleto</strong>. Por favor <a href="http://browsehappy.com/">actualiza tu navegador</a> para mejorar la experiencia y seguridad.</p>
<![endif]-->

@if (Auth::guest())
    {{redirect()->to('/')}}
@else
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img alt="{{Setting::get('empresa_sigla')}}" src="{{asset('storage/parametros/empresa/'.Setting::get('logo_menu'))}}" style="max-height:30px;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('admin/dashboard') }}"><i class="fas fa-home"></i> Escritorio <span class="sr-only">(current)</span></a>
                </li>
                @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Inspector') || auth()->user()->hasRole('Auxiliar Inspección') || auth()->user()->hasRole('Coordinador Trámites'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="far fa-address-book"></i> Inspección<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('admin/inspeccion/AcuerdosPagos/administrar') }}">Acuerdos de Pagos</a>
                        <a class="dropdown-item" href="{{ url('admin/inspeccion/comparendos/administrar') }}">Comparendos</a>                        
                        <a class="dropdown-item" href="{{ url('admin/inspeccion/sanciones/administrar') }}">Sanciónes</a>                        
                    </div>
                </li>
                @endif
                @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Coordinador Coactivo') || auth()->user()->hasRole('Auxiliar Coactivo'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-briefcase"></i> Coactivo<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">Mandamientos</h6>
                        <a class="dropdown-item" href="{{ url('admin/coactivo/mandamientos/administrar') }}">Administrar</a>
                    </div>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-copy"></i> Gestión documental<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">PQR</h6>
                        @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Administrador PQR'))
                        <a class="dropdown-item" href="{{ url('admin/pqr/administrar') }}">Administrar</a>
                        @endif
                        <a class="dropdown-item" href="{{ url('admin/mis-pqr/misProcesos') }}">Mis procesos</a>
                        @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Administrador PQR'))
                        <h6 class="dropdown-header">TRD</h6>
                        <a class="dropdown-item" href="{{ url('admin/trd/administrar') }}">Administrar</a>
                        @endif
                        <h6 class="dropdown-header">Normatividad</h6>
                        <a class="dropdown-item" href="{{ url('admin/normativa/administrar') }}">Administrar</a>
                        <h6 class="dropdown-header">Notificaciones por aviso</h6>
                        <a class="dropdown-item" href="{{ url('admin/notificacionesAviso/administrar') }}">Administrar</a>    
                    </div>
                </li>
                @if(auth()->user()->hasRole('Administrador'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-cogs"></i> Sistema<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">Calendario</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/calendario/administrar') }}">Administrar calendario</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Dependencias</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/dependencias/administrar') }}">Administrar dependencias</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Documentos identidad</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/documentosIdentidad/administrar') }}">Administrar documentos</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Empresas</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/empresasMensajeria/administrar') }}">Mensajería</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/empresasTransporte/administrar') }}">Transporte</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Logs</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/logs/monitor') }}">Administrar logs</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Parámetros</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/empresa/administrar') }}">Empresa</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/gestionDocumental/administrar') }}">Gestión Documental</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/pqr/administrar') }}">PQR</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/to/administrar') }}">Tarjeta Operación</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/tramites/administrar') }}">Tramites</a>
                        <a class="dropdown-item" href="{{ url('admin/sistema/parametros/vigencias/administrar') }}">Vigencias</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Reportes</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/reportes/controlInterno') }}">Control interno</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Roles</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/roles/administrar') }}">Administrar roles</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Usuarios</h6>
                        <a class="dropdown-item" href="{{ url('admin/sistema/usuarios/administrar') }}">Administrar usuarios</a>                        
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                @endif
                @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Archivista') || auth()->user()->hasRole('Auxiliar Archivo'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-file-alt"></i> Archivo<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">Administración</h6>
                        <a class="dropdown-item" href="{{ url('admin/archivo/administrar') }}">Administración del archivo</a>
                    </div>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-exchange-alt"></i> Solicitudes<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Archivista') || auth()->user()->hasRole('Auxiliar Archivo'))
                        <a class="dropdown-item" href="{{ url('admin/solicitudes/administracion/entregarCarpetas') }}">Entrega de carpetas</a>
                        <a class="dropdown-item" href="{{ url('admin/solicitudes/administracion/procesarSolicitudes') }}">Gestionar solicitudes</a>
                        <a class="dropdown-item" href="{{ url('admin/solicitudes/administracion/validarSolicitudes') }}">Validar solicitudes</a>
                        @endif
                        <a class="dropdown-item" href="{{ url('admin/solicitudes/misSolicitudes') }}">Mis solicitudes</a>
                    </div>
                </li>
                @if(auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Coordinador Trámites') || auth()->user()->hasRole('Auxiliar Trámites') || auth()->user()->hasRole('Funcionario EV'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="far fa-handshake"></i> Tramites<b class="caret"></b></a>
                    <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('admin/tramites/impuestos/administrar') }}">Impuestos</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/placas/administrar') }}">Placas</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/preAsignaciones/administrar') }}">Pre-asignaciones</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/solicitudes/administrar') }}">Solicitudes</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/sustratos/administrar') }}">Sustratos</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/to/administrar') }}">Tarjetas de operación</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/tramites/administrar') }}">Tramites</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/vehiculos/administrar') }}">Vehículos</a>
                        <a class="dropdown-item" href="{{ url('admin/tramites/solicitudes/ventanilla') }}">Ventanilla</a>                        
                        <a class="dropdown-item" href="{{ url('admin/tramites/ventanillas/administrar') }}">Ventanillas</a>
                    </div>
                </li>
                @endif
                @if(auth()->user()->hasRole('Administrador'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('admin/posts/administrar') }}"><i class="fas fa-newspaper"></i> Publicaciones</a>
                </li>
                @endif                
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="{{ url('admin/chat/openChatBox') }}"><i class="fas fa-comment"></i> <span class="badge badge-light" id="mensajesSinLeer">0</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true"><i class="fas fa-bell"></i><span class="badge badge-light" id="numeroDeNotificaciones"></span></a>
                    <ul class="dropdown-menu menu-notificaciones" role="menu" style="padding: 0px; width: 470px; position: absolute; overflow: visible;" aria-labelledby="navbarDropdown">
                        <div class="head-notifications">
                            <h3>Notificaciones</h3>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); marcarNotificaciones();">
                                <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Marcar todas como leidas
                            </a>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); obtenerTodasNotificaciones();">
                                <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Ver todas
                            </a>
                        </div>
                        <div class="list-group" id="notifications">
                
                        </div>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-user"></i> <span class="caret"></span>
                    </a>
                        <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                            <div class="text-center"><img src="{{asset(auth()->user()->avatar)}}" alt="{{Auth::user()->name}}" class="img-profile"></div>
                            <div class="text-center">{{ Auth::user()->name }}</div>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Cuenta</h6>
                            <a class="dropdown-item" href="{{ url('admin/cuenta/perfil') }}"><i class="fa fa-btn glyphicon glyphicon-user"></i> Perfil</a>
                            <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-btn glyphicon glyphicon-log-out"></i> Salir</a>                    
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
@endif
@if (Session::has('alerta_vigencia'))
    <div class="alert alert-warning">
        <h4>Política de seguridad:</h4>
        <ul>
            {{Session::get('alerta_vigencia')}}
        </ul>
    </div>
@endif

@yield('content')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="{{asset('js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('js/vendor/pickadate/picker.date.js')}}"></script>
<script src="{{asset('js/vendor/pickadate/picker.time.js')}}"></script>
<script src="{{asset('js/vendor/pickadate/translations/es_ES.js')}}"></script>
<script src="{{asset('js/vendor/parsley/parsley.min.js')}}"></script>
<script src="{{asset('js/vendor/parsley/i18n/es.js')}}"></script>
<script src="{{asset('js/vendor/jquery-confirm/jquery-confirm.min.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
<script type="text/javascript" src="{{asset('js/layouts/dashboard.js')}}"></script>
<script type="ecmascript" src="{{asset('js/layouts/es_dashboard.js')}}"></script>
@if (Session::has('error_vigencia'))
<script type="text/javascript">
    errorVigencia("{{Session::has('error_vigencia')}}");
</script>
@endif
@yield('scripts')
</body>
</html>