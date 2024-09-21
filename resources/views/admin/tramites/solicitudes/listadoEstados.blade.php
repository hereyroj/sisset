<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerEstados();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoEstado();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo Estado
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="4">ESTADOS</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Finaliza el servicio</th>
                <th>Requiere observación</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $estado)
            <tr>
                <td>{{$estado->name}}</td>
                <td>{{$estado->finaliza_servicio}}</td>
                <td>{{$estado->requiere_observacion}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarEstado({{$estado->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$estados->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>