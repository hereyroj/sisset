@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Radicado - {{Setting::get('empresa_sigla')}}</title>
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <style type="text/css">
        @media print{
            header, body.footer, nav, aside, .alert, .header , button, a{
                display: none;
            }

            body {
                background-image: none;
            }

            #pie_pagina, #buttons{
                display: none;
            }

            .page > div{
                width: 100% !important;
                min-height: 25px !important;
                margin: 0 0 40px 0;
                padding: 0 !important;
            }
        }

        @media screen{
            .footer{
                color: #2957a4;
                background-color: #fff;
                bottom: 0 !important;
                border: none;
                margin-top: 5px;
                page-break-inside: avoid;
                width: 100%;
                min-height: 380px;
                height: auto;
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
                padding: 15px;
            }

            .page > div{
                width: 100% !important;
                min-height: 25px !important;
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
                float: left;
                min-height: 380px;
                height: auto;
            }

            .footer-logo{
                width: 60%;
                float: left;
                min-height: 380px;
                height: auto;
            }

            .footer div{
                display: block;
            }

            .footer-logo{
                background-image: url("{{asset(storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}");
                background-position: center center;
                background-size: contain;
                background-repeat: no-repeat;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="alert alert-success">
            Se ha radicado su solicitud correctamente.
        </div>
        <div class="page" id="imprimir">

            <div class="ciudad">
                <p>
                    {{date('d')}} de {{date('m')}} del {{date('Y')}}
                </p>
                <br>
            </div>

            <div class="presentacion">
                <p>
                    <strong>SEÑORES</strong><br>
                    <strong>{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}</strong><br>
                    Calle 15A N° 31 -24 Bachue <br>
                    La ciudad
                </p>
            </div>

            <div class="asunto">
                <p><strong>Asunto: {{$pqr->asunto}}</strong></p>
            </div>

            <div class="descripcion">
                <p style="text-align: justify;">{{$pqr->descripcion}}</p>
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
            <hr style="margin-bottom: 15px;">
            <div class="radicado">
                <div class="col-xs-5" style="padding: 0;">
                    <strong style="color: #2957a4;">RADICADO</strong><br>
                    {{$radicado->numero}}
                    <br>
                    {{$pqr->uuid}}
                </div>
                <div class="col-xs-4" style="padding: 0;">
                    <strong style="color: #2957a4;">No. OFICIO</strong><br>
                    <strong>{{$pqr->numero_expediente}}</strong>
                </div>
                <div class="col-xs-3" style="padding: 0;">
                    <strong style="color: #2957a4;">FECHA</strong><br>
                    <strong>{{$radicado->created_at->format('Y-m-d H:i:s')}}</strong>
                </div>
            </div>
            <hr>
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

                </div>
            </div>
        </div>
        <div class="container-fluid" id="buttons">
            <hr>
            <div class="col-md-3">
                <button type="button" class="btn btn-secondary" onclick="printJS({ printable: 'imprimir', type: 'html', header: '{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}' })"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir</button>
                <a type="button" class="btn btn-secondary" href="{{url('servicios/pqr/pdf/'.$pqr->uuid)}}"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
@endsection