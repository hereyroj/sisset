<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerModalidadesPago();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevaModalidadPago();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">MODALIDADES PAGO</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modalidadesPago as $modalidadPago)
            <tr>
                <td>{{$modalidadPago->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarModalidadPago({{$modalidadPago->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$modalidadesPago->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>