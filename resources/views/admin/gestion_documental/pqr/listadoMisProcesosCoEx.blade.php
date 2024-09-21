<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="misCoEx();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroMisCoEx', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroCoEx', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarCoEx" id="filtrarCoEx" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarMisCoEx();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="misCoEx();">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Radicado</th>
                <th>Clase</th>
                <th>Nombre Peticionario</th>
                <th>Asunto</th>
                <th>Medio de Traslado</th>
                <th>Clasificación</th>
                <th>Límite Respuesta</th>
                <th>Previo aviso</th>
                <th>Días Restantes</th>
                <th>Días Pasados</th>
                <th>Estado</th>
                <th>Respuesta</th>
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
                            <td>{{$pqr->hasClase->name}}</td>
                            <td><button type="button" class="btn btn-secondary  btn-block" onclick="verPeticionario({{$pqr->id}})">{{$pqr->hasPeticionario->nombre_completo}}</button></td>
                            <td>
                                <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                            </td>
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