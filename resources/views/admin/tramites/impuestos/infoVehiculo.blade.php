<form>
    <h4>Información técnica</h4>
    <div class="form-group">
        <label class="control-label">Placa</label>
        <input type="text" class="form-control" value="{{$vehiculo->placa}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Modelo</label>
        <input type="text" class="form-control" value="{{$vehiculo->modelo}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Número motor</label>
        <input type="text" class="form-control" value="{{$vehiculo->numero_motor}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Número chasis</label>
        <input type="text" class="form-control" value="{{$vehiculo->numero_chasis}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Capacidad de pasajeros</label>
        <input type="text" class="form-control" value="{{$vehiculo->capacidad_pasajeros}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Capacidad de toneladas</label>
        <input type="text" class="form-control" value="{{$vehiculo->capacidad_toneladas}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Clase</label>
        <input type="text" class="form-control" value="{{$vehiculo->hasTipoVehiculo->name}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Marca</label>
        <input type="text" class="form-control" value="{{$vehiculo->hasMarca->name}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Linea</label>
        @if($vehiculo->hasLinea != null)
            <input type="text" class="form-control" value="{{$vehiculo->hasLinea->name}}" disabled>
        @else
            <input type="text" class="form-control" value="" disabled>
        @endif
    </div>
    <div class="form-group">
        <label class="control-label">Tipo de carroceria</label>
        <input type="text" class="form-control" value="{{$vehiculo->hasTipoCarroceria->name}}" disabled>
    </div>
    <div class="form-group">
        <label class="control-label">Clase de combustible</label>
        <input type="text" class="form-control" value="{{$vehiculo->hasClaseCombustible->name}}" disabled>
    </div>
    <hr>
    <h4>Información del/los propietario(s)</h4>
    @foreach($vehiculo->hasPropietarios as $propietario)
        <div class="form-group">
            <label class="control-label">Nombre</label>
            <input type="text" class="form-control" value="{{$propietario->nombre}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Tipo documento</label>
            <input type="text" class="form-control" value="{{$propietario->hasTipoDocumento->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Número documento</label>
            <input type="text" class="form-control" value="{{$propietario->numero_documento}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Teléfono</label>
            <input type="text" class="form-control" value="{{$propietario->telefono}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Departamento</label>
            <input type="text" class="form-control" value="{{$propietario->hasDepartamento->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Municipio</label>
            <input type="text" class="form-control" value="{{$propietario->hasMunicipio->name}}" disabled>
        </div>
        <div class="form-group">
            <label class="control-label">Dirección</label>
            <input type="text" class="form-control" value="{{$propietario->direccion}}" disabled>
        </div>
        <hr>
    @endforeach
</form>