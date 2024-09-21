<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposSustratos();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoSustrato();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Tipo Sustrato
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="4">TIPOS SUSTRATOS</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>{{$tipo->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarTipoSustrato({{$tipo->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$tipos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>