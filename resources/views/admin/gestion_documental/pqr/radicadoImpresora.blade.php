<html>
<head>
    <meta charset="UTF-8">
    <style>
        html, body {
            width: 215mm;
            height: 279mm;
        }

        /*.vertical-text{
            margin: 0 !important;
            padding: 0 !important;
            -webkit-transform: rotate(-90deg);
            -webkit-transform-origin: left top;
            -moz-transform: rotate(-90deg);
            -moz-transform-origin: left top;
            -o-transform: rotate(-90deg);
            -o-transform-origin: left top;
            transform: rotate(-90deg);
            position: absolute;
            bottom: 0;
            font-size: 12px;
        }*/

        .vertical-text{
            font-size: 12px;
            writing-mode: vertical-lr;
            transform: rotate(180deg);
            word-wrap: break-word;
            display: block;
            position: absolute;
            bottom: 0 !important;
            right: 0;
            margin-right: 5mm;
            margin-bottom: 7mm;
        }

        .contenedor{
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            padding: 5mm;
        }
    </style>
</head>
<body onload="imprimir();">
<div class="contenedor">
    <span class="vertical-text"><strong>{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}</strong> - RADICADO {{strtoupper($tipoRadicado)}} No. {{$radicado->numero}}  &nbsp;&nbsp;&nbsp; <strong>FECHA Y HORA:</strong> {{$radicado->created_at}}</span>
</div>
<script type="text/javascript">
    function imprimir(){
        window.print();
        window.close();
    }
</script>
</body>
</html>