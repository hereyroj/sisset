<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerCategorias();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('categoriasFiltros', $criterios, $sFiltroCategorias, ['class'=>'form-control', 'id'=>'criterios', 'style'
        => 'border-radius:0;height:40px;']) !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarNotificaciones" id="filtrarNotificaciones">
        <button type="button" class="btn-buscar" onclick="filtrarCategorias();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerCategorias();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-secondary btn-actualizar btn-md" onclick="nuevaCategoria()">Nueva</button>
    </div>
</div>
<table class="table table-responsible table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Slug</th>
            <th>Descripción</th>
            <th>Icono</th>
            <th>Categoría superior</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorias as $categoria)
        <tr>
            <td>{{$categoria->name}}</td>
            <td>{{$categoria->slug}}</td>
            <td>{{$categoria->description}}</td>
            <td>@if($categoria->icon != null)<img src="{{asset('storage/posts/'.$categoria->icono)}}" class="img-thumbnail">@endif</td>
            <td>@if($categoria->hasParentCategory != null){{$categoria->hasParentCategory->name}}@endif</td>
            <td><button type="button" class="btn btn-secondary" onclick="editarCategoria({{$categoria->id}})">Editar</button></td>
        </tr>
        @endforeach
    </tbody>
</table>