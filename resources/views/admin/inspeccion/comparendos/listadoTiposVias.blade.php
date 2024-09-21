<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposVias();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoVia();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="2">TIPOS VIAS</th>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tiposVias as $tipo)
        <tr>
            <td>{{$tipo->name}}</td>
            <td><button type="button" class="btn btn-secondary" onclick="editarTipoVia({{$tipo->id}})">Editar</button></td>
        </tr>    
        @endforeach
    </tbody>
</table>     