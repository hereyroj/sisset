<?php
\Carbon\Carbon::setToStringFormat('jS \o\f F \o\f Y');
\Carbon\Carbon::setLocale('es');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">


    <style type="text/css">
        .footer{
            color: #2957a4;
            background-color: #fff;
            bottom: 0 !important;
            border: none;
            margin-top: 5px;
            page-break-inside: avoid;
        }

        .logo img{
            width: 330px;
            height: 210px;
            display:block;
            margin:auto;
            vertical-align: middle !important;
        }

        .logo{
            padding: 0;
            margin: 0;
            max-width: inherit;
        }
        
        body{
            font-size: 20px;
            color: #000;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .remitente{
            padding-top: 8040px;
            page-break-inside: avoid;
        }

        .descripcion{
            margin-top: -45px;
            text-align: justify;
            page-break-inside: auto;
        }

        .radicado{
            page-break-inside: avoid;
        }

        .radicado div{
            text-align: center;
        }

        hr{
            border: 1px solid #0f0f0f;
            width: 100%;
        }

        .page{
            width: 100% !important;
            height: 100% !important;
        }

        .page > div{
            width: 100% !important;
            height: 100% !important;
            margin: 0 0 40px 0;
            padding: 0 !important;
        }

        .footer-title{
            padding: 0;
            margin: 0 0 30px 0;
            text-align: center;
            font-size: 22px;
            width: 100%;
            page-break-inside: avoid;
        }

        .footer-text{
            width: 40%;
            padding: 0;
            page-break-inside: avoid;
        }

        .footer-logo{
            width: 60%;
            padding: 0 0 15px 0;
            page-break-inside: avoid;
        }

        .footer div{
            float: left;
            page-break-inside: avoid;
        }

        .footer-logo img{
            width: 400px;
            height: 360px;
            margin: 0 auto 0 auto;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="page">

        <div class="ciudad">
            <p>
                {{date('d')}} de {{date('m')}} del {{date('Y')}}
            </p>
            <br>
        </div>

        <div class="presentacion">
            <p>
                <strong>SEÑORES</strong><br>
                <strong>{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}</strong>
            </p>
        </div>

        <div class="asunto">
            <p><strong>Asunto: {{$pqr->asunto}}</strong></p>
        </div>

        <div class="descripcion">
            <p>{{$pqr->descripcion}}</p>
        </div>

        <div class="remitente">
            <p>
                <strong>Cordialmente,</strong><br>
                {{$pqr->hasPeticionario->nombre_completo}}<br>
                {{title_case($pqr->hasPeticionario->getUsuarioTipoDocumento->name)}} N° {{$pqr->hasPeticionario->numero_documento}}<br>
                {{$pqr->hasPeticionario->direccion_residencia}} - {{$pqr->hasPeticionario->couldHaveMunicipio->name}},{{$pqr->hasPeticionario->couldHaveDpto->name}}<br>
                {{$pqr->hasPeticionario->numero_telefono}}
            </p>
        </div>
        <hr>
        <div class="radicado">
            <div class="col-xs-5" style="padding: 0;">
                <strong style="color: #2957a4;">RADICADO</strong><br>
                {{$radicado->numero}}
            </div>
            <div class="col-xs-4" style="padding: 0;">
                <strong style="color: #2957a4;">EXPEDIENTE</strong><br>
                <strong>{{$pqr->numero_expediente}}</strong>
            </div>
            <div class="col-xs-3" style="padding: 0;">
                <strong style="color: #2957a4;">FECHA</strong><br>
                <strong>{{$radicado->created_at->format('Y-m-d H:i:s')}}</strong>
            </div>
        </div>

        <div class="footer">
            <div class="footer-title">
                <h2><strong>{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}</strong></h2>
            </div>
            <div class="footer-text">
                <p>
                    <strong>Teléfono:</strong><br>
                    {{ \anlutro\LaravelSettings\Facade::get('empresa-telefono') }}
                </p>
                <p>
                    <strong>Horario de atención:</strong><br>
                    {{ \anlutro\LaravelSettings\Facade::get('empresa-horario') }}
                </p>
                <p>
                    <strong>Correo electrónico:</strong><br>
                    {{ \anlutro\LaravelSettings\Facade::get('empresa-correo') }}
                </p>
                <p>
                    <strong>Sitio web:</strong><br>
                    {{ \anlutro\LaravelSettings\Facade::get('empresa-web') }}
                </p>
                <p>
                    <strong>Dirección:</strong><br>
                    {{ \anlutro\LaravelSettings\Facade::get('empresa-direccion') }}
                </p>
            </div>
            <div class="footer-logo">
                <img src="{{url('storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}">
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/vendor/modernizr-3.6.0.min.js')}}"></script>
</body>
</html>