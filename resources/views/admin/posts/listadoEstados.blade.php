<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerEstados();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('estadosFiltros', $filtroEstados, $sFiltroEstado, ['class'=>'form-control', 'id'=>'criterios', 'style'=>
        'border-radius:0;height:40px;']) !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarNotificaciones" id="filtrarNotificaciones">
        <button type="button" class="btn-buscar" onclick="filtrarEstados();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerEstados();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevoEstado()">Nuevo</button>
    </div>
</div>
<table class="table table-responsible table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Publicaciones visibles?</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estados as $estado)
        <tr>
            <td>{{$estado->name}}</td>
            <td>@if($estado->show_post) Sí @else No @endif</td>
            <td><button type="button" class="btn btn-secondary" onclick="editarEstado({{$estado->id}})">Editar</button></td>
        </tr>
        @endforeach
    </tbody>
</table>