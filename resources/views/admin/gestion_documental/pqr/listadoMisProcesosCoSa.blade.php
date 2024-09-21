<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="misCoSa();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroCoSa', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroCoSa', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarCoSa" id="filtrarCoSa" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarMisCoSa();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="misCoSa();">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped tblCoIn">
        <thead>
            <tr>
                <th>Radicado</th>
                <th>Asunto</th>
                <th>Radicados respuesta</th>
                <th>Medio de Traslado</th>
                <th>Clase</th>
                <th>Clasificación</th>
                <th>Envío</th>
                <th>Entrega</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pqrs as $pqr)
            <tr>
                <td>
                    {{$pqr->getRadicadoSalida->numero}}<br> {{$pqr->getRadicadoSalida->created_at->format('Y-m-d H:i:s')}}
                </td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verAsunto({{$pqr->id}})">Ver</button>
                </td>
                <td>
                    <?php
                    $radicados = explode(',', $pqr->radicados_respuesta);
                    foreach ($radicados as $radicado) {
                        echo '<span class="badge badge-pill badge-primary">' . $radicado . '</span>';
                    }
                    ?>
                </td>
                <td>{{$pqr->hasClase->name}}</td>
                <td>
                    {{$pqr->getMedioTraslado->name}}
                </td>
                <td>
                    @if($pqr->hasClasificacion != null)
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verClasificacion({{$pqr->hasClasificacion->id.','.$pqr->id}})">Ver</button>                @endif
                </td>
                <td>
                    @if($pqr->hasEnvio != null)
                    <button type="button" class="btn btn-primary" onclick="verEnvio({{$pqr->id}});">Ver</button> @else
                    <button type="button" class="btn btn-primary" onclick="registrarEnvio({{$pqr->id}});">Registrar envío</button>                @endif
                </td>
                <td>
                    @if($pqr->hasEntrega == null)
                    <button type="button" class="btn btn-secondary  btn-block" onclick="registrarEntrega({{$pqr->id}})">Registrar</button>                @else
                    <button type="button" class="btn btn-secondary  btn-block" onclick="verEntrega({{$pqr->hasEntrega->id}})">Ver</button>                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$pqrs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>