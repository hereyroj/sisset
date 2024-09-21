<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerVehiculos();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('filtroVehiuclos', $filtros, $sFiltro, ['class'=>'form-control', 'id'=>'filtroVehiculos', 'style' => 'border-radius:0;height:40px;'])
        !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarVehiculos" id="filtrarVehiculos" @if(isset($parametro)) value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="filtrarVehiculos();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerVehiculos();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoVehiculo();">
            <i class="fas fa-sync"></i> Crear vehículo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped" style="text-align: center;">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Tipo de vehículo</th>
                <th>Tipo de carrocería</th>
                <th>Marca</th>
                <th>Línea</th>
                <th>Modelo</th>
                <th>Clase de combustible</th>
                <th>Número de motor</th>
                <th>Número de chasis</th>
                <th>Capacidad de pasajeros</th>
                <th>Capacidad de toneladas</th>
                <th>Empresa afiliación</th>
                <th>Tarjetas de operación</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiculos as $vehiculo)
            <tr>
                <td>
                    {{$vehiculo->placa}}
                </td>
                <td>
                    {{$vehiculo->hasTipoVehiculo->name}}
                </td>
                <td>
                    {{$vehiculo->hasTipoCarroceria->name}}
                </td>
                <td>
                    {{$vehiculo->hasMarca->name}}
                </td>
                <td>
                    @if($vehiculo->hasLinea != null){{$vehiculo->hasLinea->nombre}}@endif
                </td>
                <td>
                    {{$vehiculo->modelo}}
                </td>
                <td>
                    {{$vehiculo->hasClaseCombustible->name}}
                </td>
                <td>
                    {{$vehiculo->numero_motor}}
                </td>
                <td>
                    {{$vehiculo->numero_chasis}}
                </td>
                <td>
                    {{$vehiculo->capacidad_pasajeros}}
                </td>
                <td>
                    {{$vehiculo->capacidad_toneladas}}
                </td>
                <td>
                    @if($vehiculo->hasEmpresaActiva() != null)
                    <button type="button" class="btn btn-danger btn-block" onclick="verVinculacion({{$vehiculo->id}});">{{$vehiculo->hasEmpresaActiva()->name}}</button>                @else
                    <button type="button" class="btn btn-danger btn-block" onclick="vincularEmpresa({{$vehiculo->id}});">Vincular</button>
                    @endif
                </td>
                <td>
                    @foreach($vehiculo->hasTOS as $to)
                    <button type="button" class="btn btn-warning btn-block" onclick="verTO({{$to->id}});">{{$to->id}}</button>                @endforeach
                </td>
                <td>
                    <button type="button" class="btn btn-secondary btn-block" onclick="editarVehiculo({{$vehiculo->id}});">Editar</button>
                    <button type="button" class="btn btn-success btn-block" onclick="propietariosVehiculo({{$vehiculo->id}});">Prop.</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$vehiculos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>