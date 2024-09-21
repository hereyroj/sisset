<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerOrigenes();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoOrigen();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Origen
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">ORIGENES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($origenes as $origen)
            <tr>
                <td>{{$origen->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarOrigen({{$origen->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$origenes->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>