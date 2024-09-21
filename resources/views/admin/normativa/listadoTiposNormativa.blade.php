<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerTiposNormativa();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevoTipoNormativa();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="2">TIPOS NORMATIVA</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposNormativa as $tipoNormativa)
            <tr>
                <td>{{$tipoNormativa->name}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarTipoNormativa({{$tipoNormativa->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>