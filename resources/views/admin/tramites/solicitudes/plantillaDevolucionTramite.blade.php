<?php
\Jenssegers\Date\Date::setLocale('es');
$date = \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $servicio->hasTramiteSolicitud->created_at);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        @page {
            size: letter;
            margin: 10mm;
        }

        body {
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h4 style="margin-bottom: 3em; text-align: center; font-weight: bold;">COMUNICADO DE DEVOLUCIÓN – SOLICITUD DE TRÁMITE</h4>
            <div class="table-responsive" style="margin-bottom: 3em; border: none;">
                <table class="table" style="border: none;">
                    <tr style="border: none;">
                        <td><strong>CIUDAD:</strong> </td>
                        <td><strong>FECHA:</strong> {{date('Y-m-d')}}</td>
                        <td><strong>HORA:</strong> {{date('H:i A')}}</td>
                    </tr>
                </table>
            </div>
            <p style="text-align: left; margin-bottom: 3em;">
                Señor(a)<br>
                {{strtoupper($servicio->hasTramiteSolicitud->hasTurnoActivo()->hasUsuarioSolicitante->nombre_usuario)}}
            </p>
            <p style="text-align: left; margin-bottom: 3em;">
                En atención a la solicitud de servicio radicada en la entidad {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }} el día {{$date->format('j F')}} de {{$date->format('Y')}} para la placa {{$servicio->placa}}, nos permitimos comunicarle que el trámite no cumple con los requisitos a continuación relacionados:
            </p>
            <table class="table table-striped table-bordered" style="margin-bottom: 3em;">
                <thead style="text-align: center;">
                <tr>
                    <th><strong>ESTADO</strong></th>
                    <th><strong>OBSERVACIÓN</strong></th>
                </tr>
                </thead>
                <tbody>
                @foreach($estados as $estado)
                    <tr>
                        <td>{{$estado->name}}</td>
                        <td>{{$estado->pivot->observacion}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p style="text-align: left;">
                Expedido por: {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}
            </p>
            <p style="text-align: center;">
                <br><br><br><br><br><br>{{auth()->user()->name}}<br>
                ________________________________<br>
                <strong>Funcionario {{ \anlutro\LaravelSettings\Facade::get('empresa-sigla') }}</strong>
            </p>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>