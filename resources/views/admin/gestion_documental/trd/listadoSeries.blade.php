<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoEdictos" onclick="obtenerSeries();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div class="field-search input-group">
        <input type="text" name="serieBusqueda" id="serieBusqueda" placeholder="Buscar por placa, código o empresa" @if(isset($parametro))
            value="{{$parametro}}" @endif>
        <button type="button" class="btn-buscar" onclick="serieBusqueda();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerSeries();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" id="btnCrearSerie" onclick="crearSerie();">
            <i class="fas fa-sync"></i> Crear serie
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Dependencia</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($series as $serie)
            <tr>
                <td>
                    {{$serie->hasDependencia->name}}
                </td>
                <td>
                    {{$serie->name}}
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarSerie({{$serie->id}});">Editar</button>
                    <button type="button" class="btn btn-secondary" onclick="eliminarSerie({{$serie->id}});">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$series->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>