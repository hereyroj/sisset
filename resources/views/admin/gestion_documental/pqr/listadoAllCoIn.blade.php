<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerCoIn();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroCoIn', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroCoIn', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarCoIn" id="filtrarCoIn" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarCoIn();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerCoIn();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="crearCoIn();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo PQR
        </button>
    </div>
    <div class="text-center" id="bar-navigation">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped tblCoIn">
        <thead>
            <tr>
                <th colspan="1" rowspan="2">Radicado</th>
                <th colspan="2" rowspan="1">Información del Funcionario</th>
                <th colspan="4" rowspan="1">Información del Proceso</th>
                <th colspan="4" rowspan="1">Información del Tiempo</th>
                <th rowspan="2" colspan="1"><span>Estado</span></th>
                <th rowspan="2" colspan="1"><span>Respuesta</span></th>
            </tr>
            <tr>
                <th rowspan="1">Oficina Funcionario</th>
                <th rowspan="1">Nombre Funcionario</th>
                <th rowspan="1">Asunto</th>
                <th rowspan="1">Clase</th>
                <th rowspan="1">Medio de Traslado</th>
                <th rowspan="1">Clasificación</th>
                <th rowspan="1">Límite Respuesta</th>
                <th rowspan="1">Previo aviso</th>
                <th rowspan="1">Días Restantes</th>
                <th rowspan="1">Días Pasados</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pqrs as $pqr)
            <tr @if($pqr->hasAnulacion != null) class="anulada" @elseif($pqr->hasClase->required_answer === 'SI') @if($pqr->diasPasados() >=
                1 && $pqr->hasRespuestas->count()
                <=0 ) class="danger" @elseif($pqr->diasRestantes()
                    <=3 && $pqr->hasRespuestas->count()
                        <=0 ) class="warning" @elseif($pqr->hasRespuestas->count() > 0 && $pqr->diasPasados() == null) class="success" @elseif($pqr->hasRespuestas->count()
                            > 0 && $pqr->diasPasados() >= 1) class="info" @endif @elseif($pqr->hasRespuestas->count() > 0) class="success"
                            @endif >
                            <td>
                                @if($pqr->getRadicadoEntrada != null) {{$pqr->getRadicadoEntrada->numero}}
                                <br> {{$pqr->getRadicadoEntrada->created_at->format('Y-m-d H:i:s')}} @endif
                            </td>
                            <td>{{$pqr->hasPeticionario->couldHaveFuncionario->hasDependencia->name}}</td>
                            <td><button type="button" class="btn btn-secondary" onclick="cambiarFuncionario({{$pqr->id.','.$pqr->hasPeticionario->couldHaveFuncionario->id}});">{{$pqr->hasPeticionario->couldHaveFuncionario->name}}</button></td>
                            <td>
                                <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                            </td>
                            <td><button type="button" class="btn btn-secondary" onclick="cambiarClase({{$pqr->id}})">{{$pqr->hasClase->name}}</button></td>
                            <td>
                                <button type="button" class="btn btn-secondary" onclick="cambiarMedioTraslado({{$pqr->id}})">{{$pqr->getMedioTraslado->name}}</button>
                            </td>
                            <td>
                                @if($pqr->hasClasificacion == null)
                                <button type="button" class="btn btn-secondary  btn-block" onclick="clasificarPqr({{$pqr->id}})">Clasificar</button>                            @else
                                <button type="button" class="btn btn-secondary  btn-block" onclick="verClasificacion({{$pqr->hasClasificacion->id.','.$pqr->id}})">Ver</button>                            @endif
                            </td>
                            <td>@if($pqr->limite_respuesta != null && $pqr->hasRespuestas->count()
                                <=0 )<button type="button" class="btn btn-primary"
                                    onclick="cambiarFechaLimite({{$pqr->id}});"> {{$pqr->limite_respuesta}} </button> @else {{$pqr->limite_respuesta}} @endif</td>
                            <td>{{$pqr->previo_aviso}}</td>
                            <td>{{$pqr->diasRestantes()}}</td>
                            <td>{{$pqr->diasPasados()}}</td>
                            <td>
                                @if($pqr->hasAnulacion == null)
                                <button class="btn btn-danger btn-block" onclick="anularProceso({{$pqr->id}})">Anular</button>                            @if($pqr->hasAsignaciones->count()
                                <=0 ) <button class="btn btn-secondary  btn-block" onclick="asignarPqr({{$pqr->id}})">Asignar</button>
                                    @elseif($pqr->hasAsignaciones->count() > 0)
                                    <button type="button" class="btn btn-secondary  btn-block" onclick="verHistorialAsignaciones({{$pqr->id}})">Examinar
                            </button> @if($pqr->hasRespuesta == null)
                                    <button class="btn btn-secondary  btn-block" onclick="reAsignar({{$pqr->id}})">Reasignar
                                </button> @endif @endif @else
                                    <button type="button" class="btn btn-secondary btn-block" onclick="verAnulacion({{$pqr->id}})">Anulado</button>                                @endif
                            </td>
                            <td>
                                @foreach($pqr->hasRespuestas as $respuesta)
                                <button class="btn btn-secondary  btn-block" onclick="verRespuesta({{$respuesta->id}})">Ver</button>                            @endforeach
                            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>