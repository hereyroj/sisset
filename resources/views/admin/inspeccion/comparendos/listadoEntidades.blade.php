<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerEntidades();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevaEntidad();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<table class="table table-striped ">
    <thead>
        <tr>
            <th colspan="2">ENTIDADES</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entidades as $entidad)
        <tr>
            <td>{{$entidad->name}}</td>
            <td>
                <button type="button" class="btn btn-secondary  btn-block" onclick="editarEntidad({{$entidad->id}})">Editar</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>