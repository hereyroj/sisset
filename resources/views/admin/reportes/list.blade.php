<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
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
    </head>
    <body>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr class="title">
                    <th colspan="{{count($columns)}}">{{strtoupper($title)}}</th>
                </tr>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        @foreach($item as $key)
                            <td>{!! $key !!}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>    
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>