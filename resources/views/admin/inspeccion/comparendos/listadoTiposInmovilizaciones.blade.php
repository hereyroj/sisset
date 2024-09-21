<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposInmovilizaciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoInmovilizacion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">TIPOS INMOVILIZACIONES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposInmovilizaciones as $tipoInmovilizacion)
            <tr>
                <td>{{$tipoInmovilizacion->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarTipoInmovilizacion({{$tipoInmovilizacion->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tiposInmovilizaciones->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>