@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pre-asignaciones - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('styles')
    <style>
        .cajas {
            display: flex;
            justify-content: center;
            align-items: center;
            -webkit-box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
            -moz-box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
            box-shadow: 6px 8px 15px -4px rgba(0,0,0,0.75);
        }

        .cajas a {
            flex-basis: 100%;
            min-height: 1px;
            position: relative;
            flex-direction: column;
            text-decoration: none;
        }

        .caja-head {
            -moz-box-align: center;
            align-items: center;
            display: flex;
            height: 60px;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
        }

        .caja-body {
            background: rgba(255, 255, 255, 0.1) none repeat scroll 0 0;
            height: 100%;
            padding: 15px 30px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="page-header">
            <h1>Pre-asignaciones
                <small>Servicio al ciudadano</small>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="cajas">
                <a href="{{url('servicios/tramites/preasignaciones/solicitar')}}" style="background-color: #196c4b; color: #fff;">
                    <div class="caja-head">
                        <h3><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Solicitar</h3>
                    </div>
                    <div class="caja-body">
                        Clic aquí para solicitar una pre-asignación de placa para su posterior matricula.
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cajas">
                <a href="{{url('servicios/tramites/preasignaciones/consultar')}}" style="background-color: #0059bc; color: #fff;">
                    <div class="caja-head">
                        <h3><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Consultar estado</h3>
                    </div>
                    <div class="caja-body">
                        Aquí puede verificar el estado de su solicitud de pre-asignación.
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cajas">
                <a href="" style="background-color: #d9534f; color: #fff;">
                    <div class="caja-head">
                        <h3><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Consultar normativa del servicio</h3>
                    </div>
                    <div class="caja-body">
                        Descargue el documento que reglamenta la prestación de los servicios de tramites al ciudadano. {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}.
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection