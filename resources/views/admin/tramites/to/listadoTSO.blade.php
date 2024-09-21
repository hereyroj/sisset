<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoEdictos" onclick="obtenerTSO();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarBusqueda" id="filtrarBusqueda" placeholder="Buscar por placa, código o empresa" @if(isset($parametro))
            value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarBusqueda();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerTSO();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevaTO();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th rowspan="2" class="center-th" style="border-right: 2px solid #fff">Código Tarjeta</th>
                <th colspan="2" class="center-th">Vigencia</th>
                <th colspan="5" class="center-th">Información del vehículo</th>
                <th colspan="4" class="center-th">Información de la empresa</th>
                <th colspan="2" class="center-th">Gestión</th>
            </tr>
            <tr>
                <th>Vencimiento</th>
                <th>Expedición</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Número de motor</th>
                <th>Clase de combustible</th>
                <th>Razón social</th>
                <th>Nivel del servicio</th>
                <th>Número interno</th>
                <th>Radio de operación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tos as $to)
            <?php
            if ($to->fecha_vencimiento <= \Carbon\Carbon::now()->toDateString()) {
                echo '<tr class="tarjeta-vencida">';
            } elseif ($to->fecha_vencimiento <= \Carbon\Carbon::now()->addMonths(3)->toDateString()) {
                echo '<tr class="tarjeta-sobre-vencimiento">';
            }
            ?>
                <td style="text-align: center;">{{$to->id}}</td>
                <td>{{$to->fecha_vencimiento}}</td>
                <td>{{$to->created_at}}</td>
                <td>{{$to->placa}}</td>
                <td>{{$to->hasMarca->name}}</td>
                <td>{{$to->modelo}}</td>
                <td>{{$to->numero_motor}}</td>
                <td>{{$to->hasClaseCombustible->name}}</td>
                <td>{{$to->hasEmpresaTransporte->name}}</td>
                <td>{{$to->hasNivelServicio->name}}</td>
                <td>{{$to->numero_interno}}</td>
                <td>{{$to->hasRadioOperacion->name}}</td>
                <td>
                    <a href="#" class="btn btn-success btn-block" onclick="event.preventDefault();editarTO({{$to->id}});"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
                    <a class="btn btn-primary btn-block" href="imprimir/{{$to->id}}"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir</a>
                    <a href="#" class="btn btn-warning btn-block" onclick="event.preventDefault();verTO({{$to->id}});"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a>
                </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>