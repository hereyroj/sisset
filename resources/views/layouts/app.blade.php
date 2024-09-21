<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- title -->
    @yield('meta')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Place favicon.ico in the root directory -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('js/vendor/jquery-confirm/jquery-confirm.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <meta name="theme-color" content="#fafafa">
    <style>
        th {
            font-weight: lighter;
            font-family: 'Libre Baskerville', serif;
        }

        nav {
            font-family: 'Roboto Slab', serif;
            border-radius: 0 !important;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        html {
            height: 100%;
            box-sizing: border-box;
        }

        body, html {        
            background-color: #DADADA;
            min-height: 100%;
            width: 100%;
            padding: 0;
            margin: 0;
            font-family: 'Open Sans', sans-serif; 
            font-size: 14px;
        }

        footer {
            background-color: #222;
            color: #ffffff;
            min-height: 300px;
            border-top-color: #3c763d;
            border-top-width: 8px;
            border-top-style: solid;
            font-size: 18px;
            right: 0;
            left: 0;
            bottom: 0;
            padding: 15px;
            text-align: center;
            max-width: 100% !important;
        }

        footer div{
            padding: 0;
            margin: 0;
        }

        .footer h4 {
            font-size: 22px;
        }

        .footer ul li a:hover {
            cursor: pointer;
        }

        .navbar {
            margin: 0;
        }

        #contenido {
            margin: 0 auto;
            padding: 15px 15px 40px 15px;
            position: relative;
            height: 100%;
        }

        .header {
            width: 100%;
            height: 350px;
            margin-bottom: 2em;
            margin: 0;
            padding: 0;
        }

        .header > div{
            padding: 0;
        }

        .iconos {
            text-align: center;
        }

        .dashboard-box {
            border: 1px solid #e7ecf1;
            padding: 12px 20px 15px;
            min-height: 400px;
            background-color: #fff;            
            -webkit-box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
            -moz-box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
            box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
        }

        .title-box {
            border-bottom: 1px solid #eef1f5;
            padding: 0;
            min-height: 48px;
            margin-bottom: 10px;
        }

        .body-box {
            padding-top: 8px;
        }

        .title-box-title {
            font-size: 18px;
            line-height: 18px;
            padding: 10px 0;
        }

        .title-box-actions {
            float: right;
            display: inline-block;
            padding: 10px 0;
        }

        .actions-icons a {
            border-radius: 100px;
            height: 30px;
            width: 30px;
            border-color: #337ab7;
            border: 1px solid;
            color: #337ab7;
            vertical-align: middle;
            padding: 5px;
            font-size: 14px;
        }
    </style>
    <!-- Styles section -->
    @yield('styles')
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <a class="navbar-brand" href="#">SISSET</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/') }}">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Servicios<b class="caret"></b></a>
                <div class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ url('servicios/inspeccion/acuerdoPago/index') }}">Acuerdos de pago</a>
                    <a class="dropdown-item" href="{{ url('servicios/inspeccion/comparendos/index') }}">Comparendos</a>
                    <a class="dropdown-item" href="{{ url('servicios/vehiculo/estadoCuenta/index') }}">Estado de cuenta</a>
                    <a class="dropdown-item" href="{{ url('servicios/vehiculo/inmovilizaciones/index') }}">Inmovilizaciones</a>
                    <a class="dropdown-item" href="{{ url('servicios/liquidaciones/acuerdoPago/index') }}">Liquidar acuerdo de pago</a>
                    <a class="dropdown-item" href="{{ url('servicios/liquidaciones/comparendos/index') }}">Liquidar comparendo</a>
                    <a class="dropdown-item" href="{{ url('servicios/servicios/liquidaciones/servicioPublico/index') }}">Liquidar impuesto público</a>
                    <a class="dropdown-item" href="{{ url('servicios/normativa/index') }}">Normatividad</a>
                    <a class="dropdown-item" href="{{ url('servicios/notificacionesAviso/index') }}">Notificaciones por aviso</a>
                    <a class="dropdown-item" href="{{ url('servicios/tramites/preasignaciones/index') }}">Pre-Asignaciones</a>
                    <a class="dropdown-item" href="{{ url('servicios/pqr/index') }}">PQRS</a>
                    <a class="dropdown-item" href="{{ url('servicios/to/index') }}">Tarjetas de operación</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/login') }}">Ingresar</a>
            </li>
        </ul>
    </div>    
</header>
@if(\Setting::get('header') != null)
<div class="row header">
    <div class="col-lg-12">
        <img src="{{asset('storage/parametros/empresa/'.\Setting::get('header'))}}" style="width: 100%; height: inherit;">
    </div>        
</div>
@endif
<div class="container-fluid" id="contenido">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <p class="text-danger">Ha ocurrido un error en la consulta:</p>
            <ul>
                @if(is_object($errors))
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                @else
                    @foreach ($errors as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    @endif
    @yield('content')
</div>
<footer id="pie_pagina">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <h3><strong>{{Setting::get('empresa_nombre')}}</strong></h3>
                    <address>
                        {{Setting::get('empresa_direccion')}}<br>
                        <abbr title="Phone">PBX:</abbr> {{Setting::get('empresa_telefono')}}<br>
                        {{Setting::get('empresa_web')}}<br>
                        {{Setting::get('empresa_correo')}}
                    </address>
                </div>
                <div class="col-md-6">
                    <h3><strong>Nuestros servicios</strong></h3>
                    <ul class="list-inline">
                        <li><a href="{{ url('/servicios/edictos/comparendos/index') }}">Consultar si ha sido notificado por comparendos</a></li>
                        <li><a href="{{ url('/servicios/edictos/fotomultas/index') }}">Consultar si ha sido notificado por foto multas</a></li>
                        <li><a href="{{ url('/servicios/inspeccion/sanciones/index') }}">Consultar si ha sido notificado por sanciones</a></li>
                        <li><a href="{{ url('/servicios/to/index') }}">Consultar tarjeta de operación</a></li>
                        <li><a href="{{ url('/servicios/pqr/index') }}">Para Preguntas, Quejas, Reclamos o Solicitudes</a></li>
                        <li><a href="{{ url('/servicios/tramites/preasignaciones/solicitar') }}">Para solicitar una pre-asignación de placa</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <iframe src="{{\anlutro\LaravelSettings\Facade::get('empresa_mapa')}}" width="100%" height="270" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="{{asset('js/app.js')}}"></script>
@yield('scripts')

</body>
</html>