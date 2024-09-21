<table class="table table-striped">
    <thead>
    <tr>
        <th rowspan="2"><input type="checkbox" name="selectAll" id="selectAll" onchange="selectAll(this);"></th>
        <th colspan="6">Info comparendo</th>
        <th rowspan="2">Info inmovilización</th>
        <th rowspan="2">Info ubicación</th>
        <th rowspan="2">Info testigo</th>
        <th rowspan="2">Info agente</th>
        <th rowspan="2">Info vehículo</th>
        <th rowspan="2">Info infractor</th>
        <th rowspan="2">Info pago</th>
        <th rowspan="2">Acción</th>
    </tr>
    <tr>
        <th>Estado</th>
        <th>Número</th>
        <th>Fecha y hora</th>
        <th>Infracción</th>
        <th>Valor</th>
        <th>Documento</th>
    </tr>
    </thead>
    <tbody>
    @foreach($comparendos as $comparendo)
        <tr>
            <th><input type="checkbox" name="comparendos[]" id="{{$comparendo->id}}" value="{{$comparendo->id}}"></th>
            <th>{{$comparendo->getEstado()}}</th>
            <td>{{$comparendo->numero}}</td>
            <td>{{$comparendo->fecha_realizacion}}</td>
            <td>{{$comparendo->hasInfraccion->name}}</td>
            @if($comparendo->valor != null && $comparendo->valor !== ' ')
                <td>${{$comparendo->valor}}</td>
            @else
                <td></td>
            @endif
            <td><a href="/admin/inspeccion/comparendos/obtenerComparendo/{{$comparendo->id}}" class="btn btn-secondary btn-block">Ver</a></td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verInmovilizacion({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verUbicacion({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verTestigo({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verAgente({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verVehiculo({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="verInfractor({{$comparendo->id}});">Ver</button>
            </td>
            <td>
                @if($comparendo->hasPago === null)
                    <button type="button" class="btn btn-secondary btn-block" onclick="registrarPago({{$comparendo->id}});">Registrar</button>
                @else
                    <button type="button" class="btn btn-secondary btn-block" onclick="verPago({{$comparendo->id}});">Ver</button>
                @endif
            </td>
            <td>
                <button type="button" class="btn btn-secondary btn-block" onclick="editarComparendo({{$comparendo->id}});">Editar comparendo</button>
                @if($comparendo->hasPago != null)<button type="button" class="btn btn-secondary btn-block" onclick="editarPago({{$comparendo->id}});">Editar pago</button>@endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center">
    {{$comparendos->links('vendor.pagination.bootstrap-4')}}
</div>
<div class="btn-group dropup">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Para todas las seleccionadas <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="#" onclick="sancionarComparendos();">Sancionar</a></li>
    </ul>
</div>