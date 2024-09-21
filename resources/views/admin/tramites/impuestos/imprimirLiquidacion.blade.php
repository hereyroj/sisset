<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- title -->
@yield('meta')
<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/vendor/jquery-confirm/jquery-confirm.min.css')}}">

    <style>
        body {
            padding: 0 !important;
            margin: 0 !important;
        }

        .row{
            page-break-inside: avoid !important;
        }

        table{
            text-align: center;
            font-size: 12px;
            word-break: keep-all;
        }

        td, tbody, thead, tr, th, table{
            border: 1px solid black !important;
        }

        h5{
            text-align: center;
        }

        table tr{
            height: 22px;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{
            padding: 2px;
        }

        div{
            float: left;
            display: block;
        }

        hr{
            display: block;
        }

        .verticaltext {
            position: fixed;
            -webkit-transform: rotate(270deg);
            -webkit-transform-origin: center bottom auto;
            right: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div style="margin-bottom: 30px;width: 100%;">
        <div style="width: 20%;">
            <img src="{{asset('storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}" style="width: 170px;height: 80px;">
        </div>
        <div style="font-size: 18px;text-align: center;font-weight: bold;width: 60%;">
            {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}<br>Declaración de Impuesto<br>Sobre Vehículos de Servicio Público
        </div>
        <div style="width: 20%;">

        </div>
    </div>
    <div style="width: 100%;display: table">
        <div style="width: 69%;padding-right: 10px;display: table-cell;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>DECLARACION No.</th>
                        <th>PERIODO GRAVABLE</th>
                        <th>FRACCION AÑO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$liquidacion->codigo}}</td>
                        <td><strong>{{$liquidacion->hasVigencia->vigencia}}</strong></td>
                        <td>
                            12 MESES
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h5>INFORMACIÓN DEL DECLARANTE</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 25%;">CEDULA / NIT</th>
                        <th style="width: 50%;">DECLARANTE</th>
                        <th style="width: 25%;">TELEFONO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 25%;">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->numero_documento}}</td>
                        <td style="width: 50%;">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->nombre}}</td>
                        <td style="width: 25%;">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->telefono}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 45%;">DIRECCION</th>
                        <th style="width: 30%;">MUNICIPIO DE RESIDENCIA</th>
                        <th style="width: 25%;">DPTO DE RESIDENCIA</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 45%;">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->direccion}}</td>
                        <td style="width: 30%;">{{strtoupper($liquidacion->hasVehiculo->hasPropietariosActivos()->first()->hasMunicipio->name)}}</td>
                        <td style="width: 25%;">{{strtoupper($liquidacion->hasVehiculo->hasPropietariosActivos()->first()->hasDepartamento->name)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h5>INFORMACIÓN DEL VEHÍCULO</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="max-width: 25% !important;width: 25%;">PLACA</th>
                        <th style="max-width: 25% !important;width: 25%;">MARCA</th>
                        <th style="max-width: 25% !important;width: 25%;">MODELO</th>
                        <th style="max-width: 25% !important;width: 25%;">CLASE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="max-width: 25% !important;width: 25%;"><strong>{{$liquidacion->hasVehiculo->placa}}</strong></td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasMarca->name}}</td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->modelo}}</td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasTipoVehiculo->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="max-width: 25% !important;width: 25%;">CILINDRAJE</th>
                        <th style="max-width: 25% !important;width: 25%;">LINEA</th>
                        <th style="max-width: 25% !important;width: 25%;">CARROCERIA</th>
                        <th style="max-width: 25% !important;width: 25%;">SERVICIO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasLinea->cilindraje}}</td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasLinea->nombre}}</td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasTipoCarroceria->name}}</td>
                        <td style="max-width: 25% !important;width: 25%;">{{$liquidacion->hasVehiculo->hasNivelServicio()->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 29%;padding-left: 10px;display: table-cell;">
            <div class="table-responsive">
                <table class="table table-striped" style="text-align: right;">
                    <thead>
                    <tr>
                        <th>DESCRIPCION</th>
                        <th>VALOR</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>AVALUO</td>
                        <td><span style="float: left;">$</span> {{number_format($liquidacion->valor_avaluo, 0, ',','.')}}</td>
                    </tr>
                    <tr style="background-color: #dbdbdb">
                        <td>IMPUESTO</td>
                        <td><span style="float: left;">$</span> {{number_format($liquidacion->valor_impuesto, 0, ',','.')}}</td>
                    </tr>
                    <tr>
                        <td>INTERESES</td>
                        <td><span style="float: left;">$</span> {{number_format($liquidacion->valor_mora_total, 0, ',','.')}}</td>
                    </tr>
                    <tr style="background-color: #dbdbdb">
                        <td>DESCUENTOS</td>
                        <td><span style="float: left;">$</span> {{number_format($liquidacion->valor_descuento_total, 0, ',','.')}}</td>
                    </tr>
                    <tr>
                        <td>DERECHOS ENTIDAD</td>
                        <td><span style="float: left;">$</span> {{number_format($liquidacion->derechos_entidad, 0, ',','.')}}</td>
                    </tr>
                    <tr style="background-color: #dbdbdb">
                        <td><strong>TOTAL</strong></td>
                        <td><span style="float: left;">$</span> <strong>{{number_format($liquidacion->valor_total, 0, ',','.')}}</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="height: 180px; width: 100%; text-align: center; padding-top: 160px;">
                <span style="font-size: 14px;">Sello de Caja</span>
            </div>
            <h5>FECHA LÍMITE DE PAGO:   {{$liquidacion->fecha_vencimiento}}</h5>
        </div>
        <div style="width: 2%;display: table-cell;">
            <span style="margin-top: 200px;" class="etiqueta">DECLARANTE</span>
        </div>
    </div>

    <div style="margin-bottom: 29px;width: 100%;border-top: 2px #0f0f0f dashed;padding-top: 45px;margin-top: 39px;">
        <div style="width: 20%;">
            <img src="{{asset('storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}" style="width: 170px;height: 80px;">
        </div>
        <div style="font-size: 18px;text-align: center;font-weight: bold;width: 60%;">
            {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}<br>Declaración de Impuesto<br>Sobre Vehículos de Servicio Público
        </div>
        <div  style="width: 20%">

        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 59%;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>DECLARACION No.</th>
                        <th>PERIODO</th>
                        <th>PLACA</th>
                        <th>VALOR PAGADO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$liquidacion->codigo}}</td>
                        <td><strong>{{$liquidacion->hasVigencia->vigencia}}</strong></td>
                        <td><strong>{{$liquidacion->hasVehiculo->placa}}</strong></td>
                        <td><span style="float: left;">$</span> <span style="float: right;">{{number_format($liquidacion->valor_total, 0, ',','.')}}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 35%">CEDULA / NIT</th>
                        <th style="width: 65%">DECLARANTE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 35%">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->numero_documento}}</td>
                        <td style="width: 65%">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->nombre}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 39%;">
            <div style="height: 150px; width: 100%; text-align: center; padding-top: 130px;">
                <span style="font-size: 14px;">Sello de Caja</span>
            </div>
            <h5>FECHA LÍMITE DE PAGO:   {{$liquidacion->fecha_vencimiento}}</h5>
        </div>
        <div style="width: 2%; margin-top: 40px;">
            <span class="etiqueta">TESORERIA</span>
        </div>
    </div>
    <div style="margin-bottom: 29px;width: 100%;border-top: 2px #0f0f0f dashed;padding-top: 45px;margin-top: 39px;">
        <div style="width: 20%;">
            <img src="{{asset('storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}" style="width: 170px;height: 80px;">
        </div>
        <div style="font-size: 18px;text-align: center;font-weight: bold;width: 60%;">
            {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}<br>Declaración de Impuesto<br>Sobre Vehículos de Servicio Público
        </div>
        <div style="width: 20%">

        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 59%">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>DECLARACION No.</th>
                        <th>PERIODO</th>
                        <th>PLACA</th>
                        <th>VALOR PAGADO</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$liquidacion->codigo}}</td>
                        <td><strong>{{$liquidacion->hasVigencia->vigencia}}</strong></td>
                        <td><strong>{{$liquidacion->hasVehiculo->placa}}</strong></td>
                        <td><span style="float: left;">$</span> <span style="float: right;">{{number_format($liquidacion->valor_total, 0, ',','.')}}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 35%">CEDULA / NIT</th>
                        <th style="width: 65%">DECLARANTE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 35%">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->numero_documento}}</td>
                        <td style="width: 65%">{{$liquidacion->hasVehiculo->hasPropietariosActivos()->first()->nombre}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 39%;">
            <div style="height: 150px; width: 100%; text-align: center; padding-top: 130px;">
                <span style="font-size: 14px;">Sello de Caja</span>
            </div>
            <h5>FECHA LÍMITE DE PAGO:   {{$liquidacion->fecha_vencimiento}}</h5>
        </div>
        <div style="width: 2%;margin-top: 40px;">
            <span class="etiqueta">CARPETA</span>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery-3.3.1.min.js')}}"><\/script>')</script>
<script src="{{asset('js/vendor/modernizr-3.6.0.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/importarLiquidacion.js')}}"></script>
</body>
</html>