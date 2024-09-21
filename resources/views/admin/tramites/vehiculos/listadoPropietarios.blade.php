<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="propietariosVehiculoUpdate({{$vehiculo->id}});">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoPropietario({{$vehiculo->id}});">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Crear
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Tipo de documento</th>
            <th>Número de documento</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo electrónico</th>
            <th>Dpto residencia</th>
            <th>Municipio residencia</th>
            <th>Dirección</th>
            <th>Vehículos activos</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($vehiculo->hasPropietarios as $propietario)
            <tr>
                <td>{{$propietario->hasTipoDocumento->name}}</td>
                <td>{{$propietario->numero_documento}}</td>
                <td>{{$propietario->nombre}}</td>
                <td>{{$propietario->telefono}}</td>
                <td>{{$propietario->correo_electronico}}</td>
                <td>{{$propietario->hasDepartamento->name}}</td>
                <td>{{$propietario->hasMunicipio->name}}</td>
                <td>{{$propietario->direccion}}</td>
                <td>
                    @foreach($propietario->hasVehiculos as $vehiculo)
                        @if($vehiculo->pivot->estado == 1)
                        <span class="badge badge-pill badge-primary">{{$vehiculo->placa}}</span>
                        @endif
                    @endforeach
                </td>
                <th>
                    @if($vehiculo->pivot->estado == 1)
                        Activo
                    @else
                        Inactivo
                    @endif
                </th>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarPropietario({{$propietario->id.','.$vehiculo->id}});">Editar</button>
                    @if($vehiculo->pivot->estado == 1)
                        <button type="button" class="btn btn-danger" onclick="retirarPropietario({{$propietario->id.','.$vehiculo->id}});">Retirar</button>
                    @else
                        <button type="button" class="btn btn-success" onclick="vincularPropietario({{$propietario->id.','.$vehiculo->id}});">Vincular</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>