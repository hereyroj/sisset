<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:Regular|Bold" rel="stylesheet">
    <style>
        tr {
            page-break-inside: avoid;
        }

        tr, td {
            margin: 0;
            padding: 0;
        }

        div.page > table {
            border-collapse: collapse;
            height: 100% !important;
            width: 100% !important;
            margin: auto 0;
            padding: 0;
            table-layout: auto;
            clear: both;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            page-break-inside: avoid !important;
            padding: 0 !important;
            margin: auto 0 !important;
            font-family: 'Lato', sans-serif;
        }

        div.page {
            page-break-after: always;
            page-break-inside: avoid;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<!-- hoja -->
<body>
<div class="page">
    <table>
        <tr>
            <!-- primer label -->
            <td style="width: 50%; height: 100%; padding-right: 15px;">
                <table>
                    <!-- Primera fila (logo y nombre empresa) -->
                    <tr style="height: 50%; !important">
                        <!-- logo -->
                        <td style="width: 25% !important;" class="logo"> </td>
                        <!-- nombre empresa-->
                        <td style="width: 75% !important; font-weight: bold; text-align: center; font-size: 9px; line-height: 15px; margin: 0 auto;">
                            {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}
                        </td>
                    </tr>
                    <!-- segunda fila  (número del radicado)-->
                    <tr style="font-weight: bold; font-size: 7px;  height: 50%; text-align: center">
                        <td colspan="2" style="line-height: 15px;">
                            Radicado Entrada No. {{$radicado->numero}}<br>
                            <?php
                            $time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $radicado->created_at);
                            echo $time->format('Y/m/d g:i a');
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <!-- segundo label -->
            <td style="width: 50%; height: 100%; padding-left: 15px;">
                <table>
                    <!-- Primera fila (logo y nombre empresa) -->
                    <tr style="height: 50%; !important">
                        <!-- logo -->
                        <td style="width: 25% !important;" class="logo"> </td>
                        <!-- nombre empresa-->
                        <td style="width: 75% !important; font-weight: bold; text-align: center; font-size: 9px; line-height: 15px; margin: 0 auto;">
                            {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}
                        </td>
                    </tr>
                    <!-- segunda fila  (número del radicado)-->
                    <tr style="font-weight: bold; font-size: 7px;  height: 50%; text-align: center">
                        <td colspan="2" style="line-height: 15px;">
                            Radicado Entrada No. {{$radicado->numero}}<br>
                            <?php
                            $time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $radicado->created_at);
                            echo $time->format('Y/m/d g:i a');
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        //var width = $('.logo_1').width();
        //var height = $('.logo_1').height();
        var base_url = "{{asset('storage/parametros/empresa/'.\anlutro\LaravelSettings\Facade::get('empresa-logo'))}}";
        $('.logo').append("<img src='" + base_url + "' width='50px' height='30px'>");
    })
</script>
</html>