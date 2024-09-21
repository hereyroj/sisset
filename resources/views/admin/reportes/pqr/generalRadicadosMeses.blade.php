<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style type="text/css">
        th {
            font-weight: lighter;
            font-family: 'Libre Baskerville', serif;
        }

        .title {
            text-align: center;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
<div class="table-responsive">
    <table class="table table-striped generalPorClases">
        <thead>
        <tr>
            <th colspan="40">{{$title}}</th>
        </tr>
        <tr>
            <th rowspan="3">Clase</th>
            <th colspan="3">Enero</th>
            <th colspan="3">Febrero</th>
            <th colspan="3">Marzo</th>
            <th colspan="3">Abril</th>
            <th colspan="3">Mayo</th>
            <th colspan="3">Junio</th>
            <th colspan="3">Julio</th>
            <th colspan="3">Agosto</th>
            <th colspan="3">Septiembre</th>
            <th colspan="3">Octubre</th>
            <th colspan="3">Noviembre</th>
            <th colspan="3">Diciembre</th>
            <th colspan="3">Total</th>
        </tr>
        <tr>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
            <th>Ex</th>
            <th>In</th>
            <th>Sa</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clases as $clase)
            <tr>
                <td>{{$clase[0]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][0]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][0]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][0]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][1]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][1]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][1]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][2]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][2]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][2]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][3]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][3]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][3]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][4]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][4]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][4]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][5]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][5]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][5]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][6]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][6]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][6]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][7]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][7]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][7]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][8]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][8]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][8]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][9]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][9]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][9]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][10]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][10]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][10]}}</td>
                <td style="text-align: center">{{$clase[1]['CoEx'][11]}}</td>
                <td style="text-align: center">{{$clase[1]['CoIn'][11]}}</td>
                <td style="text-align: center">{{$clase[1]['CoSa'][11]}}</td>
                <td style="text-align: center">
                    <?php
                        $i = 0;
                        foreach ($clase[1]['CoEx'] as $mes){
                            $i = $i + $mes;
                        }
                        echo $i;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    $i = 0;
                    foreach ($clase[1]['CoIn'] as $mes){
                        $i = $i + $mes;
                    }
                    echo $i;
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    $i = 0;
                    foreach ($clase[1]['CoSa'] as $mes){
                        $i = $i + $mes;
                    }
                    echo $i;
                    ?>
                </td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td>TOTAL</td>
            @foreach($totales as $total)
                <td style="text-align: center">{{$total['CoEx']}}</td>
                <td style="text-align: center">{{$total['CoIn']}}</td>
                <td style="text-align: center">{{$total['CoSa']}}</td>
            @endforeach
            <td style="text-align: center">
                <?php
                $i = 0;
                foreach ($totales as $total){
                    $i = $i + $total['CoEx'];
                }
                echo $i;
                ?>
            </td>
            <td style="text-align: center">
                <?php
                $i = 0;
                foreach ($totales as $total){
                    $i = $i + $total['CoIn'];
                }
                echo $i;
                ?>
            </td>
            <td style="text-align: center">
                <?php
                $i = 0;
                foreach ($totales as $total){
                    $i = $i + $total['CoSa'];
                }
                echo $i;
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>    
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.0.3/jquery.floatThead.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var $table = $('table.generalPorClases');
        $table.floatThead();
    });
</script>
</html>
