<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="misCoIn();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroMisCoIn', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroCoIn', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarCoIn" id="filtrarCoIn" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarMisCoIn();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="misCoIn();">
            <i class="fas fa-times"></i>
        </button>
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
                            <td>
                                {{$pqr->hasPeticionario->couldHaveFuncionario->hasDependencia->name}}
                            </td>
                            <td>
                                {{$pqr->hasPeticionario->couldHaveFuncionario->name}}
                            </td>
                            <td>
                                <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                            </td>
                            <td>{{$pqr->hasClase->name}}</td>
                            <td>
                                {{$pqr->getMedioTraslado->name}}
                            </td>
                            <td>
                                @if($pqr->hasClasificacion != null)
                                <button type="button" class="btn btn-secondary  btn-block" onclick="verClasificacion({{$pqr->hasClasificacion->id.','.$pqr->id}})">Ver</button>                            @endif
                            </td>
                            <td>{{$pqr->limite_respuesta}}</td>
                            <td>{{$pqr->previo_aviso}}</td>
                            <td>{{$pqr->diasRestantes()}}</td>
                            <td>{{$pqr->diasPasados()}}</td>
                            <td>
                                <button type="button" class="btn @if($pqr->comprobarUsuarioResponsable(auth()->user()->id)) btn-primary @else btn-secondary @endif  btn-block"
                                    onclick="verHistorialAsignaciones({{$pqr->id}})">Examinar</button>
                            </td>
                            <td>
                                @foreach($pqr->hasRespuestas as $respuesta)
                                <button class="btn btn-secondary btn-block" onclick="verRespuesta({{$respuesta->id}})">Ver</button>                            @endforeach
                            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>