@extends('layouts.dashboard')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PQR - Reporte de Control Interno</title>
@endsection

@section('styles')
    <style>
        .btn-actualizar {
            border-radius: 0 !important;
            min-height: 40px !important;
        }

        .cabecera-tabla {
            min-height: 40px;
            max-height: 40px;
        }

        .cabecera-tabla div {
            float: left;
            display: block;
        }

        .field-search {
            background-color: #5cb85c;
            padding: 4px 6px;
            width: 378px;
            min-height: 40px !important;
        }

        .field-search input {
            border: none;
            width: 300px;
            min-height: 32px !important;
            vertical-align: middle;
            padding-left: 5px;
        }

        .btn-buscar {
            width: 32px;
            height: 32px;
            background-color: #2e6da4;
            color: #fff;
            border: none;
            vertical-align: middle;
            margin-left: -3px;
        }

        .btn-restaurar {
            width: 32px;
            height: 32px;
            background-color: #d43f3a;
            color: #fff;
            border: none;
            vertical-align: middle;
            margin-left: -4px;
        }


    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">PQR - Reporte de Control Interno</div>
                   <div class="card-body">                        
                        <form class="form-inline" method="POST" action="{{url('admin/reportes/pqr/informeGeneralControlInterno')}}">
                            {{ csrf_field() }}
                            <h4>Reporte General por tipo</h4>
                            <div class="form-group">
                                <label class="control-label">Fecha Inicio</label>   
                                <input type="text" name="fecha_inicio" class="datepicker form-control" required>     
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fecha Fin</label>   
                                <input type="text" name="fecha_fin" class="datepicker form-control" required>     
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo PQR</label>   
                                <select class="form-control" name="tipoPQR" required>
                                    <option value="CoEx">Externa</option>
                                    <option value="CoIn">Interna</option>
                                    <option value="CoSa">Saliente</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.datepicker').pickadate({
            selectYears: true,
            selectMonths: true,
            formatSubmit: 'yyyy-mm-dd'
        });
        
        @if ($errors->any())                        
            $.alert({
                title: 'Error(es) en la generación del reporte:',
                type: 'red',
                content: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
        @endif        
    </script>
@endsection