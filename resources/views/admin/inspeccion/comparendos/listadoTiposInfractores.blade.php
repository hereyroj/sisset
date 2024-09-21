<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposInfractores();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoInfractor();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="2">TIPOS INFRACTORES</th>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tiposInfractores as $tipo)
        <tr>
            <td>{{$tipo->name}}</td>
            <td><button type="button" class="btn btn-secondary" onclick="editarTipoInfractor({{$tipo->id}})">Editar</button></td>
        </tr>    
        @endforeach
    </tbody>
</table>                