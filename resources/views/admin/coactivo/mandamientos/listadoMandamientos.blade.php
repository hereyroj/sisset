<div class="row table-bar">
    <div class="col-sm-12 col-mg-6 col-lg-6">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-primary" type="button" title="Actualizar" onclick="obtenerMandamientos();"><i class="fas fa-sync-alt"></i> Actualizar</button>
            </div>
            <div class="input-group-prepend">
                {!! Form::select('filtroMandamientos', $filtros, $sFiltro, ['class'=>'custom-select', 'id'=>'filtroMandamientos']) !!}
            </div>
            <input type="text" class="form-control" placeholder="Buscar" aria-label="Buscar" aria-describedby="filtrar-mandamientos" id="filtrarMandamientos">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" title="Buscar" onclick="filtrarMandamientos();"><i class="fas fa-search"></i></button>
                <button class="btn btn-danger" type="button" title="Limpiar" onclick="obtenerMandamientos();"><i class="fas fa-times"></i></button>
                <button class="btn btn-info" type="button" title="Nuevo" onclick="nuevoMandamiento();"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-mg-6 col-lg-6">
        {{$mandamientos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="7">Información Mandamiento</th>
                <th rowspan="2">Notificaciones</th>
                <th rowspan="2">Finalización</th>
                <th rowspan="2">Pago</th>
                <th rowspan="2">Acciones</th>
            </tr>
            <tr>
                <th>Estado</th>
                <th>Tipo proceso</th>
                <th>Número proceso</th>
                <th># Mandamiento</th>
                <th>Fecha mandamiento</th>
                <th>Valor</th>
                <th>Mandamiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mandamientos as $mandamiento)
            <tr>
                <td>{{$mandamiento->getEstado()}}</td>
                <td>
                    @if($mandamiento->proceso_type == 'App\comparendo')
                    <button type="button" class="btn btn-primary" onclick="verComparendo({{$mandamiento->hasProceso->id}})">Comparendo</button>
                    @else 
                    <button type="button" class="btn btn-primary" onclick="verAcuerdoPago({{$mandamiento->hasProceso->id}})">Acuerdo Pago</button>
                    @endif
                </td>
                <td>
                    @if($mandamiento->proceso_type == 'App\comparendo')
                        {{$mandamiento->hasProceso->numero}}
                    @else
                        {{$mandamiento->hasProceso->numero_acuerdo}}
                    @endif
                </td>
                <td>{{$mandamiento->consecutivo}}</td>
                <td>{{$mandamiento->fecha_mandamiento}}</td>
                <td>{{$mandamiento->valor}}</td>
                <td><a class="btn btn-outline-secondary" href="{{url('admin/coactivo/mandamientos/obtenerDocumentoMandamiento/'.$mandamiento->id)}}">Ver</a></td>
                <td>
                    @if($mandamiento->hasNotificaciones->count() > 0)<button type="button" class="btn btn-outline-secondary  btn-block" onclick="verNotificaciones({{$mandamiento->id}})">Ver</button>@endif       
                    <button type="button" class="btn btn-primary btn-block" onclick="nuevaNotificacion({{$mandamiento->id}})">Nueva</button>         
                </td>
                <td>
                    @if($mandamiento->hasFinalizacion != null)
                    <button type="button" class="btn btn-outline-secondary  btn-block" onclick="verFinalizacion({{$mandamiento->hasFinalizacion->id}})">Ver</button>
                    @else
                    <button type="button" class="btn btn-primary btn-block" onclick="registrarFinalizacion({{$mandamiento->id}})">Registrar</button>
                    @endif
                </td>
                <td>
                    @if($mandamiento->hasPago === null)
                        <button type="button" class="btn btn-secondary btn-block" onclick="registrarPago({{$mandamiento->id}});">Registrar</button>
                    @else
                        <button type="button" class="btn btn-secondary btn-block" onclick="verPago({{$mandamiento->id}});">Ver</button>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-outline-secondary  btn-block" onclick="editarMandamiento({{$mandamiento->id}})">Editar</button>
                    @if($mandamiento->hasPago != null)<button type="button" class="btn btn-secondary btn-block" onclick="editarPago({{$mandamiento->id}});">Editar pago</button>@endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$mandamientos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>