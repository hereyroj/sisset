<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- title -->
@yield('meta')
<!-- CSRF Token -->
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>

        body {
            background-color: #ffffff;
            padding: 1.5cm;
            font-size: 16px;
        }

        .fecha {
            bottom: 0;
            position: absolute;
        }

        .row, .fecha {
            margin: 0;
            padding: 0;
            width: 100%;
        }

    </style>
</head>
<body>
<div class="row">
    @if(isset($notificacionesAviso) && count($notificacionesAviso)>0)
        <h4>Historial de Notificaciones por Aviso a: {{$parametro}}</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Tipo notificación</th>
                    <th>Número de proceso</th>
                    <th>Nombres y Apellidos</th>
                    <th>Número documento</th>                    
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($notificacionesAviso as $notificacionAviso)
                    <tr>
                        <td>{{$notificacionAviso->hasTipoNotificacion->name}}</td>
                        <td>{{$notificacionAviso->numero_proceso}}</td>
                        <td>{{$notificacionAviso->nombre_notificado}}</td>
                        <td>{{$notificacionAviso->numero_documento}}</td>                        
                        <td>{{$notificacionAviso->fecha_publicacion}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <h2>No se han realizado Notificaciones por Aviso al nombre o cédula especificado.</h2>
    @endif
</div>
<div class="fecha">
    <h4>{{\anlutro\LaravelSettings\Facade::get('empresa_web')}} / Generado el <?php
        \Jenssegers\Date\Date::setLocale('es');
        echo \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('j F, Y g:i:s a');
        ?></h4>
</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>