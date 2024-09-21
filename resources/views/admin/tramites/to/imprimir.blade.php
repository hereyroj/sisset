<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:Regular|Bold" rel="stylesheet">
    <style type="text/css">
        table {
            border-collapse: collapse;
            margin: auto 0;
            padding: 0;
            table-layout: auto;
            clear: both;
            letter-spacing: 0.8px;
            width: 100% !important;
            margin-top: 10px;
        }

        table tr {
            page-break-inside: avoid;
            padding: 0;
        }

        table th{
            font-weight: normal;
            font-size: 14px;
        }

        table td {
            white-space: nowrap;
            border: 1px solid transparent;
            text-align: center;
            padding: 0;
            font-weight: bold;
        }

        body {
            width: 580px !important;
            height: 264px !important;
            max-width: 580px !important;
            max-height: 264px !important;
            page-break-inside: avoid !important;
            padding: 0 !important;
            margin: 0 !important;
            font-family: 'Lato', sans-serif;
        }

        div.page {
            page-break-after: always;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="page" style="padding-top: 10px;">
    <table>
        <thead>
        <tbody>
        <tr>
            <td><img src="{{asset('img/escudo_colombia.png')}}" style="width: 70px; height: 90px;"></td>
            <td><strong><span style="font-size: 26px;">REPÚBLICA DE COLOMBIA</span></strong><br>MINISTERIO DE TRANSPORTE<br><strong><span style="font-size: 20px;">TARJETA DE OPERACIÓN</span></strong></td>
            <td><img src="{{asset('storage/parametros/empresa/'.\Setting::get('logo_empresa'))}}" style="width: 110px; height: 80px;"></td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>N°</th>
            <th>FECHA DE VENCIMIENTO</th>
            <th>PLACA</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><span style="font-size: 20px;">{!! $to[0]['id'] !!}</span></td>
            <td>
                <table style="margin: 0 !important;">
                    <thead>
                    <tr>
                        <th>Día</th>
                        <th>Mes</th>
                        <th>Año</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$dia}}</td>
                        <td>{{$mes}}</td>
                        <td>{{$año}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td><span style="font-size: 20px;">{{ $to[0]['placa'] }}</span></td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>CLASE DE VEHÍCULO</th>
            <th>TIPO DE CARROCERÍA</th>
            <th>MARCA</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{!! $to[0]['hasTipoVehiculo']['name'] !!}</td>
            <td>{!! $to[0]['hasTipoCarroceria']['name'] !!}</td>
            <td>{!! $to[0]['hasMarca']['name'] !!}</td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>CLASE DE COMBUSTIBLE</th>
            <th>MODELO</th>
            <th>NÚMERO DE MOTOR</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{!! $to[0]['hasClaseCombustible']['name'] !!}</td>
            <td>{!! $to[0]['modelo'] !!}</td>
            <td>{!! $to[0]['numero_motor'] !!}</td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>NIVEL DE SERVICIO</th>
            <th>CAPACIDAD</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="white-space: normal; vertical-align: text-top;">{!! $to[0]['hasNivelServicio']['name'] !!}</td>
            <td>
                <table style="margin: 0 !important;">
                    <thead>
                    <tr>
                        <th>PASAJEROS</th>
                        <th>TONELADAS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>@if($to[0]['capacidad_pasajeros'] < 10) 0{!! $to[0]['capacidad_pasajeros'] !!}@else{!! $to[0]['capacidad_pasajeros'] !!}@endif</td>
                        <td>@if($to[0]['capacidad_toneladas'] != null && $to[0]['capacidad_toneladas'] != '0')@if($to[0]['capacidad_toneladas'] < 10) 0{!! $to[0]['capacidad_toneladas'] !!}@else{!! $to[0]['capacidad_toneladas'] !!}@endif @else <div class="subtd" style="color: #ffffff; opacity: 0.1;">00</div> @endif</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="page" style="padding-top: 20px;">
    <table>
        <thead>
        <tr>
            <th>RAZÓN SOCIAL</th>
            <th>NIT</th>
            <th>N° INTERNO</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{!! $to[0]['hasEmpresaTransporte']['name'] !!}</td>
            <td>{!! $to[0]['hasEmpresaTransporte']['nit'] !!}</td>
            <td>{!! $to[0]['numero_interno'] !!}</td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>SEDE</th>
            <th>RADIO DE OPERACIÓN</th>
            <th>ZONA DE OPERACIÓN</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{!! $to[0]['sede'] !!}</td>
            <td>{!! $to[0]['hasRadioOperacion']['name'] !!}</td>
            <td>{!! $to[0]['zona_operacion'] !!}</td>
        </tr>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>AUTORIDAD QUE EXPIDE</th>
            <th>FIRMA DEL FUNCIONARIO</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="white-space: normal; vertical-align: text-top; text-align: center;">{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}</td>
            <td><img src="{{ storage_path('app/otros/empresa/'.\Setting::get('firma_director'))}}" style="width: 250px;"></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>

