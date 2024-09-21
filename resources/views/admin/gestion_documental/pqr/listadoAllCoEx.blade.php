<div class="row table-bar">
    <div class="col-sm-12 col-mg-6 col-lg-6">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-primary" type="button" title="Actualizar" onclick="obtenerCoEx();"><i class="fas fa-sync-alt"></i> Actualizar</button>
            </div>
            <div class="input-group-prepend">
                {!! Form::select('filtroCoEx', $filtros, $sFiltro, ['class'=>'custom-select', 'id'=>'filtroCoEx']) !!}
            </div>
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="filtrar-coex" id="filtrarCoEx">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" title="Buscar" onclick="filtrarCoEx();"><i class="fas fa-search"></i></button>
                <button class="btn btn-danger" type="button" title="Limpiar" onclick="obtenerCoEx();"><i class="fas fa-times"></i></button>
                <button class="btn btn-info" type="button" title="Nuevo" onclick="crearCoEx();"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-mg-6 col-lg-6">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
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
            <th>Asignaciones</th>
            <th>Respuesta</th>
        </trtr>
        </thead>
        <tbody>
        @foreach ($pqrs as $pqr)
            <tr
                @if($pqr->hasAnulacion != null)
                    class="anulada"
                @elseif($pqr->hasClase->required_answer === 'SI')
                    @if($pqr->diasPasados() >= 1 && $pqr->hasRespuestas->count() <= 0)
                    class="danger"
                    @elseif($pqr->diasRestantes() <=3 && $pqr->hasRespuestas->count() <= 0)
                    class="warning"
                    @elseif($pqr->hasRespuestas->count() > 0 && $pqr->diasPasados() == null)
                    class="success"
                    @elseif($pqr->hasRespuestas->count() > 0 && $pqr->diasPasados() >= 1)
                    class="info"
                    @endif
                @elseif($pqr->hasRespuestas->count() > 0)
                    class="success"
                @endif
            >
                <td>
                    @if($pqr->getRadicadoEntrada != null)
                        {{$pqr->getRadicadoEntrada->numero}}<br>
                        {{$pqr->getRadicadoEntrada->created_at->format('Y-m-d H:i:s')}}
                    @endif
                </td>
                <td><button type="button" class="btn btn-secondary" onclick="cambiarClase({{$pqr->id}})">{{$pqr->hasClase->name}}</button></td>
                <td>{{$pqr->hasPeticionario->nombre_completo}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="cambiarMedioTraslado({{$pqr->id}})">{{$pqr->getMedioTraslado->name}}</button>
                </td>
                <td>
                    @if($pqr->hasClasificacion == null)
                        <button type="button" class="btn btn-secondary  btn-block" onclick="clasificarPqr({{$pqr->id}})">Clasificar</button>
                    @else
                        <button type="button" class="btn btn-secondary  btn-block" onclick="verClasificacion({{$pqr->hasClasificacion->id.','.$pqr->id}})">Ver</button>
                    @endif
                </td>
                <td>@if($pqr->limite_respuesta != null && $pqr->hasRespuestas->count() <= 0)<button type="button" class="btn btn-primary" onclick="cambiarFechaLimite({{$pqr->id}});"> {{$pqr->limite_respuesta}} </button> @else {{$pqr->limite_respuesta}} @endif</td>
                <td>{{$pqr->previo_aviso}}</td>
                <td>{{$pqr->diasRestantes()}}</td>
                <td>{{$pqr->diasPasados()}}</td>
                <td>
                    @if($pqr->hasAnulacion == null)
                        <div class="btn-group" role="group" aria-label="Basic example">
                        <button class="btn btn-warning btn-sm" onclick="anularProceso({{$pqr->id}})" title="Anular"><i class="fas fa-ban"></i></button>
                        @if($pqr->hasAsignaciones->count() <= 0)
                            <button class="btn btn-secondary btn-sm" onclick="asignarPqr({{$pqr->id}})" title="Asignar"><i class="fas fa-tasks"></i></button>
                        @elseif($pqr->hasAsignaciones->count() > 0)
                            <button type="button" class="btn btn-primary btn-sm" onclick="verHistorialAsignaciones({{$pqr->id}})" title="Ver asignaciones"><i class="fas fa-eye"></i></button>
                            @if($pqr->hasRespuesta == null)
                                <button class="btn btn-info btn-sm" onclick="reAsignar({{$pqr->id}})" title="Modificar asignaciones"><i class="fas fa-edit"></i></button>
                            @endif
                        @endif
                        </div>
                    @else
                        <button type="button" class="btn btn-secondary btn-block" onclick="verAnulacion({{$pqr->id}})">Anulado</button>
                    @endif
                </td>
                <td>
                    @foreach($pqr->hasRespuestas as $respuesta)
                        <button class="btn btn-secondary  btn-block" onclick="verRespuesta({{$respuesta->id}})">Ver</button>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>