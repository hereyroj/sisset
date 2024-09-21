<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerPublicaciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        {!! Form::select('publicacionesFiltros', $filtroPublicaciones, $sFiltroPublicaciones, ['class'=>'form-control', 'id'=>'criterios',
        'style' => 'border-radius:0;height:40px;']) !!}
    </div>
    <div class="field-search input-group">
        <input type="text" name="filtrarNotificaciones" id="filtrarNotificaciones">
        <button type="button" class="btn-buscar" onclick="filtrarPublicaciones();">
            <i class="fas fa-search"></i>
        </button>
        <button type="button" class="btn-restaurar" onclick="obtenerPublicaciones();">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div>
        <a class="btn btn-secondary btn-actualizar btn-md" href="{{url('admin/posts/nuevaPublicacion')}}">Nueva</a>
    </div>
</div>
<table class="table table-responsible table-striped">
    <thead>
        <tr>
            <th>Título</th>
            <th>Slug</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Fecha publicación</th>
            <th>Fecha despublicación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>
            <td>{{$post->title}}</td>
            <td>{{$post->slug}}</td>
            <td>{{$post->hasCategoria->name}}</td>
            <td>{{$post->hasEstado->name}}</td>
            <td>{{$post->published_date}}</td>
            <td>{{$post->unpublished_date}}</td>
            <td><a class="btn btn-secondary" href="{{url('/admin/posts/editarPublicacion/'.$post->id)}}">Editar</a></td>
        </tr>
        @endforeach
    </tbody>
</table>