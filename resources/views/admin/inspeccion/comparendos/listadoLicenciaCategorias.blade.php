<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerLicenciaCategorias();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevaLicenciaCategoria();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<table class="table table-striped ">
    <thead>
        <tr>
            <th colspan="2">CATEGORIA LICENCIA</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($licenciaCategorias as $licenciaCategoria)
        <tr>
            <td>{{$licenciaCategoria->name}}</td>
            <td>
                <button type="button" class="btn btn-secondary  btn-block" onclick="editarLicenciaCategoria({{$licenciaCategoria->id}})">Editar</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>