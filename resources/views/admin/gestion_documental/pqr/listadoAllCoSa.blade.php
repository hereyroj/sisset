<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerCoSa();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroCoSa', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroCoSa', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarCoSa" id="filtrarCoSa" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarCoSa();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerCoSa();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="crearCoSa();">
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
                <th>Radicado</th>
                <th>Oficina funcionario</th>
                <th>Funcionario</th>
                <th>Radicados respuesta</th>
                <th>Asunto</th>
                <th>Medio de Traslado</th>
                <th>Clase</th>
                <th>Clasificación</th>
                <th>Información de envío</th>
                <th>Información de entrega</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pqrs as $pqr)
            <tr @if($pqr->hasAnulacion != null) class="anulada" @endif >
                <td>
                    {{$pqr->getRadicadoSalida->numero}}<br> {{$pqr->getRadicadoSalida->created_at->format('Y-m-d H:i:s')}}
                </td>
                <td>{{$pqr->hasPeticionario->couldHaveFuncionario->hasDependencia->name}}</td>
                <td><button type="button" class="btn btn-secondary" onclick="cambiarFuncionario({{$pqr->id.','.$pqr->hasPeticionario->couldHaveFuncionario->id}});">{{$pqr->hasPeticionario->couldHaveFuncionario->name}}</button></td>
                <td>
                    @if($pqr->radicados_respuesta != null) @foreach(explode(',', $pqr->radicados_respuesta) as $radicado)
                    <button type="button" class="btn btn-primary  btn-block" onclick="opcionesRadicadoContestacion('{{$pqr->id}}','{{$radicado}}')">{{$radicado}}</button>                @endforeach
                    <button type="button" class="btn btn-secondary  btn-block" onclick="vincularRadicadosEntrada('{{$pqr->id}}')">Vincular</button>                @else
                    <button type="button" class="btn btn-secondary  btn-block" onclick="vincularRadicadosEntrada('{{$pqr->id}}')">Vincular</button>                @endif
                </td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="cambiarMedioTraslado({{$pqr->id}})">{{$pqr->getMedioTraslado->name}}</button>
                </td>
                <td><button type="button" class="btn btn-secondary" onclick="cambiarClase({{$pqr->id}})">{{$pqr->hasClase->name}}</button></td>
                <td>
                    @if($pqr->hasClasificacion == null)
                    <button type="button" class="btn btn-secondary  btn-block" onclick="clasificarPqr({{$pqr->id}})">Clasificar</button>                @else
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verClasificacion({{$pqr->hasClasificacion->id.','.$pqr->id}})">Ver</button>                @endif
                </td>
                <td>
                    @if($pqr->hasEnvio != null)
                    <button type="button" class="btn btn-secondary btn-block" onclick="verEnvio({{$pqr->id}})">Ver</button> @endif
                </td>
                <td>
                    @if($pqr->hasEntrega != null)
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verEntrega({{$pqr->hasEntrega->id}})">Ver</button>                @endif
                </td>
                <td>
                    @if($pqr->hasAnulacion == null)
                    <button class="btn btn-danger btn-block" onclick="anularProceso({{$pqr->id}})">Anular</button> @else
                    <button type="button" class="btn btn-secondary btn-block" onclick="verAnulacion({{$pqr->id}})">Anulado</button>                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>