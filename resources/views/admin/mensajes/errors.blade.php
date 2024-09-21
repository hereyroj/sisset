<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
</head>
<body>
<div class="alert alert-danger">
    <strong>{{$encabezado}}</strong>
    <ul>
        @foreach ($errors as $error)
            <li>{!! $error !!}</li>
        @endforeach
    </ul>
</div>
</body>

