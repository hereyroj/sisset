@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Radicar PQR - {{Setting::get('empresa_sigla')}}</title>
    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
@endsection

@section('content')
    <div class="row" style="padding: 0 20px;">
        <div class="card">
            <div class="card-header">
                <div class="panel-title">Estado proceso PQR</div>
            </div>
            <div class="panel-body" id="imprimir">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="8">PROCESO RADICADO</th>
                        </tr>
                        <tr>
                            <th>Radicado</th>
                            <th>Fecha de radicación</th>
                            <th>Clase</th>
                            <th>Peticionario</th>
                            <th>Tipo documento ID</th>
                            <th>Número documento ID</th>
                            <th>Asignado a:</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$pqr->getRadicadoEntrada->numero}}<br>{{$pqr->getRadicadoEntrada->created_at}}</td>
                            <td>{{$pqr->getRadicadoEntrada->created_at->format('Y-m-d H:i:s')}}</td>
                            <td>{{$pqr->hasClase->name}}</td>
                            <td>{{$pqr->hasPeticionario->nombre_completo}}</td>
                            <td>{{$pqr->hasPeticionario->getUsuarioTipoDocumento->name}}</td>
                            <td>{{$pqr->hasPeticionario->numero_documento}}</td>
                            <td>
                                @if($pqr->getAsignacionesActivas() != null)
                                    @foreach($pqr->getAsignacionesActivas() as $asignacion)
                                        <span class="badge badge-pill badge-primary">{{$asignacion->hasUsuarioAsignado->name}}</span>
                                    @endforeach
                                @else
                                    Sin asignar
                                @endif
                            </td>
                            <td>
                                @if(($pqr->hasAnulacion != null))
                                    Anulado
                                @elseif(($pqr->hasRespuesta == null))
                                    Sin resolver<br>
                                    @if($pqr->diasPasados() > 1)
                                        Días pasados: {{$pqr->diasPasados()}}
                                    @else
                                        Días restantes: {{$pqr->diasRestantes()}}
                                    @endif
                                @elseif($pqr->hasRespuesta != null)
                                    Resuelto<br>
                                    @if($pqr->diasPasados() > 1)
                                        Días pasados: {{$pqr->diasPasados()}}
                                    @endif
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                @if($pqr->hasRespuestas->count() > 0)
                    @foreach($pqr->hasRespuestas as $respuesta)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th colspan="11">RESPUESTA DEL PROCESO</th>
                            </tr>
                            <tr>
                                <th>Radicado de salida</th>
                                <th>Funcionario respuesta</th>
                                <th>Número de oficio</th>
                                <th>Radicados a los que responde</th>
                                <th>Documento de respuesta</th>
                                <th>Anexos</th>
                                <th>Modalidad de envío</th>
                                <th>Empresa de envío</th>
                                <th>Número de guía</th>
                                <th>Fecha de envío</th>
                                <th>Hora de envío</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    {{$respuesta->getRadicadoSalida->numero}}<br>
                                    {{$respuesta->getRadicadoSalida->created_at}}
                                </td>
                                <td>
                                    {{$respuesta->hasPeticionario->couldHaveFuncionario->name}}
                                </td>
                                <td>
                                    {{$respuesta->numero_oficio}}
                                </td>
                                <td>
                                    <?php
                                    $radicados = explode(',', $respuesta->radicados_respuesta);
                                    foreach ($radicados as $radicado){
                                        echo '<span class="badge badge-pill badge-primary">'.$radicado.'</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="/servicios/pqr/respuesta/get/documento/{{$respuesta->uuid}}" class="btn btn-secondary">Ver documento</a>
                                </td>
                                <td>
                                    @if($respuesta->anexos == null)
                                        Sin anexos
                                    @else
                                        <a href="/servicios/pqr/respuesta/get/anexos/{{$respuesta->uuid}}" class="btn btn-secondary">Ver anexos</a>
                                    @endif
                                </td>
                                @if($respuesta->hasEnvio != null)
                                    <td>
                                        {{$respuesta->hasEnvio->hasModalidadEnvio->name}}
                                    </td>
                                    <td>
                                        @if($respuesta->hasEnvio->hasEmpresaMensajeria != null)
                                            {{$respuesta->hasEnvio->hasEmpresaMensajeria->name}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$respuesta->hasEnvio->numero_guia}}
                                    </td>
                                    <td>
                                        {{$respuesta->hasEnvio->fecha_hora_envio}}
                                    </td>
                                    <td>
                                        {{$respuesta->hasEnvio->fecha_hora_envio}}
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach                    
                @endif
            </div>
        </div>
        <a href="{{url('/servicios/pqr/estado')}}" class="btn btn-primary">Consultar otro proceso</a>
        <button type="button" class="btn btn-secondary" onclick="printJS({ printable: 'imprimir', type: 'html', header: '{{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}' })"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir</button>
    </div>
@endsection

@section('scripts')
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
@endsection