<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Llamado de turnos</title>
    <link rel="stylesheet" href="{{ asset('js/vendor/jquery-confirm/jquery-confirm.min.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- Styles -->
    <script src="{{asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">

    <style>
        th {
            font-weight: lighter;
            font-family: 'Libre Baskerville', serif;
            border: none;
        }

        table{
            text-align: center;
            font-weight: bold;
            margin: 0;
            padding: 0;
            font-size: 7em !important;
        }

        body {
            position: relative;
            margin: 0;
            padding: 20px;
            font-family: 'Open Sans', sans-serif;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="font-size: 3em;">
        <div class="row">
            <div class="col-md-12" style="padding: 0; margin: 0;">
                <div class="table-responsive">
                    <table class="table table-striped" id="turnos" style="border: 2px solid #e7ecf1;">
                        <thead>
                        <tr>
                            <th style="border-right: 1px solid #fff;">TURNO</th>
                            <th>VENTANILLA</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery-3.1.0.min.js')}}"><\/script>')</script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/vendor/jquery-confirm/jquery-confirm.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/publico/turnos.js')}}"></script>
    <script type="text/ecmascript" src="{{asset('js/publico/es_turnos.js')}}"></script>
</body>
</html>