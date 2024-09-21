<!DOCTYPE html>
<html>
<head>
    <title>No autorizado</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">@if(isset($message)) {!! $message !!} @else Ops! Ha ocurrido un error. @endif</div>
        <div class="content"><a href="{{str_replace(url('/'), '', url()->previous())}}" style="display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;background-color: #2BAE58;color: #ffffff;font-weight:bold;border-radius: 7px;padding: 10px"><span style="font-size:30px;line-height:24px;">Regresar</span></a></div>
    </div>
</div>
</body>
</html>