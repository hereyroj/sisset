<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerClases();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevaClase();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva Clase
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="5">CLASES DE PQR</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Tipo día</th>
                <th>Cantidad días</th>
                <th>Requiere respuesta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clases as $clase)
            <tr @if($clase->trashed()) class="danger" @endif >
                <td>{{$clase->name}}</td>
                <td>{{$clase->dia_clase}}</td>
                <td>{{$clase->dia_cantidad}}</td>
                <td>{{$clase->required_answer}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarClase({{$clase->id}})">Editar</button>                @if($clase->trashed())
                    <button type="button" class="btn btn-primary  btn-block" onclick="restaurarClase({{$clase->id}})">Restaurar</button>                @else
                    <button type="button" class="btn btn-danger  btn-block" onclick="eliminarClase({{$clase->id}})">Eliminar</button>                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$clases->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>